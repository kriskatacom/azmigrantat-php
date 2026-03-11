<?php

namespace App\Controllers;

use App\Models\CompanyOffer;
use App\Models\Company;
use App\Models\User;
use App\Services\HelperService;

class CompanyOfferController extends BaseController
{
    private CompanyOffer $offerModel;
    private Company $companyModel;
    private User $userModel;

    public function __construct()
    {
        $this->offerModel = new CompanyOffer();
        $this->companyModel = new Company();
        $this->userModel = new User();
    }

    public function index()
    {
        $this->checkAccess(['admin', 'entrepreneur']);

        $pageData = $this->paginate($this->offerModel, 15);

        $offers = $this->offerModel->getAllWithRelations(
            $pageData['limit'],
            $pageData['offset']
        );

        $this->render('admin/offers/index', [
            'title'      => HelperService::trans('company_ads'),
            'offers'     => $offers,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess(['admin', 'entrepreneur']);

        $this->render('admin/offers/form', [
            'title'     => HelperService::trans('new_offer'),
            'companies' => $this->companyModel->getAllWithRelations(),
            'users'     => $this->userModel->all(),
            'nextOrder' => $this->offerModel->getNextSortOrder(),
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');

        $_POST['user_id'] = !empty($_POST['auto_user_id']) ? $_POST['auto_user_id'] : ($_POST['user_id'] ?? null);

        if (empty($_POST['user_id'])) {
            $this->flash('error', 'Моля, изберете собственик за тази обява.');
            $this->redirect($_SERVER['HTTP_REFERER']);
        }

        $this->handleStore($this->offerModel, '/admin/offers', ['image_url'], 'offers');
    }

    public function edit($id)
    {
        $this->checkAccess(['admin', 'entrepreneur']);

        $ad = $this->offerModel->find((int)$id);
        if (!$ad) {
            $this->flash('error', 'Обявата не е намерена.');
            $this->redirect('/admin/offers');
        }

        $this->render('admin/offers/form', [
            'title'     => 'Редакция на обява: ' . $ad['name'],
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
            $this->offerModel,
            (int)$id,
            '/admin/offers',
            ['image_url'],
            'offers'
        );
    }

    public function updateOrder()
    {
        $this->checkAccess(['admin', 'entrepreneur']);
        $this->handleOrderUpdate($this->offerModel);
    }

    public function delete($id)
    {
        $this->checkAccess(['admin', 'entrepreneur']);

        $this->handleDelete(
            $this->offerModel,
            (int)$id,
            '/admin/offers',
            ['image_url']
        );
    }
}
