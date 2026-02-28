<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\City;
use App\Models\Country;
use App\Core\View;
use App\Services\FileService;
use App\Services\HelperService;

class CityController extends BaseController
{
    protected City $cityModel;
    protected Country $countryModel;

    public function __construct()
    {
        $this->middleware('admin', ['index', 'getByCountry']);
        
        $this->cityModel = new City();
        $this->countryModel = new Country();
    }

    public function index()
    {
        $pageData = $this->paginate($this->cityModel);

        $cities = $this->cityModel->getWithCountry(
            $pageData['limit'],
            $pageData['offset']
        );

        View::render('admin/cities/index', [
            'title'      => 'Управление на градове',
            'cities'     => $cities,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        View::render('admin/cities/form', [
            'title' => 'Добавяне на град',
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $data = $_POST;

        $data['slug'] = !empty($data['slug'])
            ? HelperService::slug($data['slug'])
            : HelperService::slug($data['name']);

        if (!empty($_FILES['image_url']['name'])) {
            $data['image_url'] = FileService::upload($_FILES['image_url']);
        }

        $data['country_id'] = (int)$data['country_id'];
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        unset($data['remove_image_url']);

        $newId = $this->cityModel->create($data);

        if ($newId) {
            $this->flash('success', "Градът беше добавен успешно.");
            header('Location: /admin/cities/edit/' . $newId);
        } else {
            $this->flash('error', "Възникна грешка при записа.");
            header('Location: /admin/cities/create');
        }
        exit;
    }

    public function edit(int $id)
    {
        $city = $this->cityModel->find($id);

        if (!$city) {
            header('Location: /admin/cities');
            exit;
        }

        View::render('admin/cities/form', [
            'title' => 'Редактиране на ' . $city['name'],
            'city' => $city,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'layout' => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $city = $this->cityModel->find($id);
        if (!$city) {
            $this->flash('error', 'Градът не е намерен!');
            header('Location: /admin/cities');
            exit;
        }

        $data = $_POST;
        $data['slug'] = !empty($data['slug'])
            ? HelperService::slug($data['slug'])
            : HelperService::slug($data['name']);

        $finalImageUrl = $city['image_url'];

        if (isset($data['remove_image_url']) && $data['remove_image_url'] == '1') {
            FileService::delete($city['image_url']);
            $finalImageUrl = null;
        }

        if (!empty($_FILES['image_url']['name'])) {
            FileService::delete($city['image_url']);
            $finalImageUrl = FileService::upload($_FILES['image_url']);
        }

        $data['image_url'] = $finalImageUrl;
        $data['country_id'] = (int)$data['country_id'];
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;

        unset($data['remove_image_url']);

        if ($this->cityModel->update($id, $data)) {
            $this->flash('success', 'Градът беше обновен успешно.');
        } else {
            $this->flash('error', 'Възникна грешка при обновяването.');
        }

        $redirectUrl = '/admin/cities/edit/' . $id;
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function getByCountry(int $countryId)
    {
        $cities = $this->cityModel->getByCountry($countryId);
        header('Content-Type: application/json');
        echo json_encode($cities);
        exit;
    }

    public function updateOrder()
    {
        return $this->handleOrderUpdate($this->cityModel);
    }

    public function delete(int $id)
    {
        $this->handleDelete($this->cityModel, (int)$id, null, ['image_url']);
    }
}
