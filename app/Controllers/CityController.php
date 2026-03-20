<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\City;
use App\Models\Country;
use App\Core\View;
use App\Models\CountryElement;
use App\Services\HelperService;

class CityController extends BaseController
{
    protected City $cityModel;
    protected Country $countryModel;

    public function __construct()
    {
        $this->cityModel = new City();
        $this->countryModel = new Country();
    }

    // public routes

    public function indexByCountry($countrySlug)
    {
        $elementModel = new CountryElement();

        $countryResults = $this->countryModel->where('slug', $countrySlug);
        $country = $countryResults[0] ?? null;

        if (!$country || (isset($country['is_active']) && !$country['is_active'])) {
            header("HTTP/1.0 404 Not Found");
            exit(HelperService::trans('error_country_not_found'));
        }

        $country['entity_type'] = 'country';

        // Вземаме термина за търсене
        $searchTerm = $_GET['search'] ?? null;

        $cityElement = $elementModel->all([
            'where' => [
                'country_id' => $country['id'],
                'slug'       => 'cities',
                'is_active'  => 1
            ]
        ])[0] ?? null;

        if ($cityElement) {
            $cityElement['entity_type'] = 'country_element';
        }

        // Подготвяме опциите за филтриране
        $filterOptions = [
            'where' => [
                'country_id' => $country['id'],
                'is_active'  => 1
            ],
            'order' => 'sort_order ASC'
        ];

        // Логика за търсене
        if (!empty($searchTerm)) {
            $filterOptions = [
                'where'  => ['country_id' => $country['id']],
                'search' => $searchTerm, // Моделът трябва да поддържа 'search' ключ
                'order'  => 'sort_order ASC'
            ];

            // Тук приемаме, че имаш метод getFiltered в CityModel, подобно на LandmarkModel
            $cities = $this->cityModel->getFiltered($filterOptions);
        } else {
            $cities = $this->cityModel->all($filterOptions);
        }

        foreach ($cities as &$city) {
            $city['entity_type'] = 'city';
        }

        View::render('cities/index', [
            'title'       => HelperService::trans('cities_in') . ' ' . HelperService::getTranslation($country, 'name', 'country') . ' - ' . HelperService::trans('i_the_migrant'),
            'country'     => $country,
            'cityElement' => $cityElement,
            'cities'      => $cities,
            'searchTerm'  => $searchTerm, // Подаваме го към View-то, за да го покажем в input полето
            'layout'      => 'secondary',
            'breadcrumbs' => [
                [
                    'label' => HelperService::getTranslation($country, 'name', 'country'),
                    'href'  => '/' . $country['slug']
                ],
                [
                    'label' => HelperService::trans('cities'),
                ],
            ],
        ]);
    }

    // admin routes

    public function index()
    {
        $this->checkAccess('admin');
        $pageData = $this->paginate($this->cityModel);

        $cities = $this->cityModel->getWithCountry(
            $pageData['limit'],
            $pageData['offset']
        );

        View::render('admin/cities/index', [
            'title'      => HelperService::trans('admin_manage_cities'),
            'cities'     => $cities,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        View::render('admin/cities/form', [
            'title' => HelperService::trans('admin_add_city'),
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->cityModel, '/admin/cities', ['image_url'], 'cities');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');

        $city = $this->cityModel->find($id);
        if (!$city) {
            $this->flash('error', HelperService::trans('error_record_not_found'));
            $this->redirect('/admin/cities');
        }

        $city['translations'] = $this->getMappedTranslations('city', $id);

        $nextId = $this->cityModel->getNextId($id);
        $prevId = $this->cityModel->getPrevId($id);

        View::render('admin/cities/form', [
            'title'        => HelperService::trans('admin_edit_label') . ' ' . $city['name'],
            'city'      => $city,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'nextId'       => $nextId,
            'prevId'       => $prevId,
            'languages'    => HelperService::AVAILABLE_LANGUAGES,
            'layout'       => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->cityModel, (int)$id, '/admin/cities', ['image_url', 'image_tablet_url', 'image_mobile_url'], 'cities');
    }

    public function getByCountry(int $countryId)
    {
        $cities = $this->cityModel->getByCountry($countryId);
        header('Content-Type: application/json');
        echo json_encode($cities);
        exit;
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->cityModel);
    }

    public function delete(int $id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->cityModel, (int)$id, null, ['image_url', 'image_tablet_url', 'image_mobile_url']);
    }
}