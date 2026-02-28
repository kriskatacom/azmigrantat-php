<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Embassy;
use App\Models\Country;
use App\Core\View;

class EmbassyController extends BaseController
{
    private Embassy $embassyModel;

    public function __construct()
    {
        $this->middleware('admin', ['index']);

        $this->embassyModel = new Embassy();
    }

    public function index()
    {
        $pageData = $this->paginate($this->embassyModel);

        $embassies = $this->embassyModel->getAllWithCountries([
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC, name ASC'
        ]);

        View::render('admin/embassies/index', [
            'title'      => 'Посолства',
            'embassies'  => $embassies,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $countryModel = new Country();
        return View::render('admin/embassies/form', [
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'title' => 'Добавяне на посолство',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->handleStore($this->embassyModel, '/admin/embassies', ['image_url', 'logo', 'right_heading_image'], 'embassies');
    }

    public function edit($id)
    {
        $embassy = $this->embassyModel->find((int)$id);
        if (!$embassy) {
            header('Location: /admin/embassies?error=notfound');
            exit;
        }

        $countryModel = new Country();
        return View::render('admin/embassies/form', [
            'title' => 'Редактиране на посолство',
            'layout' => 'admin',
            'embassy'  => $embassy,
            'countries' => $countryModel->all(['order' => 'name ASC'])
        ]);
    }

    public function update($id)
    {
        $this->handleUpdate($this->embassyModel, (int)$id, '/admin/embassies', ['image_url', 'logo', 'right_heading_image'], 'embassies');
    }

    public function updateOrder()
    {
        return $this->handleOrderUpdate($this->embassyModel);
    }

    public function delete($id)
    {
        $this->handleDelete($this->embassyModel, (int)$id, null, ['logo', 'image_url', 'right_heading_image']);
    }
}
