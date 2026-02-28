<?php

namespace App\Controllers;

use App\Models\Airline;
use App\Services\FileService;

class AirlineController extends BaseController
{
    private Airline $airlineModel;

    public function __construct()
    {
        $this->middleware('admin', ['index']);
        $this->airlineModel = new Airline();
    }

    public function index()
    {
        $pageData = $this->paginate($this->airlineModel);

        $airlines = $this->airlineModel->all([
            'order'  => 'sort_order ASC',
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset']
        ]);

        $this->render('admin/airlines/index', [
            'title'      => 'Авиолинии',
            'airlines'   => $airlines,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->render('admin/airlines/form', [
            'title'  => 'Нова авиокомпания',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $data = $this->airlineModel->prepareData($_POST);

        if (!empty($_FILES['image']['name'])) {
            $data['image_url'] = FileService::upload($_FILES['image'], 'airlines');
        }

        unset($data['remove_image']);

        $newId = $this->airlineModel->create($data);

        if ($newId) {
            $this->flash('success', 'Авиокомпания беше създадена успешно!');
        }

        header('Location: /admin/airlines/edit/' . $newId);
        exit;
    }

    public function edit($id)
    {
        $airline = $this->airlineModel->find((int)$id);

        if (!$airline) {
            $this->flash('error', 'Авиокомпания не е намерена.');
            $this->redirect('/admin/airlines');
        }

        $this->render('admin/airlines/form', [
            'title'   => 'Редакция: ' . $airline['name'],
            'airline' => $airline,
            'layout'  => 'admin'
        ]);
    }

    public function update($id)
    {
        $airline = $this->airlineModel->find((int)$id);
        if (!$airline) $this->redirect('/admin/airlines');

        $data = $this->airlineModel->prepareData($_POST);

        if (!empty($_FILES['image']['name'])) {
            FileService::delete($airline['image_url']);
            $data['image_url'] = FileService::upload($_FILES['image'], 'airlines');
        }

        unset($data['remove_image']);

        if ($this->airlineModel->update((int)$id, $data)) {
            $this->flash('success', 'Данните бяха обновени!');
            $this->redirect('/admin/airlines');
        }
    }

    public function updateOrder()
    {
        $this->handleOrderUpdate($this->airlineModel);
    }

    public function delete($id)
    {
        $this->handleDelete($this->airlineModel, (int)$id, null, ['image_url']);
    }
}
