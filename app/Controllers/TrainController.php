<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Banner;
use App\Models\Train;
use App\Models\Country;
use App\Models\City;
use App\Services\HelperService;

class TrainController extends BaseController
{
    protected Train $trainModel;
    private Country $countryModel;
    private City $cityModel;
    private Banner $bannerModel;
    protected string $baseRoute = '/admin/trains';

    public function __construct()
    {
        $this->trainModel = new Train();
        $this->countryModel = new Country();
        $this->cityModel = new City();
        $this->bannerModel = new Banner();
    }

    public function showCountries()
    {
        $banner = $this->bannerModel->findByColumn('link', '/travel/trains/countries');
        $trainsBanner = $this->bannerModel->findByColumn('link', '/travel/trains');
        $countries = $this->countryModel->all(['order' => 'name ASC']);

        if ($banner) $banner['entity_type'] = 'banner';
        if ($trainsBanner) $trainsBanner['entity_type'] = 'banner';

        foreach ($countries as &$country) {
            $country['entity_type'] = 'country';
        }

        $this->render('travel/trains/countries/index', [
            'title' => HelperService::trans('trains_europe_title') ?? 'Жележопътни гари и превози в Европа',
            'banner' => $banner,
            'trainsBanner' => $trainsBanner,
            'countries' => $countries
        ]);
    }

    public function showCitiesByCountry($countrySlug)
    {
        $country = $this->countryModel->findByColumn('slug', $countrySlug);
        if (!$country) return $this->abort(404);

        $country['entity_type'] = 'country';

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/trains/countries');
        $trainsBanner = $this->bannerModel->findByColumn('link', '/travel/trains');

        // Търсим специфичен банер или ползваме държавата
        $banner = $this->bannerModel->findByColumn('link', '/travel/trains/countries/' . $countrySlug) ?? $country;

        // Определяме типа на банера
        if (isset($banner['group_key'])) {
            $banner['entity_type'] = 'banner';
        } else {
            $banner['entity_type'] = 'country';
        }

        if ($countriesBanner) $countriesBanner['entity_type'] = 'banner';
        if ($trainsBanner) $trainsBanner['entity_type'] = 'banner';

        $cities = $this->cityModel->where('country_id', $country['id']);
        foreach ($cities as &$city) {
            $city['entity_type'] = 'city';
        }

        $translatedCountryName = HelperService::getTranslation($country, 'name');

        $this->render('travel/trains/countries/show-by-country/index', [
            'title' => HelperService::trans('train_stations_in') . " {$translatedCountryName}",
            'banner' => $banner,
            'countriesBanner' => $countriesBanner,
            'trainsBanner' => $trainsBanner,
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

        $countriesBanner = $this->bannerModel->findByColumn('link', '/travel/trains/countries');
        $trainsBanner = $this->bannerModel->findByColumn('link', '/travel/trains');

        $banner = $this->bannerModel->findByColumn('link', '/travel/trains/countries/' . $countrySlug) ?? $city;

        if (isset($banner['group_key'])) {
            $banner['entity_type'] = 'banner';
        } else {
            $banner['entity_type'] = 'city';
        }

        if ($countriesBanner) $countriesBanner['entity_type'] = 'banner';
        if ($trainsBanner) $trainsBanner['entity_type'] = 'banner';

        $trains = $this->trainModel->where('city_id', $city['id']);
        foreach ($trains as &$train) {
            $train['entity_type'] = 'train';
        }

        $translatedCityName = HelperService::getTranslation($city, 'name');

        $this->render('travel/trains/countries/show-by-country/show-by-city/index', [
            'title' => HelperService::trans('train_stations_in') . " {$translatedCityName}",
            'banner' => $banner,
            'countriesBanner' => $countriesBanner,
            'trainsBanner' => $trainsBanner,
            'country' => $country,
            'city' => $city,
            'trains' => $trains
        ]);
    }

    public function index()
    {
        $this->checkAccess('admin');
        $paginationData = $this->paginate($this->trainModel);
        $trains = $this->trainModel->all([
            'limit' => $paginationData['limit'],
            'offset' => $paginationData['offset'],
            'order' => 'sort_order ASC'
        ]);

        $this->render('admin/trains/index', [
            'title' => 'Влакове',
            'trains' => $trains,
            'pagination' => $paginationData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $countryModel = new Country();
        $this->render('admin/trains/form', [
            'title' => 'Нова ЖП гара',
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'train' => null,
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->trainModel, $this->baseRoute, ['image_url'], 'trains');
    }

    public function edit(int $id)
    {
        $this->checkAccess('admin');
        $train = $this->trainModel->find($id);
        if (!$train) {
            $this->flash('error', 'ЖП гарата не е намерена.');
            $this->redirect($this->baseRoute);
        }

        $countryModel = new Country();
        $cityModel = new City();

        $this->render('admin/trains/form', [
            'title' => 'Редактиране на ЖП гара: ' . $train['name'],
            'train' => $train,
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'cities' => $cityModel->where('country_id', $train['country_id']),
            'layout'     => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->trainModel, $id, $this->baseRoute, ['image_url'], 'trains');
    }

    public function delete(int $id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->trainModel, $id, $this->baseRoute);
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->trainModel);
    }
}
