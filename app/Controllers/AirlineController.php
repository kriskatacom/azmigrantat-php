<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Airline;
use App\Models\Banner;
use App\Models\Country;
use App\Models\Category;
use App\Services\HelperService;
use App\Services\MetaTagsService;

class AirlineController extends BaseController
{
    private Airline $airlineModel;
    private Banner $bannerModel;

    public function __construct()
    {
        $this->airlineModel = new Airline();
        $this->bannerModel = new Banner();
    }

    public function all()
    {
        $banner = $this->bannerModel->findByColumn('link', '/travel/air-tickets/airlines');
        $airTicketsBanner = $this->bannerModel->findByColumn('link', '/travel/air-tickets');
        $airlinesBanner = $this->bannerModel->findByColumn('link', '/travel/air-tickets/airlines');

        $airlines = $this->airlineModel->all(['order' => 'sort_order ASC']);

        if ($banner) $banner['entity_type'] = 'banner';
        if ($airTicketsBanner) $airTicketsBanner['entity_type'] = 'banner';
        if ($airlinesBanner) $airlinesBanner['entity_type'] = 'banner';

        foreach ($airlines as &$airline) {
            $airline['entity_type'] = 'airline';
        }

        $displayBanner = $banner ?? $airlinesBanner;

        $this->render('travel/air-tickets/airlines/index', [
            'banner' => $displayBanner,
            'airTicketsBanner' => $airTicketsBanner,
            'airlinesBanner' => $airlinesBanner,
            'airlines' => $airlines,
            'layout'   => 'secondary',
            'seo' => new MetaTagsService([
                'title'       => HelperService::trans($banner['name']),
                'description' => HelperService::trans($banner['description'] ?? ''),
            ])
        ]);
    }

    public function index()
    {
        $this->checkAccess('admin');

        $filters = $this->getFilters();

        $searchColumns = ['name'];

        $pageData = $this->paginate($this->airlineModel, $filters, $searchColumns);

        $airlines = $this->airlineModel->getFiltered(array_merge($filters, [
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC'
        ]), $searchColumns);

        $this->render('admin/airlines/index', [
            'title'      => 'Авиолинии',
            'airlines'   => $airlines,
            'filters'    => $filters,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $this->render('admin/airlines/form', [
            'title'      => 'Нова авиокомпания',
            'countries'  => (new Country())->all(['order' => 'name ASC']),
            'categories' => (new Category())->all(['order' => 'name ASC']),
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $fileFields = ['image_url', 'image_tablet_url', 'image_mobile_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleStore($this->airlineModel, '/admin/airlines', $fileFields, 'airlines');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
        $airline = $this->airlineModel->find((int)$id);

        if (!$airline) {
            $this->flash('error', 'Авиокомпанията не е намерена.');
            $this->redirect('/admin/airlines');
        }

        // Зареждаме преводите за админ формата (за AI модала)
        $airline['translations'] = $this->getMappedTranslations('airline', $id);

        $this->render('admin/airlines/form', [
            'title'      => 'Редакция: ' . $airline['name'],
            'airline'    => $airline,
            'categories' => (new Category())->all(['order' => 'name ASC']),
            'languages'  => HelperService::AVAILABLE_LANGUAGES,
            'layout'     => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $fileFields = ['image_url', 'image_tablet_url', 'image_mobile_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleUpdate($this->airlineModel, (int)$id, '/admin/airlines', $fileFields, 'airlines');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->airlineModel);
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $fileFields = ['image_url', 'image_tablet_url', 'image_mobile_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleDelete($this->airlineModel, (int)$id, null, $fileFields);
    }
}
