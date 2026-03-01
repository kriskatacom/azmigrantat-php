<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\taxi;
use App\Models\Country;
use App\Models\City;

class TaxiController extends BaseController
{
    protected Taxi $taxi;
    protected string $baseRoute = '/admin/taxis';

    public function __construct()
    {
        $this->taxi = new Taxi();
    }

    public function index()
    {
        $this->checkAccess('admin');
        $paginationData = $this->paginate($this->taxi);
        $taxis = $this->taxi->all([
            'limit' => $paginationData['limit'],
            'offset' => $paginationData['offset'],
            'order' => 'sort_order ASC'
        ]);

        $this->render('admin/taxis/index', [
            'title' => 'Таксита',
            'taxis' => $taxis,
            'pagination' => $paginationData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $countryModel = new Country();
        $this->render('admin/taxis/form', [
            'title' => 'Създаване на нова такси компания',
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'taxi' => null,
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->taxi, $this->baseRoute, ['image_url'], 'taxis');
    }

    public function edit(int $id)
    {
        $this->checkAccess('admin');
        $taxi = $this->taxi->find($id);
        if (!$taxi) {
            $this->flash('error', 'Такси компанията не е намерена.');
            $this->redirect($this->baseRoute);
        }

        $countryModel = new Country();
        $cityModel = new City();

        $this->render('admin/taxis/form', [
            'title' => 'Редактиране на такси компания: ' . $taxi['name'],
            'taxi' => $taxi,
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'cities' => $cityModel->where('country_id', $taxi['country_id']),
            'layout'     => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->taxi, $id, $this->baseRoute, ['image_url'], 'taxis');
    }

    public function delete(int $id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->taxi, $id, $this->baseRoute);
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->taxi);
    }
}