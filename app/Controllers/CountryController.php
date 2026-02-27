<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;

class CountryController extends BaseController
{
    private Country $countryModel;

    public function __construct()
    {
        $this->countryModel = new Country();
    }

    public function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 10);
        $offset = ($page - 1) * $perPage;

        $countries = $this->countryModel->all([
            'limit' => $perPage,
            'offset' => $offset,
            'order' => 'name ASC'
        ]);

        $total = $this->countryModel->count();

        View::render('admin/countries/index', [
            'title' => 'Държави',
            'countries' => $countries,
            'layout' => 'admin',
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $perPage),
                'per_page' => $perPage,
            ],
        ]);
    }

    public function store()
    {
        $data = [
            'name'       => $_POST['name'],
            'slug'       => $_POST['slug'] ?? strtolower(str_replace(' ', '-', $_POST['name'])),
            'heading'    => $_POST['heading'],
            'excerpt'    => $_POST['excerpt'],
            'image_url'  => $_POST['image_url'] ?? null,
            'sort_order' => (int)($_POST['sort_order'] ?? 0)
        ];

        $success = $this->countryModel->create($data);

        if ($success) {
            $this->json(['message' => 'Country created successfully'], 201);
        } else {
            $this->json(['message' => 'Failed to create country'], 400);
        }
    }

    public function show(int $id)
    {
        $country = $this->countryModel->find($id);

        if (!$country) {
            $this->json(['message' => 'Country not found'], 404);
        }

        $this->json($country);
    }

    public function update(int $id)
    {
        $data = $_POST;

        $success = $this->countryModel->update($id, $data);

        if ($success) {
            $this->json(['message' => 'Country updated successfully']);
        } else {
            $this->json(['message' => 'Update failed'], 400);
        }
    }

    public function destroy(int $id)
    {
        $success = $this->countryModel->delete($id);

        if ($success) {
            $this->json(['message' => 'Country deleted successfully']);
        } else {
            $this->json(['message' => 'Delete failed'], 400);
        }
    }
}