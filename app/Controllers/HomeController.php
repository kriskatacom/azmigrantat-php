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

        View::render('index/home/index', [
            'title' => 'Начало',
            'countries' => $countries
        ]);
    }

    public function travel()
    {
        $mainBanner = $this->bannerModel->where('link', '/travel')[0] ?? null;

        $travelBanners = $this->bannerModel->where('group_key', 'TRAVEL_ELEMENTS', 'sort_order ASC');

        $items = [];
        foreach ($travelBanners as $b) {
            $items[] = [
                'name'        => $b['name'],
                'slug'        => $b['link'],
                'description' => $b['description'],
                'image_url'   => $b['image_url'],
                'button_text' => $b['button_text'] ?? null,
                'show_name'   => $b['show_name']
            ];
        }

        View::render('travel/index', [
            'title'   => 'Пътуване',
            'banner'  => $mainBanner,
            'banners' => $items
        ]);
    }

    public function sharedTravel()
    {
        $mainBanner = $this->bannerModel->where('link', '/travel/shared-travel')[0] ?? null;

        $breadcrumbs = [
            [
                'label' => HelperService::trans('travel') ?? 'Пътуване',
                'href'  => '/travel'
            ],
            [
                'label' => $mainBanner['name'] ?? 'Споделено пътуване'
            ],
        ];

        $allCities = $this->cityModel->all();

        $drivers = $this->driverModel->getActiveDriversWithUsers();

        $citiesJson = json_encode($allCities, JSON_UNESCAPED_UNICODE);

        View::render('travel/shared-travel/index', [
            'title'       => $mainBanner['name'] ?? 'Споделено пътуване',
            'banner'      => $mainBanner,
            'drivers'     => $drivers,
            'citiesJson'  => $citiesJson,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}