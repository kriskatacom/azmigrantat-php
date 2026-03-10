<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Airport;
use App\Models\Banner;
use App\Models\Country;

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
        $countries = $this->countryModel->all();

        $this->render('travel/air-tickets/airports/index', [
            'title' => 'Европейски летища – информация и връзки към официални сайтове',
            'banner' => $banner,
            'airTicketsBanner' => $airTicketsBanner,
            'countries' => $countries
        ]);
    }

    public function showByCountry($countrySlug)
    {
        $banner = $this->bannerModel->findByColumn('link', '/travel/air-tickets/airports/' . $countrySlug);
        $airTicketsBanner = $this->bannerModel->findByColumn('link', '/travel/air-tickets');
        $airportsBanner = $this->bannerModel->findByColumn('link', '/travel/air-tickets/airports');
        $country = $this->countryModel->findByColumn('slug', $countrySlug);
        $airports = $this->airportModel->where('country_id', $country['id']);

        $this->render('travel/air-tickets/airports/show-by-country/index', [
            'title' => 'Европейски летища – информация и връзки към официални сайтове',
            'banner' => $banner ?? $airportsBanner,
            'airTicketsBanner' => $airTicketsBanner,
            'airportsBanner' => $airportsBanner,
            'country' => $country,
            'airports' => $airports
        ]);
    }

    public function index()
    {
        $this->checkAccess('admin');
        $pageData = $this->paginate($this->airportModel);

        $airports = $this->airportModel->getAllWithCountries([
            'order'  => 'sort_order ASC',
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset']
        ]);

        $this->render('admin/airports/index', [
            'title'      => 'Авиолинии',
            'airports'   => $airports,
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
        $this->handleStore($this->airportModel, '/admin/airports', ['image_url'], 'airports');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
        $airport = $this->airportModel->find((int)$id);
        if (!$airport) $this->redirect('/admin/airports');

        $this->render('admin/airports/form', [
            'airport'   => $airport,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'title'     => 'Редакция: ' . $airport['name'],
            'layout'    => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->airportModel, (int)$id, '/admin/airports', ['image_url'], 'airports');
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->airportModel, (int)$id, '/admin/airports', ['image_url']);
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->airportModel);
    }
}
