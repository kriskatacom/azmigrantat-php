<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Train;
use App\Models\Country;
use App\Models\City;

class TrainController extends BaseController
{
    protected Train $train;
    protected string $baseRoute = '/admin/trains';

    public function __construct()
    {
        $this->train = new Train();
    }

    public function index()
    {
        $this->checkAccess('admin');
        $paginationData = $this->paginate($this->train);
        $trains = $this->train->all([
            'limit' => $paginationData['limit'],
            'offset' => $paginationData['offset'],
            'order' => 'sort_order ASC'
        ]);

        $this->render('admin/trains/index', [
            'title' => 'Влакове',
            'trains' => $trains,
            'pagination' => $paginationData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $countryModel = new Country();
        $this->render('admin/trains/form', [
            'title' => 'Нова ЖП гара',
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'train' => null,
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->train, $this->baseRoute, ['image_url'], 'trains');
    }

    public function edit(int $id)
    {
        $this->checkAccess('admin');
        $train = $this->train->find($id);
        if (!$train) {
            $this->flash('error', 'ЖП гарата не е намерена.');
            $this->redirect($this->baseRoute);
        }

        $countryModel = new Country();
        $cityModel = new City();

        $this->render('admin/trains/form', [
            'title' => 'Редактиране на ЖП гара: ' . $train['name'],
            'train' => $train,
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'cities' => $cityModel->where('country_id', $train['country_id']),
            'layout'     => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->train, $id, $this->baseRoute, ['image_url'], 'trains');
    }

    public function delete(int $id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->train, $id, $this->baseRoute);
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->train);
    }
}