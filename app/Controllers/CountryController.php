<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;
use App\Services\FileService;
use App\Services\HelperService;

class CountryController extends BaseController
{
    private Country $countryModel;

    public function __construct()
    {
        $this->middleware('admin', ['index']);

        $this->countryModel = new Country();
    }

    public function index()
    {
        $pageData = $this->paginate($this->countryModel);

        $countries = $this->countryModel->all([
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC, name ASC'
        ]);

        View::render('admin/countries/index', [
            'title'      => 'Държави',
            'countries'  => $countries,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        View::render('admin/countries/form', ['title' => 'Добавяне на държава', 'layout' => 'admin']);
    }

    public function store()
    {
        $imageUrl = FileService::upload($_FILES['image_url']);

        if ($_FILES['image_url']['name'] && !$imageUrl) {
            $this->flash('error', 'Възникна грешка при качването на снимката.');
            header('Location: /admin/countries/create');
            exit;
        }

        $data = $_POST;
        $data['image_url'] = $imageUrl;
        $data['sort_order'] = ($this->countryModel->max('sort_order') ?? 0) + 1;
        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;

        if (empty($data['slug'])) {
            $data['slug'] = HelperService::slug($data['name']);
        } else {
            $data['slug'] = HelperService::slug($data['slug']);
        }

        unset($data['remove_image_url']);

        $newId = $this->countryModel->create($data);

        if ($newId) {
            $this->flash('success', 'Държавата "' . $data['name'] . '" беше създадена успешно!');
            header('Location: /admin/countries/edit/' . $newId);
        } else {
            $this->flash('error', 'Възникна грешка при записа в базата данни.');
            header('Location: /admin/countries');
        }
        exit;
    }

    public function edit($id)
    {
        $country = $this->countryModel->find($id);
        View::render('admin/countries/form', [
            'title' => 'Редактиране на ' . $country['name'],
            'country' => $country,
            'layout' => 'admin'
        ]);
    }

    public function update($id)
    {
        $country = $this->countryModel->find($id);
        $data = $_POST;

        if (empty($data['slug'])) {
            $data['slug'] = HelperService::slug($data['name']);
        } else {
            $data['slug'] = HelperService::slug($data['slug']);
        }

        $finalImageUrl = $country['image_url'];

        if (isset($data['remove_image_url']) && $data['remove_image_url'] == '1') {
            FileService::delete($country['image_url']);
            $finalImageUrl = null;
        }

        if (!empty($_FILES['image_url']['name'])) {
            FileService::delete($country['image_url']);
            $finalImageUrl = FileService::upload($_FILES['image_url']);
        }

        $data['image_url'] = $finalImageUrl;
        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;

        unset($data['remove_image_url']);

        if ($this->countryModel->update($id, $data)) {
            $this->flash('success', 'Промените бяха запазени!');
        }

        header('Location: /admin/countries/edit/' . $id);
        exit;
    }

    public function show(int $id)
    {
        $country = $this->countryModel->find($id);

        if (!$country) {
            $this->json(['message' => 'Country not found'], 404);
        }

        $this->json($country);
    }

    public function updateOrder()
    {
        return $this->handleOrderUpdate($this->countryModel);
    }

    public function delete($id)
    {
        $this->handleDelete($this->countryModel, (int)$id, null, ['image_url']);
    }
}
