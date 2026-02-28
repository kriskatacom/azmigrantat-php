<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Autobus;
use App\Models\City;
use App\Models\Country;
use App\Services\FileService;

class AutobusController extends BaseController
{
    private Autobus $autobusModel;
    private Country $countryModel;
    private City $cityModel;

    public function __construct()
    {
        $this->middleware('admin', ['index']);

        $this->autobusModel = new Autobus();
        $this->countryModel = new Country();
        $this->cityModel = new City();
    }

    public function index()
    {
        $pageData = $this->paginate($this->autobusModel);

        $autobuses = $this->autobusModel->getWithRelations(
            $pageData['limit'],
            $pageData['offset']
        );

        View::render('admin/autobuses/index', [
            'title'      => 'Автобусни компании',
            'autobuses'  => $autobuses,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $countries = $this->countryModel->all();

        View::render('admin/autobuses/form', [
            'title'     => 'Нова автобусна компания',
            'countries' => $countries,
            'layout'    => 'admin'
        ]);
    }

    public function edit($id)
    {
        $autobus = $this->autobusModel->find($id);

        if (!$autobus) {
            header('Location: /admin/cities');
            exit;
        }

        $countries = $this->countryModel->all();
        $cities = $this->cityModel->getByCountry($autobus['country_id']);

        View::render('admin/autobuses/form', [
            'title'     => 'Редактиране на автобусна компания: ' . $autobus['name'],
            'autobus'       => $autobus,
            'countries' => $countries,
            'cities'    => $cities,
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->handleStore($this->autobusModel, '/admin/autobuses', ['image_url'], 'airlines');
    }

    public function update($id)
    {
        $this->handleUpdate($this->autobusModel, (int)$id, '/admin/autobuses', ['image_url'], 'autobuses');
    }

    public function delete($id)
    {
        $this->handleDelete($this->autobusModel, (int)$id, null, ['image_url']);
    }
}
