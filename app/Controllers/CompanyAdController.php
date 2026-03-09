<?php

namespace App\Controllers;

use App\Models\CompanyAd;
use App\Models\Company;
use App\Models\User;

class CompanyAdController extends BaseController
{
    private CompanyAd $adModel;
    private Company $companyModel;
    private User $userModel;

    public function __construct()
    {
        $this->adModel = new CompanyAd();
        $this->companyModel = new Company();
        $this->userModel = new User();
    }

    public function index()
    {
        $this->checkAccess(['admin', 'entrepreneur']);

        $pageData = $this->paginate($this->adModel, 15);

        $ads = $this->adModel->getAllWithRelations(
            $pageData['limit'],
            $pageData['offset']
        );

        $this->render('admin/ads/index', [
            'title'      => 'Реклами на компании',
            'ads'        => $ads,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess(['admin', 'entrepreneur']);

        $this->render('admin/ads/form', [
            'title'     => 'Нова реклама',
            'companies' => $this->companyModel->getAllWithRelations(),
            'users'     => $this->userModel->all(),
            'nextOrder' => $this->adModel->getNextSortOrder(),
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');

        $_POST['user_id'] = !empty($_POST['auto_user_id']) ? $_POST['auto_user_id'] : ($_POST['user_id'] ?? null);

        if (empty($_POST['user_id'])) {
            $this->flash('error', 'Моля, изберете собственик за тази реклама.');
            $this->redirect($_SERVER['HTTP_REFERER']);
        }

        $this->handleStore($this->adModel, '/admin/ads', ['image_url'], 'ads');
    }

    public function edit($id)
    {
        $this->checkAccess(['admin', 'entrepreneur']);

        $ad = $this->adModel->find((int)$id);
        if (!$ad) {
            $this->flash('error', 'Рекламата не е намерена.');
            $this->redirect('/admin/ads');
        }

        $this->render('admin/ads/form', [
            'title'     => 'Редакция на реклама: ' . $ad['name'],
            'ad'        => $ad,
            'companies' => $this->companyModel->all(['order' => 'name ASC']),
            'users'     => $this->userModel->all(),
            'layout'    => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess(['admin', 'entrepreneur']);

        $this->handleUpdate(
            $this->adModel,
            (int)$id,
            '/admin/ads',
            ['image_url'],
            'ads'
        );
    }

    public function updateOrder()
    {
        $this->checkAccess(['admin', 'entrepreneur']);
        $this->handleOrderUpdate($this->adModel);
    }

    public function delete($id)
    {
        $this->checkAccess(['admin', 'entrepreneur']);

        $this->handleDelete(
            $this->adModel,
            (int)$id,
            '/admin/ads',
            ['image_url']
        );
    }
}