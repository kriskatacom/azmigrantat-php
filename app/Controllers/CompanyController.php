<?php

namespace App\Controllers;

use App\Models\Company;
use App\Models\Country;
use App\Models\City;
use App\Models\Category;
use App\Models\User;
use App\Services\HelperService;

class CompanyController extends BaseController
{
    private Country $countryModel;
    private Company $companyModel;
    private Category $categoryModel;
    private User $userModel;

    public function __construct()
    {
        $this->countryModel = new Country();
        $this->companyModel = new Company();
        $this->categoryModel = new Category();
        $this->userModel = new User();
    }

    public function index()
    {
        $this->checkAccess('admin');
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
        $this->checkAccess('admin');

        $this->render('admin/companies/form', [
            'title'      => 'Нова компания',
            'countries'  => $this->countryModel->all(['order' => 'name ASC']),
            'categories' => $this->categoryModel->getTree(),
            'users' => $this->userModel->all(),
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];

        $_POST['additional_images'] = $this->handleGalleryUpdate(['additional_images' => '[]'], $_POST, 'additional_images', 'landmarks/gallery');

        $this->handleStore($this->companyModel, '/admin/companies', $fileFields, 'companies');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');

        $company = $this->companyModel->find($id);
        $users = $this->userModel->all();

        if (!$company) {
            $this->flash('error', 'Записът не е намерен.');
            $this->redirect('/admin/companies');
        }

        $company['translations'] = $this->getMappedTranslations('company', $id);

        $nextId = $this->companyModel->getNextId($id);
        $prevId = $this->companyModel->getPrevId($id);

        $this->render('admin/companies/form', [
            'title'        => 'Редактиране на ' . $company['name'],
            'company'      => $company,
            'countries'  => (new Country())->all(['order' => 'name ASC']),
            'cities'     => (new City())->where('country_id', $company['country_id']),
            'categories' => $this->categoryModel->getTree(),
            'users' => $users,
            'nextId'       => $nextId,
            'prevId'       => $prevId,
            'languages'    => HelperService::AVAILABLE_LANGUAGES,
            'layout'       => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];

        $company = $this->companyModel->find((int)$id);
        if (!$company) $this->redirect('/admin/companies');

        $_POST['additional_images'] = $this->handleGalleryUpdate($company, $_POST, 'additional_images', 'companies/gallery');

        $this->handleUpdate($this->companyModel, (int)$id, '/admin/companies', $fileFields, 'companies');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->companyModel);
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $fileFields = ['image_url', 'offer_image_url', 'ads_image_url', 'bottom_image_url'];
        $this->handleDelete($this->companyModel, (int)$id, null, $fileFields, ['additional_images']);
    }
}