<?php

namespace App\Controllers;

use App\Models\Company;
use App\Models\Country;
use App\Models\City;
use App\Models\Category;

class CompanyController extends BaseController
{
    private Company $companyModel;
    private Category $categoryModel;

    public function __construct()
    {
        $this->middleware('admin');
        $this->companyModel = new Company();
        $this->categoryModel = new Category();
    }

    public function index()
    {
        $pageData = $this->paginate($this->companyModel, 10);

        $companies = $this->companyModel->getAllWithRelations(
            $pageData['limit'],
            $pageData['offset']
        );

        $this->render('admin/companies/index', [
            'title'      => 'Компании',
            'companies'  => $companies,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $categoriesTree = $this->categoryModel->getTree();

        $this->render('admin/companies/form', [
            'title'      => 'Нова компания',
            'countries'  => (new Country())->all(['order' => 'name ASC']),
            'categories' => $categoriesTree,
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleStore($this->companyModel, '/admin/companies', $fileFields, 'companies');
    }

    public function edit($id)
    {
        $company = $this->companyModel->find((int)$id);

        if (!$company) {
            $this->flash('error', 'Компанията не е намерена.');
            $this->redirect('/admin/companies');
        }

        $this->render('admin/companies/form', [
            'title'      => 'Редакция: ' . $company['name'],
            'company'    => $company,
            'countries'  => (new Country())->all(['order' => 'name ASC']),
            'cities'     => (new City())->where('country_id', $company['country_id']),
            'categories' => (new Category())->all(['order' => 'name ASC']),
            'layout'     => 'admin'
        ]);
    }

    public function update($id)
    {
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleUpdate($this->companyModel, (int)$id, '/admin/companies', $fileFields, 'companies');
    }

    public function updateOrder()
    {
        $this->handleOrderUpdate($this->companyModel);
    }

    public function delete($id)
    {
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleDelete($this->companyModel, (int)$id, null, $fileFields);
    }
}