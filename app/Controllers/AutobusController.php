<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Autobus;
use App\Models\Banner;
use App\Models\City;
use App\Models\Country;

class AutobusController extends BaseController
{
    private Autobus $autobusModel;
    private Country $countryModel;
    private City $cityModel;
    private Banner $bannerModel;

    public function __construct()
    {
        $this->autobusModel = new Autobus();
        $this->countryModel = new Country();
        $this->cityModel = new City();
        $this->bannerModel = new Banner();
    }

    public function showCountries()
    {
        $banner = $this->bannerModel->findByColumn('link', '/travel/autobuses/countries');
        $autobusesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses');
        $countries = $this->countryModel->all();

        $this->render('travel/autobuses/countries/index', [
            'title' => 'Автобусни гари и превози в Европа – Информация по държави',
            'banner' => $banner,
            'autobusesBanner' => $autobusesBanner,
            'countries' => $countries
        ]);
    }

    public function showCitiesByCountry($countrySlug)
    {
        $country = $this->countryModel->findByColumn('slug', $countrySlug);
        
        if (!$country) {
            header("Location: /404"); exit;
        }

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses/countries');
        $banner = $this->bannerModel->findByColumn('link', '/travel/autobuses/countries/' . $countrySlug) ?? $country;
        $autobusesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses');
        
        $cities = $this->cityModel->where('country_id', $country['id']);

        $this->render('travel/autobuses/countries/show-by-country/index', [
            'title' => "Автобусни гари и превози в {$country['name']} – Адреси и информация",
            'banner' => $banner,
            'countriesBanner' => $countriesBanner,
            'autobusesBanner' => $autobusesBanner,
            'country' => $country,
            'cities' => $cities
        ]);
    }

    public function showByCityAndCountry($countrySlug, $citySlug)
    {
        $country = $this->countryModel->findByColumn('slug', $countrySlug);
        $city = $this->cityModel->findByColumn('slug', $citySlug);

        if (!$country || !$city) {
            header("Location: /404"); exit;
        }

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses/countries');
        $banner = $this->bannerModel->findByColumn('link', '/travel/autobuses/countries/' . $countrySlug) ?? $city;
        $autobusesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses');
        
        $autobuses = $this->autobusModel->where('city_id', $city['id']);

        $this->render('travel/autobuses/countries/show-by-country/show-by-city/index', [
            'title' => "Автобусни гари в {$city['name']}, {$country['name']} – Локации и линии",
            'banner' => $banner,
            'countriesBanner' => $countriesBanner,
            'autobusesBanner' => $autobusesBanner,
            'country' => $country,
            'city' => $city,
            'autobuses' => $autobuses
        ]);
    }

    public function index()
    {
        $this->checkAccess('admin');
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
        $this->checkAccess('admin');
        $countries = $this->countryModel->all();

        View::render('admin/autobuses/form', [
            'title'     => 'Нова автобусна компания',
            'countries' => $countries,
            'layout'    => 'admin'
        ]);
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
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
        $this->checkAccess('admin');
        $this->handleStore($this->autobusModel, '/admin/autobuses', ['image_url'], 'airlines');
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->autobusModel, (int)$id, '/admin/autobuses', ['image_url'], 'autobuses');
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->autobusModel, (int)$id, null, ['image_url']);
    }
}