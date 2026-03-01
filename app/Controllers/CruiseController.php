<?php

namespace App\Controllers;

use App\Models\Cruise;
use App\Core\View;

class CruiseController extends BaseController
{
    private Cruise $cruiseModel;

    public function __construct()
    {
        $this->cruiseModel = new Cruise();
    }

    public function index()
    {
        $this->checkAccess('admin');
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
        $this->checkAccess('admin');
        return View::render('admin/cruises/form', [
            'title'  => 'Добавяне на круизна компания',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->cruiseModel, '/admin/cruises', ['image_url'], 'cruises');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
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
        $this->checkAccess('admin');
        $this->handleUpdate($this->cruiseModel, (int)$id, '/admin/cruises', ['image_url'], 'cruises');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->cruiseModel);
    }

    public function delete(int $id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->cruiseModel, (int)$id, null, ['image_url']);
    }
}
