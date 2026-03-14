<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Autobus;
use App\Models\Banner;
use App\Models\City;
use App\Models\Country;
use App\Services\HelperService;

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
        $countries = $this->countryModel->all(['order' => 'name ASC']);

        if ($banner) $banner['entity_type'] = 'banner';
        if ($autobusesBanner) $autobusesBanner['entity_type'] = 'banner';

        foreach ($countries as &$c) {
            $c['entity_type'] = 'country';
        }

        $this->render('travel/autobuses/countries/index', [
            'title' => HelperService::trans('bus_stations_europe_title') ?? 'Автобусни гари и превози в Европа',
            'banner' => $banner,
            'autobusesBanner' => $autobusesBanner,
            'countries' => $countries
        ]);
    }

    public function showCitiesByCountry($countrySlug)
    {
        $country = $this->countryModel->findByColumn('slug', $countrySlug);
        if (!$country) return $this->abort(404);

        $country['entity_type'] = 'country';

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses/countries');
        $autobusesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses');

        $banner = $this->bannerModel->findByColumn('link', '/travel/autobuses/countries/' . $countrySlug) ?? $country;

        if (isset($banner['group_key'])) {
            $banner['entity_type'] = 'banner';
        } else {
            $banner['entity_type'] = 'country';
        }

        if ($countriesBanner) $countriesBanner['entity_type'] = 'banner';
        if ($autobusesBanner) $autobusesBanner['entity_type'] = 'banner';

        $cities = $this->cityModel->where('country_id', $country['id']);
        foreach ($cities as &$city) {
            $city['entity_type'] = 'city';
        }

        $translatedCountryName = HelperService::getTranslation($country, 'name');

        $this->render('travel/autobuses/countries/show-by-country/index', [
            'title' => HelperService::trans('bus_stations_in') . " {$translatedCountryName}",
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

        if (!$country || !$city) return $this->abort(404);

        $country['entity_type'] = 'country';
        $city['entity_type'] = 'city';

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses/countries');
        $autobusesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses');

        $banner = $this->bannerModel->findByColumn('link', '/travel/autobuses/countries/' . $countrySlug . '/' . $citySlug) ?? $city;

        if (isset($banner['group_key'])) {
            $banner['entity_type'] = 'banner';
        } else {
            $banner['entity_type'] = 'city';
        }

        if ($countriesBanner) $countriesBanner['entity_type'] = 'banner';
        if ($autobusesBanner) $autobusesBanner['entity_type'] = 'banner';

        $autobuses = $this->autobusModel->where('city_id', $city['id']);
        foreach ($autobuses as &$a) {
            $a['entity_type'] = 'autobus';
        }

        $translatedCityName = HelperService::getTranslation($city, 'name');

        $this->render('travel/autobuses/countries/show-by-country/show-by-city/index', [
            'title' => HelperService::trans('bus_stations_in') . " {$translatedCityName}",
            'banner' => $banner,
            'countriesBanner' => $countriesBanner,
            'autobusesBanner' => $autobusesBanner,
            'country' => $country,
            'city' => $city,
            'autobuses' => $autobuses
        ]);
    }

    // Admin Methods

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

    public function edit($id)
    {
        $this->checkAccess('admin');
        $autobus = $this->autobusModel->find((int)$id);

        if (!$autobus) {
            $this->flash('error', 'Записът не е намерен.');
            $this->redirect('/admin/autobuses');
        }

        $autobus['translations'] = $this->getMappedTranslations('autobus', $id);

        View::render('admin/autobuses/form', [
            'title'     => 'Редактиране: ' . $autobus['name'],
            'autobus'   => $autobus,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'cities'    => $this->cityModel->getByCountry($autobus['country_id']),
            'languages' => HelperService::AVAILABLE_LANGUAGES,
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->autobusModel, '/admin/autobuses', ['image_url'], 'autobuses');
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