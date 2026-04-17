<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Embassy;
use App\Models\Country;
use App\Core\View;
use App\Models\CountryElement;
use App\Services\HelperService;

class EmbassyController extends BaseController
{
    private Embassy $embassyModel;
    private Country $countryModel;
    private CountryElement $elementModel;

    public function __construct()
    {
        $this->embassyModel = new Embassy();
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

        $searchTerm = $_GET['search'] ?? null;

        $embassyElement = $this->elementModel->all([
            'where' => [
                'country_id' => $country['id'],
                'slug'       => 'embassies',
                'is_active'  => 1
            ]
        ])[0] ?? null;

        $filterOptions = [
            'where' => ['country_id' => $country['id']],
            'search' => $searchTerm
        ];

        $embassies = $this->embassyModel->getFilteredWithCountry($filterOptions);

        foreach ($embassies as &$e) {
            $e['entity_type'] = 'embassy';
        }

        View::render('embassies/index', [
            'title'          => HelperService::trans('embassies_in') . ' ' . HelperService::getTranslation($country, 'name', 'country'),
            'country'        => $country,
            'embassyElement' => $embassyElement,
            'embassies'      => $embassies,
            'searchTerm'     => $searchTerm,
            'breadcrumbs' => [
                [
                    'label' => HelperService::getTranslation($country, 'name', 'country'),
                    'href'  => '/' . $country['slug']
                ],
                [
                    'label' => HelperService::trans('embassies'),
                ],
            ],
            'layout'         => 'secondary'
        ]);
    }

    public function show($countrySlug, $embassySlug)
    {
        $country = $this->countryModel->where('slug', $countrySlug)[0] ?? null;
        if (!$country) $this->abort(404);
        $country['entity_type'] = 'country';

        $embassy = $this->embassyModel->all([
            'where' => [
                'slug' => $embassySlug,
                'country_id' => $country['id']
            ]
        ])[0] ?? null;

        if (!$embassy) $this->abort(404);
        $embassy['entity_type'] = 'embassy';

        View::render('embassies/show', [
            'title'   => HelperService::getTranslation($embassy, 'heading', 'embassy') . ' - ' . HelperService::trans('i_the_migrant'),
            'embassy' => $embassy,
            'country' => $country,
            'layout' => 'secondary'
        ]);
    }

    // admin routes

    public function index()
    {
        $this->checkAccess('admin');
        $pageData = $this->paginate($this->embassyModel);

        $embassies = $this->embassyModel->getAllWithCountries([
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'sort_order ASC, name ASC'
        ]);

        View::render('admin/embassies/index', [
            'title'      => HelperService::trans('admin_embassies_title'),
            'embassies'  => $embassies,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        return View::render('admin/embassies/form', [
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'title'     => HelperService::trans('admin_add_embassy'),
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->embassyModel, '/admin/embassies', ['image_url', 'image_tablet_url', 'image_mobile_url', 'logo', 'right_heading_image'], 'embassies');
    }

    public function edit($id)
    {
        $this->checkAccess('admin');

        $embassy = $this->embassyModel->find((int)$id);
        if (!$embassy) {
            $this->flash('error', HelperService::trans('error_record_not_found'));
            $this->redirect('/admin/embassies');
        }

        $embassy['translations'] = $this->getMappedTranslations('embassy', $id);

        $nextId = $this->embassyModel->getNextId($id);
        $prevId = $this->embassyModel->getPrevId($id);

        View::render('admin/embassies/form', [
            'title'     => HelperService::trans('admin_edit_label') . ' ' . $embassy['name'],
            'embassy'   => $embassy,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'nextId'    => $nextId,
            'prevId'    => $prevId,
            'languages' => HelperService::AVAILABLE_LANGUAGES,
            'layout'    => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->embassyModel, (int)$id, '/admin/embassies', ['image_url', 'image_tablet_url', 'image_mobile_url', 'logo', 'right_heading_image'], 'embassies');
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        return $this->handleOrderUpdate($this->embassyModel);
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->embassyModel, (int)$id, null, ['image_url', 'image_tablet_url', 'image_mobile_url', 'logo', 'right_heading_image']);
    }
}