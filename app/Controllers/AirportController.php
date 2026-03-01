<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Airport;
use App\Models\Country;

class AirportController extends BaseController
{
    protected Airport $airportModel;
    protected Country $countryModel;

    public function __construct()
    {
        $this->airportModel = new Airport();
        $this->countryModel = new Country();
    }

    public function index()
    {
        $this->checkAccess('admin');
        $pageData = $this->paginate($this->airportModel);

        $airports = $this->airportModel->getAllWithCountries([
            'order'  => 'sort_order ASC',
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset']
        ]);

        $this->render('admin/airports/index', [
            'title'      => 'Авиолинии',
            'airports'   => $airports,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $this->render('admin/airports/form', [
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'title'     => 'Добавяне на летище',
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->airportModel, '/admin/airports', ['image_url'], 'airports');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
        $airport = $this->airportModel->find((int)$id);
        if (!$airport) $this->redirect('/admin/airports');

        $this->render('admin/airports/form', [
            'airport'   => $airport,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'title'     => 'Редакция: ' . $airport['name'],
            'layout'    => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->airportModel, (int)$id, '/admin/airports', ['image_url'], 'airports');
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->airportModel, (int)$id, '/admin/airports', ['image_url']);
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->airportModel);
    }
}