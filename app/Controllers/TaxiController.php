<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Banner;
use App\Models\Taxi;
use App\Models\Country;
use App\Models\City;

class TaxiController extends BaseController
{
    protected Taxi $taxiModel;
    private Country $countryModel;
    private City $cityModel;
    private Banner $bannerModel;
    protected string $baseRoute = '/admin/taxis';

    public function __construct()
    {
        $this->taxiModel = new Taxi();
        $this->countryModel = new Country();
        $this->cityModel = new City();
        $this->bannerModel = new Banner();
    }

    public function showCountries()
    {
        $banner = $this->bannerModel->findByColumn('link', '/travel/taxis/countries');
        $taxisBanner = $this->bannerModel->findByColumn('link', '/travel/taxis');
        $countries = $this->countryModel->all();

        $this->render('travel/taxis/countries/index', [
            'title' => 'Таксиметрови компании в Европа – Информация по държави',
            'banner' => $banner,
            'taxisBanner' => $taxisBanner,
            'countries' => $countries
        ]);
    }

    public function showCitiesByCountry($countrySlug)
    {
        $country = $this->countryModel->findByColumn('slug', $countrySlug);

        if (!$country) {
            header("Location: /404");
            exit;
        }

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/taxis/countries');
        $banner = $this->bannerModel->findByColumn('link', '/travel/taxis/countries/' . $countrySlug) ?? $country;
        $taxisBanner = $this->bannerModel->findByColumn('link', '/travel/taxis');

        $cities = $this->cityModel->where('country_id', $country['id']);

        $this->render('travel/taxis/countries/show-by-country/index', [
            'title' => "Таксиметрови компании в {$country['name']} – Адреси и информация",
            'banner' => $banner,
            'countriesBanner' => $countriesBanner,
            'taxisBanner' => $taxisBanner,
            'country' => $country,
            'cities' => $cities
        ]);
    }

    public function showByCityAndCountry($countrySlug, $citySlug)
    {
        $country = $this->countryModel->findByColumn('slug', $countrySlug);
        $city = $this->cityModel->findByColumn('slug', $citySlug);

        if (!$country || !$city) {
            header("Location: /404");
            exit;
        }

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/taxis/countries');
        $banner = $this->bannerModel->findByColumn('link', '/travel/taxis/countries/' . $countrySlug) ?? $city;
        $taxisBanner = $this->bannerModel->findByColumn('link', '/travel/taxis');

        $taxis = $this->taxiModel->where('city_id', $city['id']);

        $this->render('travel/taxis/countries/show-by-country/show-by-city/index', [
            'title' => "Таксиметрови компании в {$city['name']}, {$country['name']} – Локации и линии",
            'banner' => $banner,
            'countriesBanner' => $countriesBanner,
            'taxisBanner' => $taxisBanner,
            'country' => $country,
            'city' => $city,
            'taxis' => $taxis
        ]);
    }

    public function index()
    {
        $this->checkAccess('admin');
        $paginationData = $this->paginate($this->taxiModel);
        $taxis = $this->taxiModel->all([
            'limit' => $paginationData['limit'],
            'offset' => $paginationData['offset'],
            'order' => 'sort_order ASC'
        ]);

        $this->render('admin/taxis/index', [
            'title' => 'Таксита',
            'taxis' => $taxis,
            'pagination' => $paginationData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $countryModel = new Country();
        $this->render('admin/taxis/form', [
            'title' => 'Създаване на нова такси компания',
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'taxi' => null,
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->taxiModel, $this->baseRoute, ['image_url'], 'taxis');
    }

    public function edit(int $id)
    {
        $this->checkAccess('admin');
        $taxi = $this->taxiModel->find($id);
        if (!$taxi) {
            $this->flash('error', 'Такси компанията не е намерена.');
            $this->redirect($this->baseRoute);
        }

        $countryModel = new Country();
        $cityModel = new City();

        $this->render('admin/taxis/form', [
            'title' => 'Редактиране на такси компания: ' . $taxi['name'],
            'taxi' => $taxi,
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'cities' => $cityModel->where('country_id', $taxi['country_id']),
            'layout'     => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->taxiModel, $id, $this->baseRoute, ['image_url'], 'taxis');
    }

    public function delete(int $id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->taxiModel, $id, $this->baseRoute);
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->taxiModel);
    }
}