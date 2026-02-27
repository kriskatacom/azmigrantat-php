<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Embassy;
use App\Models\Country;
use App\Core\View;
use App\Services\FileService;
use App\Services\HelperService;

class EmbassyController extends BaseController
{
    private Embassy $embassyModel;

    public function __construct()
    {
        $this->embassyModel = new Embassy();
    }

    public function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 10);
        $offset = ($page - 1) * $perPage;

        $embassies = $this->embassyModel->getAllWithCountries([
            'limit' => $perPage,
            'offset' => $offset,
            'order' => 'sort_order ASC, name ASC'
        ]);

        $total = $this->embassyModel->count();

        View::render('admin/embassies/index', [
            'title' => 'Посолства',
            'embassies' => $embassies,
            'layout' => 'admin',
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $perPage),
                'per_page' => $perPage,
            ],
        ]);
    }

    public function create()
    {
        $countryModel = new Country();
        return View::render('admin/embassies/form', [
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'title' => 'Добавяне на посолство',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $data = $_POST;

        $data['slug'] = !empty($data['slug'])
            ? HelperService::slug($data['slug'])
            : HelperService::slug($data['name']);

        $data['image_url'] = !empty($_FILES['image_url']['name']) ? FileService::upload($_FILES['image_url']) : null;
        $data['logo'] = !empty($_FILES['logo']['name']) ? FileService::upload($_FILES['logo']) : null;
        $data['right_heading_image'] = !empty($_FILES['right_heading_image']['name']) ? FileService::upload($_FILES['right_heading_image']) : null;

        $gallery = [];
        if (!empty($_FILES['additional_images']['name'][0])) {
            foreach ($_FILES['additional_images']['name'] as $key => $val) {
                if ($_FILES['additional_images']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileData = [
                        'name'     => $_FILES['additional_images']['name'][$key],
                        'type'     => $_FILES['additional_images']['type'][$key],
                        'tmp_name' => $_FILES['additional_images']['tmp_name'][$key],
                        'error'    => $_FILES['additional_images']['error'][$key],
                        'size'     => $_FILES['additional_images']['size'][$key]
                    ];
                    $gallery[] = FileService::upload($fileData);
                }
            }
        }
        $data['additional_images'] = json_encode($gallery);

        $data['country_id'] = !empty($data['country_id']) ? (int)$data['country_id'] : null;
        $data['sort_order'] = !empty($data['sort_order']) ? (int)$data['sort_order'] : 0;

        unset($data['remove_image_url'], $data['remove_logo'], $data['remove_right_heading_image'], $data['existing_images'], $data['return_url']);

        $newId = $this->embassyModel->create($data);

        if ($newId) {
            $this->flash('success', 'Посолството беше създадено успешно!');
            header('Location: /admin/embassies/edit/' . $newId);
        } else {
            $this->flash('error', 'Грешка при записа.');
            header('Location: /admin/embassies');
        }
        exit;
    }

    public function edit($id)
    {
        $embassy = $this->embassyModel->find((int)$id);
        if (!$embassy) {
            header('Location: /admin/embassies?error=notfound');
            exit;
        }

        $countryModel = new Country();
        return View::render('admin/embassies/form', [
            'title' => 'Редактиране на посолство',
            'layout' => 'admin',
            'embassy'  => $embassy,
            'countries' => $countryModel->all(['order' => 'name ASC'])
        ]);
    }

    public function update($id)
    {
        $embassy = $this->embassyModel->find((int)$id);
        if (!$embassy) exit;

        $data = $_POST;

        $data['slug'] = !empty($data['slug']) ? HelperService::slug($data['slug']) : HelperService::slug($data['name']);

        $imagesToHandle = ['image_url', 'logo', 'right_heading_image'];
        foreach ($imagesToHandle as $field) {
            $finalUrl = $embassy[$field];

            if (isset($data["remove_$field"]) && $data["remove_$field"] == '1') {
                FileService::delete($embassy[$field]);
                $finalUrl = null;
            }

            if (!empty($_FILES[$field]['name'])) {
                FileService::delete($embassy[$field]);
                $finalUrl = FileService::upload($_FILES[$field]);
            }
            $data[$field] = $finalUrl;
        }

        $currentGallery = !empty($embassy['additional_images']) ? json_decode($embassy['additional_images'], true) : [];
        $remainingImages = $data['existing_images'] ?? [];

        $removedImages = array_diff($currentGallery, $remainingImages);
        foreach ($removedImages as $removedImg) {
            FileService::delete($removedImg);
        }

        if (!empty($_FILES['additional_images']['name'][0])) {
            foreach ($_FILES['additional_images']['name'] as $key => $val) {
                if ($_FILES['additional_images']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileData = [
                        'name' => $_FILES['additional_images']['name'][$key],
                        'type' => $_FILES['additional_images']['type'][$key],
                        'tmp_name' => $_FILES['additional_images']['tmp_name'][$key],
                        'error' => $_FILES['additional_images']['error'][$key],
                        'size' => $_FILES['additional_images']['size'][$key]
                    ];
                    $remainingImages[] = FileService::upload($fileData);
                }
            }
        }
        $data['additional_images'] = json_encode(array_values($remainingImages));

        $data['country_id'] = (int)$data['country_id'];
        unset($data['remove_image_url'], $data['remove_logo'], $data['remove_right_heading_image'], $data['existing_images'], $data['return_url']);

        if ($this->embassyModel->update((int)$id, $data)) {
            $this->flash('success', 'Посолството беше обновено успешно!');
        }

        header('Location: /admin/embassies/edit/' . $id);
        exit;
    }

    public function delete($id)
    {
        $this->middleware('admin');

        $embassy = $this->embassyModel->find((int)$id);

        if (!$embassy) {
            $this->flash('error', 'Посолството не е намерено.');
            header('Location: /admin/embassies');
            exit;
        }

        FileService::delete($embassy['image_url']);
        FileService::delete($embassy['logo']);
        FileService::delete($embassy['right_heading_image']);

        if (!empty($embassy['additional_images'])) {
            $gallery = json_decode($embassy['additional_images'], true);
            if (is_array($gallery)) {
                foreach ($gallery as $imgPath) {
                    FileService::delete($imgPath);
                }
            }
        }

        if ($this->embassyModel->delete((int)$id)) {
            $this->flash('success', 'Посолството беше изтрито успешно!');
        } else {
            $this->flash('error', 'Възникна грешка при изтриването.');
        }

        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/admin/embassies';
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function updateOrder()
    {
        $this->middleware('admin');
        return $this->handleOrderUpdate($this->embassyModel);
    }
}
