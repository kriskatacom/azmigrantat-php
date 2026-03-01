<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;
use App\Models\CountryElement;

class CountryController extends BaseController
{
    private Country $countryModel;

    public function __construct()
    {
        $this->middleware('admin', ['show']);

        $this->countryModel = new Country();
    }

    // public routes

    public function show($countrySlug)
    {
        $country = $this->countryModel->where('slug', $countrySlug)[0] ?? null;

        if (!$country || !$country['is_active']) {
            header("HTTP/1.0 404 Not Found");
            exit('Държавата не е намерена или е деактивирана.');
        }

        $elementModel = new CountryElement();
        $elements = $elementModel->all([
            'where' => [
                'country_id' => $country['id'],
                'is_active' => 1
            ],
            'order' => 'sort_order ASC'
        ]);

        View::render('countries/show', [
            'country'  => $country,
            'elements' => $elements,
            'title'    => $country['heading'] ?: $country['name']
        ]);
    }

    // admin routes

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

    public function updateOrder()
    {
        return $this->handleOrderUpdate($this->countryModel);
    }

    public function delete($id)
    {
        $this->handleDelete($this->countryModel, (int)$id, null, ['image_url']);
    }
}
