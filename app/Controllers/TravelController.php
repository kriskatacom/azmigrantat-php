<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Airport;
use App\Models\Banner;
use App\Services\HelperService;
use App\Services\MetaTagsService;

class TravelController extends BaseController
{
    private Airport $airportModel;
    private Banner $bannerModel;

    public function __construct()
    {
        $this->airportModel = new Airport();
        $this->bannerModel = new Banner();
    }

    private function prepareBanners(array $banners): array
    {
        $items = [];
        foreach ($banners as $b) {
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
        return $items;
    }

    public function airTickets()
    {
        $banner = $this->bannerModel->where('link', '/travel/air-tickets')[0] ?? null;
        if ($banner) $banner['entity_type'] = 'banner';

        $banners = $this->bannerModel->where('group_key', 'AIR_TICKETS_ELEMENTS', 'sort_order ASC');

        $airports = $this->airportModel->all();
        foreach ($airports as &$airport) {
            $airport['entity_type'] = 'airport';
        }

        $seo = new MetaTagsService([
            'title'       => HelperService::trans($banner['name']),
            'description' => HelperService::trans($banner['description']),
        ]);

        $this->render('travel/air-tickets/index', [
            'title' => HelperService::trans($banner['name']),
            'banners' => $this->prepareBanners($banners),
            'banner' => $banner,
            'airports' => $airports,
            'layout'   => 'secondary',
        ]);
    }

    public function autobuses()
    {
        $banner = $this->bannerModel->where('link', '/travel/autobuses')[0] ?? null;
        if ($banner) $banner['entity_type'] = 'banner';

        $banners = $this->bannerModel->where('group_key', 'AUTOBUSES_ELEMENTS', 'sort_order ASC');

        $seo = new MetaTagsService([
            'title'       => HelperService::trans($banner['name']),
            'description' => HelperService::trans($banner['description']),
        ]);

        $this->render('travel/autobuses/index', [
            'title' => HelperService::trans($banner['name']),
            'banners' => $this->prepareBanners($banners),
            'banner' => $banner,
            'seo' => $seo,
            'layout'   => 'secondary',
        ]);
    }

    public function trains()
    {
        $banner = $this->bannerModel->where('link', '/travel/trains')[0] ?? null;
        if ($banner) $banner['entity_type'] = 'banner';

        $banners = $this->bannerModel->where('group_key', 'TRAINS_ELEMENTS', 'sort_order ASC');

        $seo = new MetaTagsService([
            'title'       => HelperService::trans($banner['name']),
            'description' => HelperService::trans($banner['description']),
        ]);

        $this->render('travel/autobuses/index', [
            'title' => HelperService::trans($banner['name']),
            'banners' => $this->prepareBanners($banners),
            'banner' => $banner,
            'seo' => $seo,
            'layout'   => 'secondary',
        ]);
    }

    public function taxis()
    {
        $banner = $this->bannerModel->where('link', '/travel/taxis')[0] ?? null;
        if ($banner) $banner['entity_type'] = 'banner';

        $banners = $this->bannerModel->where('group_key', 'TAXIS_ELEMENTS', 'sort_order ASC');

        $seo = new MetaTagsService([
            'title'       => HelperService::trans($banner['name']),
            'description' => HelperService::trans($banner['description']),
        ]);

        $this->render('travel/taxis/index', [
            'title' => HelperService::trans($banner['name']),
            'banners' => $this->prepareBanners($banners),
            'banner' => $banner,
            'seo' => $seo,
            'layout'   => 'secondary',
        ]);
    }

    public function cruises()
    {
        $banner = $this->bannerModel->where('link', '/travel/cruises')[0] ?? null;
        if ($banner) $banner['entity_type'] = 'banner';

        $banners = $this->bannerModel->where('group_key', 'CRUISES_ELEMENTS', 'sort_order ASC');

        $seo = new MetaTagsService([
            'title'       => HelperService::trans($banner['name']),
            'description' => HelperService::trans($banner['description']),
        ]);

        $this->render('travel/cruises/index', [
            'title' => HelperService::trans($banner['name']),
            'banners' => $this->prepareBanners($banners),
            'banner' => $banner,
            'seo' => $seo,
            'layout'   => 'secondary',
        ]);
    }
}