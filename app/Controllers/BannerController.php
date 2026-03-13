<?php

namespace App\Controllers;

use App\Models\Banner;
use App\Core\View;
use App\Services\HelperService;

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

        $groups = $this->bannerModel->getUniqueGroups();

        return View::render('admin/banners/form', [
            'title'      => 'Добавяне на банер',
            'positions'  => $this->getPositions(),
            'groups'  => $groups,
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

        $banner = $this->bannerModel->find($id);
        if (!$banner) {
            $this->flash('error', 'Записът не е намерен.');
            $this->redirect('/admin/banners');
        }

        $banner['translations'] = $this->getMappedTranslations('banner', $id);

        $nextId = $this->bannerModel->getNextId($id);
        $prevId = $this->bannerModel->getPrevId($id);

        View::render('admin/banners/form', [
            'title'        => 'Редактиране на ' . $banner['name'],
            'banner'      => $banner,
            'nextId'       => $nextId,
            'prevId'       => $prevId,
            'languages'    => HelperService::AVAILABLE_LANGUAGES,
            'layout'       => 'admin'
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
