<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Core\View;
use App\Models\Airport;
use App\Models\Banner;
use App\Models\Country;
use App\Services\HelperService;
use App\Services\MetaTagsService;

class AirportController extends BaseController
{
    protected Airport $airportModel;
    protected Country $countryModel;
    protected Banner $bannerModel;

    public function __construct()
    {
        $this->airportModel = new Airport();
        $this->countryModel = new Country();
        $this->bannerModel = new Banner();
    }

    public function showCountries()
    {
        $banner = $this->bannerModel->findByColumn('link', '/travel/air-tickets/airports');
        $airTicketsBanner = $this->bannerModel->findByColumn('link', '/travel/air-tickets');
        $countries = $this->countryModel->all(['order' => 'name ASC']);

        if ($banner) $banner['entity_type'] = 'banner';
        if ($airTicketsBanner) $airTicketsBanner['entity_type'] = 'banner';

        foreach ($countries as &$country) {
            $country['entity_type'] = 'country';
        }

        $this->render('travel/air-tickets/airports/index', [
            'banner' => $banner,
            'airTicketsBanner' => $airTicketsBanner,
            'countries' => $countries,
            'layout'   => 'secondary',
            'seo' => new MetaTagsService([
                'title'       => HelperService::trans($banner['name']),
                'description' => HelperService::trans($banner['description'] ?? ''),
            ])
        ]);
    }

    public function showByCountry($countrySlug)
    {
        $banner = $this->bannerModel->findByColumn('link', '/travel/air-tickets/airports/' . $countrySlug);
        $airTicketsBanner = $this->bannerModel->findByColumn('link', '/travel/air-tickets');
        $airportsBanner = $this->bannerModel->findByColumn('link', '/travel/air-tickets/airports');

        $country = $this->countryModel->findByColumn('slug', $countrySlug);
        if (!$country) return $this->abort404('Държавата не е намерена.');

        $country['entity_type'] = 'country';

        $airports = $this->airportModel->where('country_id', $country['id']);
        foreach ($airports as &$airport) {
            $airport['entity_type'] = 'airport';
        }

        if ($banner) $banner['entity_type'] = 'banner';
        if ($airTicketsBanner) $airTicketsBanner['entity_type'] = 'banner';
        if ($airportsBanner) $airportsBanner['entity_type'] = 'banner';

        $displayBanner = $banner ?? $airportsBanner;

        $this->render('travel/air-tickets/airports/show-by-country/index', [
            'banner' => $displayBanner,
            'airTicketsBanner' => $airTicketsBanner,
            'airportsBanner' => $airportsBanner,
            'country' => $country,
            'airports' => $airports,
            'layout'   => 'secondary',
            'seo' => new MetaTagsService([
                'title'       => HelperService::trans($banner['name'] ?? ''),
                'description' => HelperService::trans($banner['description'] ?? ''),
            ])
        ]);
    }

    public function index()
    {
        $this->checkAccess('admin');

        $filters = $this->getFilters();

        $searchColumns = ['name', 'description'];

        $pageData = $this->paginate($this->airportModel, $filters, $searchColumns);

        $airports = $this->airportModel->getAllWithCountries(array_merge($filters, [
            'order'  => 'sort_order ASC',
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset']
        ]), $searchColumns);

        $this->render('admin/airports/index', [
            'title'      => 'Летища',
            'airports'   => $airports,
            'filters'    => $filters,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $this->render('admin/airports/form', [
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'title'     => 'Добавяне на летище',
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->airportModel, '/admin/airports', ['image_url', 'image_tablet_url', 'image_mobile_url'], 'airports');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');

        $airport = $this->airportModel->find($id);
        if (!$airport) {
            $this->flash('error', 'Записът не е намерен.');
            $this->redirect('/admin/airport');
        }

        $airport['translations'] = $this->getMappedTranslations('airport', $id);

        $nextId = $this->airportModel->getNextId($id);
        $prevId = $this->airportModel->getPrevId($id);

        View::render('admin/airports/form', [
            'title'        => 'Редактиране на ' . $airport['name'],
            'airport'      => $airport,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'nextId'       => $nextId,
            'prevId'       => $prevId,
            'languages'    => HelperService::AVAILABLE_LANGUAGES,
            'layout'       => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->airportModel, (int)$id, '/admin/airports', ['image_url', 'image_tablet_url', 'image_mobile_url'], 'airports');
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->airportModel, (int)$id, '/admin/airports', ['image_url', 'image_tablet_url', 'image_mobile_url']);
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->airportModel);
    }
}
