<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Landmark;
use App\Models\Country;
use App\Core\View;
use App\Models\CountryElement;

class LandmarkController extends BaseController
{
    private Landmark $landmarkModel;

    public function __construct()
    {
        $this->landmarkModel = new Landmark();
    }

    // public routes

    public function indexByCountry($countrySlug)
    {
        $countryModel = new Country();
        $landmarkModel = new Landmark();
        $elementModel = new CountryElement();

        $country = $countryModel->where('slug', $countrySlug)[0] ?? null;

        if (!$country) {
            header("HTTP/1.0 404 Not Found");
            exit('Държавата не е намерена.');
        }

        $landmarkElement = $elementModel->all([
            'where' => [
                'country_id' => $country['id'],
                'slug'       => 'landmarks',
                'is_active'  => 1
            ]
        ])[0] ?? null;

        $landmarks = $landmarkModel->all([
            'where' => [
                'country_id' => $country['id'],
                'is_active'  => 1
            ],
            'order' => 'sort_order ASC'
        ]);

        View::render('landmarks/index', [
            'title'          => 'Забележителности в ' . $country['name'],
            'country'        => $country,
            'landmarkElement' => $landmarkElement,
            'landmarks'      => $landmarks
        ]);
    }

    // admin routes

    public function index()
    {
        $this->checkAccess('admin');
        $pageData = $this->paginate($this->landmarkModel);

        $landmarks = $this->landmarkModel->getAllWithCountries([
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC, name ASC'
        ]);

        View::render('admin/landmarks/index', [
            'title'      => 'Забележителности',
            'landmarks'  => $landmarks,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $countryModel = new Country();
        return View::render('admin/landmarks/form', [
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'title' => 'Добавяне на забележителност',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $_POST['additional_images'] = $this->handleGalleryUpdate(['additional_images' => '[]'], $_POST, 'additional_images', 'landmarks/gallery');

        $this->handleStore($this->landmarkModel, '/admin/landmarks', ['image_url'], 'landmarks');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
        $landmark = $this->landmarkModel->find((int)$id);

        if (!$landmark) {
            header('Location: /admin/landmarks?error=notfound');
            exit;
        }

        $countryModel = new Country();
        return View::render('admin/landmarks/form', [
            'title' => 'Редактиране на забележителност',
            'layout' => 'admin',
            'landmark'  => $landmark,
            'countries' => $countryModel->all(['order' => 'name ASC'])
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $landmark = $this->landmarkModel->find((int)$id);
        if (!$landmark) $this->redirect('/admin/landmarks');

        $_POST['additional_images'] = $this->handleGalleryUpdate($landmark, $_POST, 'additional_images', 'landmarks/gallery');

        $this->handleUpdate($this->landmarkModel, (int)$id, '/admin/landmarks', ['image_url'], 'images');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->landmarkModel);
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->landmarkModel, (int)$id, null, ['image_url'], ['additional_images']);
    }
}