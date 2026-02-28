<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;

class CountryController extends BaseController
{
    private Country $countryModel;

    public function __construct()
    {
        $this->middleware('admin', ['index']);

        $this->countryModel = new Country();
    }

    public function index()
    {
        $pageData = $this->paginate($this->countryModel);

        $countries = $this->countryModel->all([
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC, name ASC'
        ]);

        View::render('admin/countries/index', [
            'title'      => 'Държави',
            'countries'  => $countries,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        View::render('admin/countries/form', ['title' => 'Добавяне на държава', 'layout' => 'admin']);
    }

    public function store()
    {
        $this->handleStore($this->countryModel, '/admin/countries', ['image_url'], 'countries');
    }

    public function edit($id)
    {
        $country = $this->countryModel->find($id);
        View::render('admin/countries/form', [
            'title' => 'Редактиране на ' . $country['name'],
            'country' => $country,
            'layout' => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->handleUpdate($this->countryModel, (int)$id, '/admin/countries', ['image_url'], 'countries');
    }

    public function show(int $id)
    {
        $country = $this->countryModel->find($id);

        if (!$country) {
            $this->json(['message' => 'Country not found'], 404);
        }

        $this->json($country);
    }

    public function updateOrder()
    {
        return $this->handleOrderUpdate($this->countryModel);
    }

    public function delete($id)
    {
        $this->handleDelete($this->countryModel, (int)$id, null, ['image_url']);
    }
}