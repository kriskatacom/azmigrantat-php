<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\City;
use App\Models\Country;
use App\Core\View;
use App\Models\CountryElement;
use App\Services\FileService;
use App\Services\HelperService;

class CityController extends BaseController
{
    protected City $cityModel;
    protected Country $countryModel;

    public function __construct()
    {
        $this->middleware('admin', ['index', 'getByCountry']);

        $this->cityModel = new City();
        $this->countryModel = new Country();
    }

    // public routes

    public function indexByCountry($countrySlug)
    {
        $countryModel = new Country();
        $cityModel = new City();
        $elementModel = new CountryElement();

        $countryResults = $countryModel->where('slug', $countrySlug);
        $country = $countryResults[0] ?? null;

        if (!$country || (isset($country['is_active']) && !$country['is_active'])) {
            header("HTTP/1.0 404 Not Found");
            exit('Държавата не е намерена.');
        }

        $elementResults = $elementModel->all([
            'where' => [
                'country_id' => $country['id'],
                'slug'       => 'cities',
                'is_active'  => 1
            ]
        ]);
        $cityElement = $elementResults[0] ?? null;

        $cities = $cityModel->all([
            'where' => [
                'country_id' => $country['id'],
                'is_active'  => 1
            ],
            'order' => 'sort_order ASC'
        ]);

        View::render('cities/index', [
            'title'       => 'Градове в ' . $country['name'],
            'country'     => $country,
            'cityElement' => $cityElement,
            'cities'      => $cities
        ]);
    }

    // admin routes

    public function index()
    {
        $pageData = $this->paginate($this->cityModel);

        $cities = $this->cityModel->getWithCountry(
            $pageData['limit'],
            $pageData['offset']
        );

        View::render('admin/cities/index', [
            'title'      => 'Управление на градове',
            'cities'     => $cities,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        View::render('admin/cities/form', [
            'title' => 'Добавяне на град',
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->handleStore($this->cityModel, '/admin/cities', ['image_url'], 'cities');
    }

    public function edit(int $id)
    {
        $city = $this->cityModel->find($id);

        if (!$city) {
            header('Location: /admin/cities');
            exit;
        }

        View::render('admin/cities/form', [
            'title' => 'Редактиране на ' . $city['name'],
            'city' => $city,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'layout' => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->handleUpdate($this->cityModel, (int)$id, '/admin/cities', ['image_url'], 'cities');
    }

    public function getByCountry(int $countryId)
    {
        $cities = $this->cityModel->getByCountry($countryId);
        header('Content-Type: application/json');
        echo json_encode($cities);
        exit;
    }

    public function updateOrder()
    {
        return $this->handleOrderUpdate($this->cityModel);
    }

    public function delete(int $id)
    {
        $this->handleDelete($this->cityModel, (int)$id, null, ['image_url']);
    }
}
