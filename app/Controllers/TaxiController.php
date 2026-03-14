<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Banner;
use App\Models\Taxi;
use App\Models\Country;
use App\Models\City;
use App\Services\HelperService;

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
        $countries = $this->countryModel->all(['order' => 'name ASC']);

        if ($banner) $banner['entity_type'] = 'banner';
        if ($taxisBanner) $taxisBanner['entity_type'] = 'banner';
        
        foreach ($countries as &$country) {
            $country['entity_type'] = 'country';
        }

        $this->render('travel/taxis/countries/index', [
            'title' => HelperService::trans('taxi_companies_europe_title') ?? 'Таксиметрови компании в Европа',
            'banner' => $banner,
            'taxisBanner' => $taxisBanner,
            'countries' => $countries
        ]);
    }

    public function showCitiesByCountry($countrySlug)
    {
        $country = $this->countryModel->findByColumn('slug', $countrySlug);
        if (!$country) return $this->abort(404);

        $country['entity_type'] = 'country';

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/taxis/countries');
        $taxisBanner = $this->bannerModel->findByColumn('link', '/travel/taxis');
        $banner = $this->bannerModel->findByColumn('link', '/travel/taxis/countries/' . $countrySlug) ?? $country;
        
        if (isset($banner['group_key'])) {
            $banner['entity_type'] = 'banner';
        } else {
            $banner['entity_type'] = 'country';
        }

        if ($countriesBanner) $countriesBanner['entity_type'] = 'banner';
        if ($taxisBanner) $taxisBanner['entity_type'] = 'banner';

        $cities = $this->cityModel->where('country_id', $country['id']);
        foreach ($cities as &$city) {
            $city['entity_type'] = 'city';
        }

        $translatedCountryName = HelperService::getTranslation($country, 'name');

        $this->render('travel/taxis/countries/show-by-country/index', [
            'title' => HelperService::trans('taxi_companies_in') . " {$translatedCountryName}",
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

        if (!$country || !$city) return $this->abort(404);

        $country['entity_type'] = 'country';
        $city['entity_type'] = 'city';

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/taxis/countries');
        $taxisBanner = $this->bannerModel->findByColumn('link', '/travel/taxis');
        $banner = $this->bannerModel->findByColumn('link', '/travel/taxis/countries/' . $countrySlug) ?? $city;
        
        if (isset($banner['group_key'])) {
            $banner['entity_type'] = 'banner';
        } else {
            $banner['entity_type'] = 'city';
        }

        if ($countriesBanner) $countriesBanner['entity_type'] = 'banner';
        if ($taxisBanner) $taxisBanner['entity_type'] = 'banner';

        $taxis = $this->taxiModel->where('city_id', $city['id']);
        foreach ($taxis as &$taxi) {
            $taxi['entity_type'] = 'taxi';
        }

        $translatedCityName = HelperService::getTranslation($city, 'name');

        $this->render('travel/taxis/countries/show-by-country/show-by-city/index', [
            'title' => HelperService::trans('taxi_companies_in') . " {$translatedCityName}",
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
        $this->render('admin/taxis/form', [
            'title' => 'Създаване на нова такси компания',
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
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

        $taxi['translations'] = $this->getMappedTranslations('taxi', $id);

        $this->render('admin/taxis/form', [
            'title' => 'Редактиране: ' . $taxi['name'],
            'taxi' => $taxi,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'cities' => $this->cityModel->where('country_id', $taxi['country_id']),
            'languages' => HelperService::AVAILABLE_LANGUAGES,
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