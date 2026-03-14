<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;
use App\Models\Banner;
use App\Models\City;
use App\Models\Driver;
use App\Services\HelperService;

class HomeController
{
    private Country $countryModel;
    private Banner $bannerModel;
    private City $cityModel;
    private Driver $driverModel;

    public function __construct()
    {
        $this->countryModel = new Country();
        $this->bannerModel = new Banner();
        $this->cityModel = new City();
        $this->driverModel = new Driver();
    }

    public function index()
    {
        $countries = $this->countryModel->getAllSorted();
        
        foreach ($countries as &$country) {
            $country['entity_type'] = 'country';
        }

        $banners = $this->bannerModel->where('group_key', 'HOME_ELEMENTS', 'sort_order ASC');
        foreach ($banners as &$b) {
            $b['entity_type'] = 'banner';
        }

        View::render('index/home/index', [
            'title'     => HelperService::trans('home'),
            'countries' => $countries,
            'banners'   => $banners,
        ]);
    }

    public function travel()
    {
        $mainBanner = $this->bannerModel->where('link', '/travel')[0] ?? null;
        if ($mainBanner) $mainBanner['entity_type'] = 'banner';

        $travelBanners = $this->bannerModel->where('group_key', 'TRAVEL_ELEMENTS', 'sort_order ASC');

        $items = [];
        foreach ($travelBanners as $b) {
            $b['entity_type'] = 'banner';
            $items[] = [
                'name'        => HelperService::getTranslation($b, 'name', 'banner'),
                'slug'        => $b['link'],
                'description' => HelperService::getTranslation($b, 'description', 'banner'),
                'image_url'   => $b['image_url'],
                'button_text' => HelperService::getTranslation($b, 'button_text', 'banner'),
                'show_name'   => $b['show_name'],
                'entity_type' => 'banner'
            ];
        }

        View::render('travel/index', [
            'title'   => HelperService::trans('travel'),
            'banner'  => $mainBanner,
            'banners' => $items
        ]);
    }

    public function sharedTravel()
    {
        $mainBanner = $this->bannerModel->where('link', '/travel/shared-travel')[0] ?? null;
        if ($mainBanner) $mainBanner['entity_type'] = 'banner';

        $bannerTitle = $mainBanner ? HelperService::getTranslation($mainBanner, 'name', 'banner') : HelperService::trans('shared_travel');

        $breadcrumbs = [
            [
                'label' => HelperService::trans('travel') ?? HelperService::trans('home'),
                'href'  => '/travel'
            ],
            [
                'label' => $bannerTitle
            ],
        ];

        $allCities = $this->cityModel->all();
        foreach ($allCities as &$city) {
            $city['entity_type'] = 'city';
            $city['name'] = HelperService::getTranslation($city, 'name', 'city');
        }

        $drivers = $this->driverModel->getActiveDriversWithUsers();
        foreach ($drivers as &$driver) {
            $driver['entity_type'] = 'driver';
        }

        View::render('travel/shared-travel/index', [
            'title'       => $bannerTitle,
            'banner'      => $mainBanner,
            'drivers'     => $drivers,
            'citiesJson'  => json_encode($allCities, JSON_UNESCAPED_UNICODE),
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
