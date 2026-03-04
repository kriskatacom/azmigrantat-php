<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;
use App\Models\Banner;

class HomeController
{
    private Country $countryModel;
    private Banner $bannerModel;

    public function __construct()
    {
        $this->countryModel = new Country();
        $this->bannerModel = new Banner();
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

        View::render('index/travel/index', [
            'title'   => 'Пътуване',
            'banner'  => $mainBanner,
            'banners' => $items
        ]);
    }
}