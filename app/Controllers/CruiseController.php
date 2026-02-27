<?php

namespace App\Controllers;

use App\Models\Cruise;
use App\Core\View;
use App\Services\FileService;
use App\Services\HelperService;

class CruiseController extends BaseController
{
    private Cruise $cruiseModel;

    public function __construct()
    {
        $this->cruiseModel = new Cruise();
    }

    public function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 10);
        $offset = ($page - 1) * $perPage;

        $cruises = $this->cruiseModel->all([
            'limit' => $perPage,
            'offset' => $offset,
            'order' => 'sort_order ASC, name ASC'
        ]);

        $total = $this->cruiseModel->count();

        View::render('admin/cruises/index', [
            'title' => 'Круизни компании',
            'cruises' => $cruises,
            'layout' => 'admin',
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $perPage),
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create()
    {
        return View::render('admin/cruises/form', [
            'title'  => 'Добавяне на круизна компания',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $data = $_POST;
        $data['slug'] = !empty($data['slug']) ? HelperService::slug($data['slug']) : HelperService::slug($data['name']);

        if (!empty($_FILES['image_url']['name'])) {
            $data['image_url'] = FileService::upload($_FILES['image_url']);
        }

        $data['sort_order'] = !empty($data['sort_order']) ? (int)$data['sort_order'] : 0;
        unset($data['remove_image_url'], $data['return_url']);

        $newId = $this->cruiseModel->create($data);

        if ($newId) {
            $this->flash('success', 'Круиз компанията беше добавена!');
            header('Location: /admin/cruises/edit/' . $newId);
        } else {
            $this->flash('error', 'Грешка при записа.');
            header('Location: /admin/cruises');
        }
        exit;
    }

    public function edit($id)
    {
        $cruise = $this->cruiseModel->find((int)$id);
        if (!$cruise) exit('Круизът не е намерен');

        return View::render('admin/cruises/form', [
            'title'  => 'Редактиране: ' . $cruise['name'],
            'cruise' => $cruise,
            'layout' => 'admin'
        ]);
    }

    public function update($id)
    {
        $cruise = $this->cruiseModel->find((int)$id);
        $data = $_POST;

        $data['slug'] = !empty($data['slug']) ? HelperService::slug($data['slug']) : HelperService::slug($data['name']);

        $finalImage = $cruise['image_url'];
        if (isset($data['remove_image_url']) && $data['remove_image_url'] == '1') {
            FileService::delete($cruise['image_url']);
            $finalImage = null;
        }
        if (!empty($_FILES['image_url']['name'])) {
            FileService::delete($cruise['image_url']);
            $finalImage = FileService::upload($_FILES['image_url']);
        }
        $data['image_url'] = $finalImage;

        unset($data['remove_image_url'], $data['return_url']);

        if ($this->cruiseModel->update((int)$id, $data)) {
            $this->flash('success', 'Промените бяха запазени!');
        }

        header('Location: /admin/cruises/edit/' . $id);
        exit;
    }

    public function delete($id)
    {
        $cruise = $this->cruiseModel->find((int)$id);
        if ($cruise) {
            FileService::delete($cruise['image_url']);
            $this->cruiseModel->delete((int)$id);
            $this->flash('success', 'Записът беше изтрит.');
        }
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/admin/cruises';
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function updateOrder()
    {
        $this->middleware('admin');
        return $this->handleOrderUpdate($this->cruiseModel);
    }
}
