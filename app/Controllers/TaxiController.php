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
        $this->middleware('admin');
        $this->taxi = new Taxi();
    }

    public function index()
    {
        $paginationData = $this->paginate($this->taxi);
        $taxis = $this->taxi->all([
            'limit' => $paginationData['limit'],
            'offset' => $paginationData['offset'],
            'order' => 'sort_order ASC'
        ]);

        $this->render('admin/taxis/index', [
            'title' => 'Управление на влакове',
            'taxis' => $taxis,
            'pagination' => $paginationData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $countryModel = new Country();
        $this->render('admin/taxis/form', [
            'title' => 'Нов влак',
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'taxi' => null,
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->handleStore($this->taxi, $this->baseRoute, ['image_url'], 'taxis');
    }

    public function edit(int $id)
    {
        $taxi = $this->taxi->find($id);
        if (!$taxi) {
            $this->flash('error', 'Влакът не е намерен.');
            $this->redirect($this->baseRoute);
        }

        $countryModel = new Country();
        $cityModel = new City();

        $this->render('admin/taxis/form', [
            'title' => 'Редактиране на влак: ' . $taxi['name'],
            'taxi' => $taxi,
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'cities' => $cityModel->where('country_id', $taxi['country_id']),
            'layout'     => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->handleUpdate($this->taxi, $id, $this->baseRoute, ['image_url'], 'taxis');
    }

    public function delete(int $id)
    {
        $this->handleDelete($this->taxi, $id, $this->baseRoute);
    }

    public function updateOrder()
    {
        $this->handleOrderUpdate($this->taxi);
    }
}
