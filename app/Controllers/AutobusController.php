<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Autobus;
use App\Models\City;
use App\Models\Country;
use App\Services\FileService;

class AutobusController extends BaseController
{
    private Autobus $autobusModel;
    private Country $countryModel;
    private City $cityModel;

    public function __construct()
    {
        $this->middleware('admin', ['index']);

        $this->autobusModel = new Autobus();
        $this->countryModel = new Country();
        $this->cityModel = new City();
    }

    public function index()
    {
        $pageData = $this->paginate($this->autobusModel);

        $autobuses = $this->autobusModel->getWithRelations(
            $pageData['limit'],
            $pageData['offset']
        );

        View::render('admin/autobuses/index', [
            'title'      => 'Автобусни компании',
            'autobuses'  => $autobuses,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $countries = $this->countryModel->all();

        View::render('admin/autobuses/form', [
            'title'     => 'Нова автобусна компания',
            'countries' => $countries,
            'layout'    => 'admin'
        ]);
    }

    public function edit($id)
    {
        $autobus = $this->autobusModel->find($id);

        if (!$autobus) {
            header('Location: /admin/cities');
            exit;
        }

        $countries = $this->countryModel->all();
        $cities = $this->cityModel->getByCountry($autobus['country_id']);

        View::render('admin/autobuses/form', [
            'title'     => 'Редактиране на автобусна компания: ' . $autobus['name'],
            'autobus'       => $autobus,
            'countries' => $countries,
            'cities'    => $cities,
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $data = $_POST;
        $data = $this->autobusModel->prepareData($data);

        if (!empty($_FILES['image_url']['name'])) {
            $data['image_url'] = FileService::upload($_FILES['image_url'], 'autobuses');
        }

        unset($data['remove_image_url']);

        if ($this->autobusModel->create($data)) {
            header('Location: /admin/autobuses?success=1');
            exit;
        }
    }

    public function update($id)
    {
        $autobus = $this->autobusModel->find($id);
        if (!$autobus) return;

        $data = $_POST;
        $data = $this->autobusModel->prepareData($data);

        $finalImageUrl = $autobus['image_url'];

        if (isset($data['remove_image_url']) && $data['remove_image_url'] == '1') {
            FileService::delete($autobus['image_url']);
            $finalImageUrl = null;
        }

        if (!empty($_FILES['image_url']['name'])) {
            FileService::delete($autobus['image_url']);
            $finalImageUrl = FileService::upload($_FILES['image_url']);
        }

        $data['image_url'] = $finalImageUrl;
        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;

        unset($data['remove_image_url']);

        if ($this->autobusModel->update($id, $data)) {
            header('Location: /admin/autobuses?success=2');
            exit;
        }
    }

    public function delete($id)
    {
        $this->handleDelete($this->autobusModel, (int)$id, null, ['image_url']);
    }
}