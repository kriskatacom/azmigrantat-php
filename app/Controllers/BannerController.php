<?php

namespace App\Controllers;

use App\Models\Banner;
use App\Core\View;

class BannerController extends BaseController
{
    private Banner $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new Banner();
    }

    public function index()
    {
        $this->checkAccess('admin');
        $groupKey = $_GET['group_key'] ?? null;

        $paginationData = $this->paginate($this->bannerModel, 10);

        if ($groupKey) {
            $total = $this->bannerModel->countFiltered($groupKey);
            $paginationData['pagination']['total'] = ceil($total / $paginationData['limit']);
            $paginationData['pagination']['total_records'] = $total;
        }

        $banners = $this->bannerModel->getFiltered(
            $groupKey,
            $paginationData['limit'],
            $paginationData['offset']
        );

        $groups = $this->bannerModel->getUniqueGroups();

        View::render('admin/banners/index', [
            'title'      => 'Управление на банери',
            'banners'    => $banners,
            'groups'     => $groups,
            'layout'     => 'admin',
            'pagination' => $paginationData['pagination'],
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        return View::render('admin/banners/form', [
            'title'      => 'Добавяне на банер',
            'positions'  => $this->getPositions(),
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->bannerModel, '/admin/banners', ['image_url'], 'banners');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
        $banner = $this->bannerModel->find((int)$id);
        if (!$banner) exit('Банерът не е намерен');

        return View::render('admin/banners/form', [
            'title'     => 'Редактиране на банер',
            'banner'    => $banner,
            'positions' => $this->getPositions(),
            'layout'    => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->bannerModel, (int)$id, '/admin/banners', ['image_url'], 'banners');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->bannerModel);
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->bannerModel, (int)$id, null, ['image']);
    }

    private function getPositions(): array
    {
        return [
            'top_left' => 'Горе вляво',
            'top_center' => 'Горе център',
            'top_right' => 'Горе вдясно',
            'center_left' => 'Център вляво',
            'center_center' => 'Център',
            'center_right' => 'Център вдясно',
            'bottom_left' => 'Долу вляво',
            'bottom_center' => 'Долу център',
            'bottom_right' => 'Долу вдясно'
        ];
    }
}
