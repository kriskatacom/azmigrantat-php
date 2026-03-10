<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Airline;
use App\Models\Banner;
use App\Models\Country;
use App\Models\Category;

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
        $airlines = $this->airlineModel->all();

        $this->render('travel/air-tickets/airlines/index', [
            'title' => 'Европейски авиокомпании – информация и връзки към официални сайтове',
            'banner' => $banner ?? $airlinesBanner,
            'airTicketsBanner' => $airTicketsBanner,
            'airlinesBanner' => $airlinesBanner,
            'airlines' => $airlines
        ]);
    }

    public function index()
    {
        $this->checkAccess('admin');
        $pageData = $this->paginate($this->airlineModel);

        $airlines = $this->airlineModel->all([
            'order'  => 'sort_order ASC',
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset']
        ]);

        $this->render('admin/airlines/index', [
            'title'      => 'Компании',
            'airlines'  => $airlines,
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
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
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

        $this->render('admin/airlines/form', [
            'title'      => 'Редакция: ' . $airline['name'],
            'airline'    => $airline,
            'categories' => (new Category())->all(['order' => 'name ASC']),
            'layout'     => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
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
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleDelete($this->airlineModel, (int)$id, null, $fileFields);
    }
}