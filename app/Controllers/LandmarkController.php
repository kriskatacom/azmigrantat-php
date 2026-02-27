<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Landmark;
use App\Models\Country;
use App\Core\View;
use App\Services\FileService;
use App\Services\HelperService;

class LandmarkController extends BaseController
{
    private Landmark $landmarkModel;

    public function __construct()
    {
        $this->landmarkModel = new Landmark();
    }

    public function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 10);
        $offset = ($page - 1) * $perPage;

        $landmarks = $this->landmarkModel->getAllWithCountries([
            'limit' => $perPage,
            'offset' => $offset,
            'order' => 'sort_order ASC, name ASC'
        ]);

        $total = $this->landmarkModel->count();

        View::render('admin/landmarks/index', [
            'title' => 'Забележителности',
            'landmarks' => $landmarks,
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
        return View::render('admin/landmarks/form', [
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'title' => 'Добавяне на забележителност',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $data = $_POST;

        $data['additional_images'] = json_encode([]);

        $data['sort_order'] = !empty($data['sort_order']) ? (int)$data['sort_order'] : 0;

        unset($data['remove_image_url']);

        $newId = $this->landmarkModel->create($data);

        if ($newId) {
            $this->flash('success', 'Забележителността "' . $data['name'] . '" беше създадена успешно!');
            header('Location: /admin/landmarks/edit/' . $newId);
        } else {
            $this->flash('error', 'Възникна грешка при записа в базата данни.');
            header('Location: /admin/landmarks');
        }
        exit;
    }

    public function edit($id)
    {
        $landmark = $this->landmarkModel->find((int)$id);

        if (!$landmark) {
            header('Location: /admin/landmarks?error=notfound');
            exit;
        }

        $countryModel = new Country();
        return View::render('admin/landmarks/form', [
            'title' => 'Редактиране на забележителност',
            'layout' => 'admin',
            'landmark'  => $landmark,
            'countries' => $countryModel->all(['order' => 'name ASC'])
        ]);
    }

    public function update($id)
    {
        $landmark = $this->landmarkModel->find((int)$id);
        if (!$landmark) {
            $this->flash('error', 'Забележителността не е намерена!');
            header('Location: /admin/landmarks');
            exit;
        }

        $data = $_POST;

        $data['slug'] = !empty($data['slug'])
            ? HelperService::slug($data['slug'])
            : HelperService::slug($data['name']);

        $finalImageUrl = $landmark['image_url'];

        if (isset($data['remove_image_url']) && $data['remove_image_url'] == '1') {
            FileService::delete($landmark['image_url']);
            $finalImageUrl = null;
        }

        if (!empty($_FILES['image_url']['name'])) {
            FileService::delete($landmark['image_url']);
            $finalImageUrl = FileService::upload($_FILES['image_url']);
        }

        $data['image_url'] = $finalImageUrl;

        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
        $data['country_id'] = (int)$data['country_id'];

        unset($data['remove_image_url']);

        if ($this->landmarkModel->update((int)$id, $data)) {
            $this->flash('success', 'Забележителността беше актуализирана успешно!');
        }

        header('Location: /admin/landmarks/edit/' . $id);
        exit;
    }

    public function delete($id)
    {
        if ($this->landmarkModel->delete((int)$id)) {
            $this->flash('success', 'Изтриването беше успешно!');
            header('Location: /admin/landmarks?success=deleted');
            exit;
        }
    }
}