<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Landmark;
use App\Models\Country;
use App\Core\View;
use App\Models\CountryElement;
use App\Services\HelperService;

class LandmarkController extends BaseController
{
    private Landmark $landmarkModel;
    private Country $countryModel;
    private CountryElement $elementModel;

    public function __construct()
    {
        $this->landmarkModel = new Landmark();
        $this->countryModel = new Country();
        $this->elementModel = new CountryElement();
    }

    // public routes

    public function indexByCountry($countrySlug)
    {
        $country = $this->countryModel->where('slug', $countrySlug)[0] ?? null;

        if (!$country) {
            $this->abort(404);
        }
        $country['entity_type'] = 'country';

        $landmarkElement = $this->elementModel->all([
            'where' => [
                'country_id' => $country['id'],
                'slug'       => 'landmarks',
                'is_active'  => 1
            ]
        ])[0] ?? null;

        if ($landmarkElement) {
            $landmarkElement['entity_type'] = 'country_element';
        }

        $landmarks = $this->landmarkModel->all([
            'where' => [
                'country_id' => $country['id'],
                'is_active'  => 1
            ],
            'order' => 'sort_order ASC'
        ]);

        foreach ($landmarks as &$l) {
            $l['entity_type'] = 'landmark';
        }

        $translatedCountryName = HelperService::getTranslation($country, 'name');

        View::render('landmarks/index', [
            'title'           => HelperService::trans('landmarks_in') . ' ' . $translatedCountryName,
            'country'         => $country,
            'landmarkElement' => $landmarkElement,
            'landmarks'       => $landmarks
        ]);
    }

    public function show($countrySlug, $landmarkSlug)
    {
        $country = $this->countryModel->where('slug', $countrySlug)[0] ?? null;
        if (!$country) $this->abort(404);
        $country['entity_type'] = 'country';

        $landmark = $this->landmarkModel->all([
            'where' => [
                'slug' => $landmarkSlug,
                'country_id' => $country['id']
            ]
        ])[0] ?? null;

        if (!$landmark) $this->abort(404);
        $landmark['entity_type'] = 'landmark';

        View::render('landmarks/show', [
            'title'    => HelperService::getTranslation($landmark, 'name'),
            'landmark' => $landmark,
            'country'  => $country
        ]);
    }

    // admin routes

    public function index()
    {
        $this->checkAccess('admin');
        $pageData = $this->paginate($this->landmarkModel);

        $landmarks = $this->landmarkModel->getAllWithCountries([
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC, name ASC'
        ]);

        View::render('admin/landmarks/index', [
            'title'      => 'Забележителности',
            'landmarks'  => $landmarks,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $countryModel = new Country();
        return View::render('admin/landmarks/form', [
            'countries' => $countryModel->all(['order' => 'name ASC']),
            'title' => 'Добавяне на забележителност',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $_POST['additional_images'] = $this->handleGalleryUpdate(['additional_images' => '[]'], $_POST, 'additional_images', 'landmarks/gallery');

        $this->handleStore($this->landmarkModel, '/admin/landmarks', ['image_url'], 'landmarks');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');

        $landmark = $this->landmarkModel->find($id);
        if (!$landmark) {
            $this->flash('error', 'Записът не е намерен.');
            $this->redirect('/admin/landmarks');
        }

        $landmark['translations'] = $this->getMappedTranslations('landmark', $id);

        $nextId = $this->landmarkModel->getNextId($id);
        $prevId = $this->landmarkModel->getPrevId($id);

        View::render('admin/landmarks/form', [
            'title'        => 'Редактиране на ' . $landmark['name'],
            'landmark'      => $landmark,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'nextId'       => $nextId,
            'prevId'       => $prevId,
            'languages'    => HelperService::AVAILABLE_LANGUAGES,
            'layout'       => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $landmark = $this->landmarkModel->find((int)$id);
        if (!$landmark) $this->redirect('/admin/landmarks');

        $_POST['additional_images'] = $this->handleGalleryUpdate($landmark, $_POST, 'additional_images', 'landmarks/gallery');

        $this->handleUpdate($this->landmarkModel, (int)$id, '/admin/landmarks', ['image_url'], 'images');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->landmarkModel);
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->landmarkModel, (int)$id, null, ['image_url'], ['additional_images']);
    }
}
