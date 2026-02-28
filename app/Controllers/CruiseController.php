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
        $this->middleware('admin', ['index']);

        $this->cruiseModel = new Cruise();
    }

    public function index()
    {
        $pageData = $this->paginate($this->cruiseModel);

        $cruises = $this->cruiseModel->all([
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC, name ASC'
        ]);

        View::render('admin/cruises/index', [
            'title'      => 'Круизни компании',
            'cruises'    => $cruises,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
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
        $this->handleStore($this->cruiseModel, '/admin/cruises', ['image_url'], 'cruises');
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
        $this->handleUpdate($this->cruiseModel, (int)$id, '/admin/cruises/edit/' . $id, ['image_url'], 'cruises');
    }

    public function updateOrder()
    {
        return $this->handleOrderUpdate($this->cruiseModel);
    }

    public function delete(int $id)
    {
        $this->handleDelete($this->cruiseModel, (int)$id, null, ['image_url']);
    }
}
