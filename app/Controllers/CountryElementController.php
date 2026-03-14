<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\CountryElement;
use App\Models\Country;
use App\Services\HelperService;

class CountryElementController extends BaseController
{
    private CountryElement $elementModel;
    private Country $countryModel;

    public function __construct()
    {
        $this->elementModel = new CountryElement();
        $this->countryModel = new Country();
    }

    public function index()
    {
        $this->checkAccess('admin');
        $countryId = (int)($_GET['country_id'] ?? 0);

        if (!$countryId) {
            $this->flash('error', 'Не е избрана държава.');
            $this->redirect('/admin/countries');
        }

        $country = $this->countryModel->find($countryId);

        if (!$country) {
            $this->flash('error', 'Държавата не съществува.');
            $this->redirect('/admin/countries');
        }

        $elements = $this->elementModel->getByCountry($country['id']);

        View::render('admin/country_elements/index', [
            'title'    => 'Елементи за ' . $country['name'],
            'elements' => $elements,
            'country'  => $country,
            'layout'   => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $countryId = (int)($_GET['country_id'] ?? 0);

        View::render('admin/country_elements/form', [
            'title'     => 'Добавяне на елемент',
            'countries' => $this->countryModel->all(['order'  => 'sort_order ASC, name ASC']),
            'country_id' => $countryId,
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $countryId = $_POST['country_id'] ?? 0;
        $redirectUrl = "/admin/countries/country-elements?country_id=" . $countryId;
        
        $this->handleStore($this->elementModel, $redirectUrl, ['image_url'], 'country_elements');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
        $element = $this->elementModel->find((int)$id);
        
        if (!$element) {
            $this->flash('error', 'Елементът не е намерен');
            $this->redirect('/admin/countries');
        }

        $element['translations'] = $this->getMappedTranslations('country_element', $id);

        $nextId = $this->elementModel->getNextId($id, ['country_id' => $element['country_id']]);
        $prevId = $this->elementModel->getPrevId($id, ['country_id' => $element['country_id']]);

        View::render('admin/country_elements/form', [
            'title'     => 'Редактиране на елемент',
            'element'   => $element,
            'countries' => $this->countryModel->all(['order'  => 'sort_order ASC, name ASC']),
            'languages' => HelperService::AVAILABLE_LANGUAGES,
            'nextId'    => $nextId,
            'prevId'    => $prevId,
            'layout'    => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $countryId = $_POST['country_id'] ?? 0;
        $redirectUrl = "/admin/countries/country-elements?country_id=" . $countryId;

        $this->handleUpdate($this->elementModel, (int)$id, $redirectUrl, ['image_url'], 'country_elements');
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        
        $element = $this->elementModel->find((int)$id);
        $redirectUrl = $element ? "/admin/countries/country-elements?country_id={$element['country_id']}" : "/admin/countries";

        $this->handleDelete($this->elementModel, (int)$id, $redirectUrl, ['image_url']);
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->elementModel);
    }
}