<?php

namespace App\Controllers;

use App\Models\Cruise;
use App\Core\View;
use App\Models\Banner;
use App\Services\HelperService;

class CruiseController extends BaseController
{
    private Cruise $cruiseModel;
    private Banner $bannerModel;

    public function __construct()
    {
        $this->cruiseModel = new Cruise();
        $this->bannerModel = new Banner();
    }

    public function show()
    {
        $banner = $this->bannerModel->findByColumn('link', '/travel/cruises/cruise-companies');
        $cruisesBanner = $this->bannerModel->findByColumn('link', '/travel/cruises');

        $cruises = $this->cruiseModel->all(['order' => 'sort_order ASC, name ASC']);

        if ($banner) $banner['entity_type'] = 'banner';
        if ($cruisesBanner) $cruisesBanner['entity_type'] = 'banner';

        foreach ($cruises as &$cruise) {
            $cruise['entity_type'] = 'cruise';
        }

        $this->render('travel/cruises/cruise-companies/index', [
            'title' => HelperService::trans('cruise_companies_title') ?? 'Круизни компании – информация и връзки към официални сайтове',
            'banner' => $banner,
            'cruisesBanner' => $cruisesBanner,
            'cruises' => $cruises,
        ]);
    }

    public function index()
    {
        $this->checkAccess('admin');
        $pageData = $this->paginate($this->cruiseModel);

        $cruises = $this->cruiseModel->all([
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC, name ASC'
        ]);

        View::render('admin/cruises/index', [
            'title'      => 'Круизни компании',
            'cruises'    => $cruises,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        return View::render('admin/cruises/form', [
            'title'  => 'Добавяне на круизна компания',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->cruiseModel, '/admin/cruises', ['image_url'], 'cruises');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
        $cruise = $this->cruiseModel->find((int)$id);
        
        if (!$cruise) {
            $this->flash('error', 'Круизната компания не е намерена.');
            $this->redirect('/admin/cruises');
        }

        $cruise['translations'] = $this->getMappedTranslations('cruise', $id);

        return View::render('admin/cruises/form', [
            'title'     => 'Редактиране: ' . $cruise['name'],
            'cruise'    => $cruise,
            'languages' => HelperService::AVAILABLE_LANGUAGES,
            'layout'    => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->cruiseModel, (int)$id, '/admin/cruises', ['image_url'], 'cruises');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->cruiseModel);
    }

    public function delete(int $id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->cruiseModel, (int)$id, null, ['image_url']);
    }
}