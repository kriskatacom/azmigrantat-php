<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Airport;
use App\Models\Banner;

class TravelController extends BaseController
{
    private Airport $airportModel;
    private Banner $bannerModel;

    public function __construct()
    {
        $this->airportModel = new Airport();
        $this->bannerModel = new Banner();
    }

    public function airTickets()
    {
        $banner = $this->bannerModel->where('link', '/travel/air-tickets')[0];
        $banners = $this->bannerModel->where('group_key', 'AIR_TICKETS_ELEMENTS', 'sort_order ASC');
        $airports = $this->airportModel->all();

        $items = [];
        foreach ($banners as $b) {
            $items[] = [
                'name'        => $b['name'],
                'slug'        => $b['link'],
                'description' => $b['description'],
                'image_url'   => $b['image_url'],
                'button_text' => $b['button_text'] ?? null,
                'show_name'   => $b['show_name']
            ];
        }

        $this->render('travel/air-tickets/index', [
            'title' => 'Европейски летища – информация и връзки към официални сайтове',
            'banners' => $items,
            'banner' => $banner,
            'airports' => $airports
        ]);
    }

    public function autobuses()
    {
        $banner = $this->bannerModel->where('link', '/travel/autobuses')[0];
        $banners = $this->bannerModel->where('group_key', 'AUTOBUSES_ELEMENTS', 'sort_order ASC');
        $airports = $this->airportModel->all();

        $items = [];
        foreach ($banners as $b) {
            $items[] = [
                'name'        => $b['name'],
                'slug'        => $b['link'],
                'description' => $b['description'],
                'image_url'   => $b['image_url'],
                'button_text' => $b['button_text'] ?? null,
                'show_name'   => $b['show_name']
            ];
        }

        $this->render('travel/autobuses/index', [
            'title' => 'Автобусни гари и автобусни превози в Европа – информация и адреси',
            'banners' => $items,
            'banner' => $banner,
            'airports' => $airports
        ]);
    }

    public function trains()
    {
        $banner = $this->bannerModel->where('link', '/travel/trains')[0];
        $banners = $this->bannerModel->where('group_key', 'TRAINS_ELEMENTS', 'sort_order ASC');

        $items = [];
        foreach ($banners as $b) {
            $items[] = [
                'name'        => $b['name'],
                'slug'        => $b['link'],
                'description' => $b['description'],
                'image_url'   => $b['image_url'],
                'button_text' => $b['button_text'] ?? null,
                'show_name'   => $b['show_name']
            ];
        }

        $this->render('travel/autobuses/index', [
            'title' => 'Жележопътни гари и жележопътни превози в Европа – информация и адреси',
            'banners' => $items,
            'banner' => $banner,
        ]);
    }
}
