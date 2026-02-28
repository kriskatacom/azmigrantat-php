<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Landmark;
use App\Models\Country;
use App\Core\View;

class LandmarkController extends BaseController
{
    private Landmark $landmarkModel;

    public function __construct()
    {
        $this->middleware('admin', ['index']);

        $this->landmarkModel = new Landmark();
    }

    public function index()
    {
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
        $countryModel = new Country();
        return View::render('admin/landmarks/form', [
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'title' => 'Добавяне на забележителност',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $_POST['additional_images'] = $this->handleGalleryUpdate(['additional_images' => '[]'], $_POST, 'additional_images', 'landmarks/gallery');

        $this->handleStore($this->landmarkModel, '/admin/landmarks', ['image_url'], 'landmarks');
    }

    public function edit($id)
    {
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
        $landmark = $this->landmarkModel->find((int)$id);
        if (!$landmark) $this->redirect('/admin/landmarks');

        $_POST['additional_images'] = $this->handleGalleryUpdate($landmark, $_POST, 'additional_images', 'landmarks/gallery');

        $this->handleUpdate($this->landmarkModel, (int)$id, '/admin/landmarks', ['image_url'], 'images');
    }

    public function updateOrder()
    {
        return $this->handleOrderUpdate($this->landmarkModel);
    }

    public function delete($id)
    {
        $this->handleDelete($this->landmarkModel, (int)$id, null, ['image_url'], ['additional_images']);
    }
}
