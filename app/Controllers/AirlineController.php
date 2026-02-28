<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Airline;
use App\Models\Country;
use App\Models\City;
use App\Models\Category;

class AirlineController extends BaseController
{
    private Airline $airlineModel;

    public function __construct()
    {
        $this->middleware('admin');
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
            'title'      => 'Компании',
            'airlines'  => $airlines,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->render('admin/airlines/form', [
            'title'      => 'Нова авиокомпания',
            'countries'  => (new Country())->all(['order' => 'name ASC']),
            'categories' => (new Category())->all(['order' => 'name ASC']),
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleStore($this->airlineModel, '/admin/airlines', $fileFields, 'airlines');
    }

    public function edit($id)
    {
        $airline = $this->airlineModel->find((int)$id);

        if (!$airline) {
            $this->flash('error', 'Авиокомпанията не е намерена.');
            $this->redirect('/admin/airlines');
        }

        $this->render('admin/airlines/form', [
            'title'      => 'Редакция: ' . $airline['name'],
            'airline'    => $airline,
            'categories' => (new Category())->all(['order' => 'name ASC']),
            'layout'     => 'admin'
        ]);
    }

    public function update($id)
    {
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleUpdate($this->airlineModel, (int)$id, '/admin/airlines', $fileFields, 'airlines');
    }

    public function updateOrder()
    {
        $this->handleOrderUpdate($this->airlineModel);
    }

    public function delete($id)
    {
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleDelete($this->airlineModel, (int)$id, null, $fileFields);
    }
}
