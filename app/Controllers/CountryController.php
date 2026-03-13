<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;
use App\Models\CountryElement;
use App\Services\HelperService;

class CountryController extends BaseController
{
    private Country $countryModel;

    public function __construct()
    {
        $this->countryModel = new Country();
    }

    // public routes

    public function show($countrySlug)
    {
        $country = $this->countryModel->where('slug', $countrySlug)[0] ?? null;

        if (!$country || !$country['is_active']) {
            header("Location: /");
            exit;
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
        $this->checkAccess('admin');
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
        $this->checkAccess('admin');
        View::render('admin/countries/form', ['title' => 'Добавяне на държава', 'layout' => 'admin']);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->countryModel, '/admin/countries', ['image_url'], 'countries');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');

        $country = $this->countryModel->find($id);
        if (!$country) {
            $this->flash('error', 'Записът не е намерен.');
            $this->redirect('/admin/countries');
        }

        $country['translations'] = $this->getMappedTranslations('country', $id);

        $nextId = $this->countryModel->getNextId($id);
        $prevId = $this->countryModel->getPrevId($id);

        View::render('admin/countries/form', [
            'title'        => 'Редактиране на ' . $country['name'],
            'country'      => $country,
            'nextId'       => $nextId,
            'prevId'       => $prevId,
            'languages'    => HelperService::AVAILABLE_LANGUAGES,
            'layout'       => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->countryModel, (int)$id, '/admin/countries', ['image_url'], 'countries');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->countryModel);
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->countryModel, (int)$id, null, ['image_url']);
    }
}
