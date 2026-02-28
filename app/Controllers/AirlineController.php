<?php

namespace App\Controllers;

use App\Models\Airline;

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
        $this->handleStore($this->airlineModel, '/admin/airlines', ['image_url'], 'airlines');
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
        $this->handleUpdate($this->airlineModel, (int)$id, '/admin/airlines/edit/' . $id, ['image_url'], 'airlines');
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
