<?php

namespace App\Controllers;

use App\Models\Banner;
use App\Core\View;
use App\Services\FileService;

class BannerController extends BaseController
{
    private Banner $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new Banner();
    }

    public function index()
    {
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
        return View::render('admin/banners/form', [
            'title'      => 'Добавяне на банер',
            'positions'  => $this->getPositions(),
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $data = $this->prepareData($_POST);

        if (!empty($_FILES['image']['name'])) {
            $data['image'] = FileService::upload($_FILES['image']);
        }

        $newId = $this->bannerModel->create($data);

        if ($newId) {
            $this->flash('success', 'Банерът беше създаден успешно!');
            header('Location: /admin/banners/edit/' . $newId);
        } else {
            $this->flash('error', 'Възникна грешка при записа в базата данни.');
            header('Location: /admin/banners');
        }
        exit;
    }

    public function edit($id)
    {
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
        $banner = $this->bannerModel->find((int)$id);
        $data = $this->prepareData($_POST);

        $finalImage = $banner['image'];
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
            FileService::delete($banner['image']);
            $finalImage = null;
        }
        if (!empty($_FILES['image']['name'])) {
            FileService::delete($banner['image']);
            $finalImage = FileService::upload($_FILES['image']);
        }
        $data['image'] = $finalImage;

        if ($this->bannerModel->update((int)$id, $data)) {
            $this->flash('success', 'Промените бяха запазени!');
        }

        header('Location: /admin/banners/edit/' . $id);
        exit;
    }

    public function delete($id)
    {
        $banner = $this->bannerModel->find((int)$id);
        if ($banner) {
            FileService::delete($banner['image']);
            $this->bannerModel->delete((int)$id);
            $this->flash('success', 'Банерът беше изтрит.');
        }
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/admin/embassies';
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function updateOrder()
    {
        $this->middleware('admin');
        return $this->handleOrderUpdate($this->bannerModel);
    }

    private function prepareData(array $input): array
    {
        $data = $input;

        $flags = ['show_name', 'show_description', 'show_overlay', 'show_button'];

        foreach ($flags as $flag) {
            $data[$flag] = isset($input[$flag]) ? 1 : 0;
        }

        $data['sort_order'] = !empty($input['sort_order']) ? (int)$input['sort_order'] : 0;
        $data['height'] = !empty($input['height']) ? (int)$input['height'] : 520;

        unset($data['remove_image'], $data['return_url']);

        return $data;
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