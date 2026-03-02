<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Embassy;
use App\Models\Country;
use App\Core\View;
use App\Models\CountryElement;

class EmbassyController extends BaseController
{
    private Embassy $embassyModel;
    private Country $countryModel;
    private CountryElement $elementModel;

    public function __construct()
    {
        $this->embassyModel = new Embassy();
        $this->countryModel = new Country();
        $this->elementModel = new CountryElement();
    }

    // public routes

    public function indexByCountry($countrySlug)
    {
        $country = $this->countryModel->where('slug', $countrySlug)[0] ?? null;

        if (!$country) {
            header("HTTP/1.0 404 Not Found");
            exit('Държавата не е намерена.');
        }

        $embassyElement = $this->elementModel->all([
            'where' => [
                'country_id' => $country['id'],
                'slug'       => 'embassies',
                'is_active'  => 1
            ]
        ])[0] ?? null;

        $embassies = $this->embassyModel->all([
            'where' => [
                'country_id' => $country['id'],
                'is_active'  => 1
            ],
            'order' => 'sort_order ASC'
        ]);

        View::render('embassies/index', [
            'title'          => 'Посолства в ' . $country['name'],
            'country'        => $country,
            'embassyElement' => $embassyElement,
            'embassies'      => $embassies
        ]);
    }

    public function show($countrySlug, $embassySlug)
    {
        $country = $this->countryModel->where('slug', $countrySlug)[0] ?? null;

        if (!$country) {
            $this->abort(404);
        }

        $embassy = $this->embassyModel->all([
            'where' => [
                'slug' => $embassySlug,
                'country_id' => $country['id']
            ]
        ])[0] ?? null;

        if (!$embassy) {
            $this->abort(404);
        }

        View::render('embassies/show', [
            'title'   => $embassy['name'],
            'embassy' => $embassy,
            'country' => $country
        ]);
    }

    // admin routes

    public function index()
    {
        $this->checkAccess('admin');
        $pageData = $this->paginate($this->embassyModel);

        $embassies = $this->embassyModel->getAllWithCountries([
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC, name ASC'
        ]);

        View::render('admin/embassies/index', [
            'title'      => 'Посолства',
            'embassies'  => $embassies,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $countryModel = new Country();
        return View::render('admin/embassies/form', [
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'title' => 'Добавяне на посолство',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->embassyModel, '/admin/embassies', ['image_url', 'logo', 'right_heading_image'], 'embassies');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
        $embassy = $this->embassyModel->find((int)$id);
        if (!$embassy) {
            header('Location: /admin/embassies?error=notfound');
            exit;
        }

        $countryModel = new Country();
        return View::render('admin/embassies/form', [
            'title' => 'Редактиране на посолство',
            'layout' => 'admin',
            'embassy'  => $embassy,
            'countries' => $countryModel->all(['order' => 'name ASC'])
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->embassyModel, (int)$id, '/admin/embassies', ['image_url', 'logo', 'right_heading_image'], 'embassies');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->embassyModel);
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->embassyModel, (int)$id, null, ['logo', 'image_url', 'right_heading_image']);
    }
}
