<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Core\View;
use App\Models\Category;
use App\Models\Country;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyAd;
use App\Models\CompanyOffer;
use App\Services\HelperService;

class CategoryController extends BaseController
{
    protected Category $categoryModel;
    protected Country $countryModel;
    protected City $cityModel;
    protected Company $companyModel;
    protected CompanyAd $adModel;
    protected CompanyOffer $offerModel;

    protected string $baseRoute = '/admin/categories';

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->countryModel = new Country();
        $this->cityModel = new City();
        $this->companyModel = new Company();
        $this->adModel = new CompanyAd();
        $this->offerModel = new CompanyOffer();
    }

    // Public Routes

    public function categoriesShow($countrySlug, $citySlug, $categoriesPath = null)
    {
        $country = $this->countryModel->where('slug', $countrySlug)[0] ?? null;
        if (!$country || !$country['is_active']) return $this->abort404('Държавата не е намерена.');
        $country['entity_type'] = 'country';

        $city = $this->cityModel->all([
            'where' => ['slug' => $citySlug, 'country_id' => $country['id'], 'is_active' => 1]
        ])[0] ?? null;
        if (!$city) return $this->abort404('Градът не е намерен.');
        $city['entity_type'] = 'city';

        $categoryPathArr = $categoriesPath ? explode('/', trim($categoriesPath, '/')) : [];
        $lastSlug = !empty($categoryPathArr) ? end($categoryPathArr) : null;

        if ($lastSlug === 'about') {
            $companySlug = $categoryPathArr[count($categoryPathArr) - 2] ?? null;
            if ($companySlug) {
                $company = $this->companyModel->all([
                    'where' => ['slug' => $companySlug, 'city_id' => $city['id'], 'is_active' => 1]
                ])[0] ?? null;

                if ($company) {
                    $company['entity_type'] = 'company';
                    array_pop($categoryPathArr);
                    return $this->renderCompanyAbout($company, $country, $city, $categoryPathArr);
                }
            }
        }

        if ($lastSlug) {
            $company = $this->companyModel->all([
                'where' => ['slug' => $lastSlug, 'city_id' => $city['id'], 'is_active' => 1]
            ])[0] ?? null;

            if ($company) {
                $company['entity_type'] = 'company';
                return $this->renderCompanyDetail($company, $country, $city, $categoryPathArr);
            }
        }

        $category = null;
        if ($lastSlug) {
            $category = $this->categoryModel->all(['where' => ['slug' => $lastSlug, 'is_active' => 1]])[0] ?? null;
            if (!$category) return $this->abort404('Страницата не е намерена.');
            $category['entity_type'] = 'category';
        }

        $breadcrumbs = [
            ['label' => HelperService::getTranslation($country, 'name', 'country'), 'href' => '/' . $country['slug']],
            ['label' => HelperService::trans('cities'), 'href' => '/' . $country['slug'] . '/cities'],
            ['label' => HelperService::getTranslation($city, 'name', 'city'), 'href' => '/' . $country['slug'] . '/cities/' . $city['slug']],
        ];

        $currentPath = '/' . $country['slug'] . '/cities/' . $city['slug'];
        $runningPath = '';

        foreach ($categoryPathArr as $slug) {
            $runningPath .= '/' . $slug;
            $catInfo = $this->categoryModel->all(['where' => ['slug' => $slug]])[0] ?? null;

            $label = $catInfo
                ? HelperService::getTranslation($catInfo, 'name', 'category')
                : mb_convert_case(str_replace('-', ' ', $slug), MB_CASE_TITLE, "UTF-8");

            $breadcrumbs[] = [
                'label' => $label,
                'href'  => $currentPath . $runningPath
            ];
        }

        $displayData = $this->resolveDisplayItems($category, $city['id']);
        foreach ($displayData['items'] as &$item) {
            $item['entity_type'] = ($displayData['type'] === 'categories') ? 'category' : 'company';
        }

        $scope = $_GET['scope'] ?? null;

        $offers = $this->getOffersForCategory($category, $city['id'], $country['id'], $scope);

        View::render('categories/show', [
            'title'        => ($category ? HelperService::getTranslation($category, 'name', 'category') : HelperService::trans('categories')) . ' - ' . HelperService::getTranslation($city, 'name', 'city'),
            'country'      => $country,
            'city'         => $city,
            'category'     => $category,
            'items'        => $displayData['items'],
            'showType'     => $displayData['type'],
            'offers'       => $offers,
            'countryName' => HelperService::getTranslation($country, 'name', 'country'),
            'cityName'    => HelperService::getTranslation($city, 'name', 'city'),
            'breadcrumbs'  => $breadcrumbs,
            'base_url'     => "/{$countrySlug}/cities/{$citySlug}/" . ($categoriesPath ? trim($categoriesPath, '/') . '/' : ''),
            'categoryPath' => $categoryPathArr
        ]);
    }

    private function renderCompanyAbout($company, $country, $city, $pathArray)
    {
        $company['entity_type'] = 'company';
        View::render('companies/about', [
            'company' => $company,
            'country' => $country,
            'city'    => $city,
            'categoryPath' => $pathArray
        ]);
    }

    private function renderCompanyDetail($company, $country, $city, $pathArray)
    {
        $company['entity_type'] = 'company';

        $category = null;
        if (!empty($company['category_id'])) {
            $category = $this->categoryModel->find($company['category_id']);
            if ($category) $category['entity_type'] = 'category';
        }

        $ads = $this->adModel->all([
            'where' => ['company_id' => $company['id'], 'status' => 'active'],
            'order' => 'sort_order ASC'
        ]);
        foreach ($ads as &$ad) {
            $ad['entity_type'] = 'company_ad';
        }

        $offers = $this->offerModel->all([
            'where' => ['company_id' => $company['id'], 'status' => 'active'],
            'order' => 'sort_order ASC'
        ]);
        foreach ($offers as &$offer) {
            $offer['entity_type'] = 'offer';
        }

        $breadcrumbs = [
            ['label' => HelperService::getTranslation($country, 'name', 'country'), 'href' => '/' . $country['slug']],
            ['label' => HelperService::trans('cities'), 'href' => '/' . $country['slug'] . '/cities'],
            ['label' => HelperService::getTranslation($city, 'name', 'city'), 'href' => '/' . $country['slug'] . '/cities/' . $city['slug']],
        ];

        $currentPath = '/' . $country['slug'] . '/cities/' . $city['slug'];
        $runningPath = '';
        foreach ($pathArray as $slug) {
            if ($slug === $company['slug']) continue;
            $runningPath .= '/' . $slug;
            $cat = $this->categoryModel->all(['where' => ['slug' => $slug]])[0] ?? null;
            $breadcrumbs[] = [
                'label' => $cat ? HelperService::getTranslation($cat, 'name', 'category') : $slug,
                'href'  => $currentPath . $runningPath
            ];
        }
        $breadcrumbs[] = ['label' => HelperService::getTranslation($company, 'name', 'company'), 'href' => ''];

        View::render('companies/details', [
            'title'       => HelperService::getTranslation($company, 'name', 'company') . ' - ' . HelperService::getTranslation($city, 'name', 'city'),
            'company'     => $company,
            'country'     => $country,
            'city'        => $city,
            'category'    => $category,
            'ads'         => $ads,
            'offers'      => $offers,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    private function getOffersForCategory($category, $cityId, $countryId, $scope = null)
    {
        if (!$category) return [];

        $allCompanies = $this->companyModel->all([
            'where' => [
                'category_id' => (int)$category['id'],
                'is_active'   => 1
            ]
        ]);

        if (empty($allCompanies)) return [];

        $companiesMap = [];
        $targetCountryId = (int)$countryId;
        $targetCityId = (int)$cityId;

        foreach ($allCompanies as $comp) {
            $compCountryId = (int)($comp['country_id'] ?? 0);
            $compCityId = (int)($comp['city_id'] ?? 0);

            if ($scope === 'country') {
                if ($compCountryId === $targetCountryId) {
                    $companiesMap[$comp['id']] = $comp;
                }
            } else {
                if ($compCityId === $targetCityId) {
                    $companiesMap[$comp['id']] = $comp;
                }
            }
        }

        if (empty($companiesMap)) return [];

        $timeString = ($scope === null) ? '-24 hours' : '-1 month';
        $thresholdTimestamp = strtotime($timeString);

        $rawOffers = $this->offerModel->all([
            'where_in' => ['company_id' => array_keys($companiesMap)],
            'where'    => ['status' => 'active'],
            'order'    => 'created_at DESC'
        ]);

        $offers = [];
        foreach ($rawOffers as $offer) {
            if (!isset($companiesMap[$offer['company_id']])) continue;

            $dateColumn = $offer['created_at'] ?? $offer['date_created'] ?? null;
            $offerTime = $dateColumn ? strtotime($dateColumn) : 0;

            if ($offerTime < $thresholdTimestamp) {
                continue;
            }

            $company = $companiesMap[$offer['company_id']];

            $offer['entity_type'] = 'offer';
            $offer['company_slug'] = $company['slug'];
            $offer['company_name'] = HelperService::getTranslation($company, 'name', 'company');
            $offer['city_name'] = HelperService::getTranslation($company, 'city_name', 'city');

            $offers[] = $offer;
        }

        return $offers;
    }

    private function resolveDisplayItems(?array $category, int $cityId): array
    {
        if (!$category) {
            return [
                'type'  => 'categories',
                'items' => $this->categoryModel->all([
                    'where' => ['parent_id' => null, 'is_active' => 1],
                    'order' => 'sort_order ASC'
                ])
            ];
        }

        $subCategories = $this->categoryModel->all([
            'where' => ['parent_id' => $category['id'], 'is_active' => 1],
            'order' => 'sort_order ASC'
        ]);

        if (!empty($subCategories)) {
            return ['type' => 'categories', 'items' => $subCategories];
        }

        return [
            'type'  => 'companies',
            'items' => $this->companyModel->all([
                'where' => [
                    'category_id' => $category['id'],
                    'city_id'     => $cityId,
                    'is_active'   => 1
                ],
                'order' => 'sort_order ASC'
            ])
        ];
    }

    // --- Admin Routes ---

    public function index()
    {
        $this->checkAccess('admin');
        $parentId = isset($_GET['parent_id']) ? (int)$_GET['parent_id'] : null;
        $this->categoryModel->setFilterParent($parentId);
        $paginationData = $this->paginate($this->categoryModel);

        $path = $parentId ? $this->categoryModel->getBreadcrumbs($parentId) : [];

        $categories = $this->categoryModel->getByParentPaginated(
            $parentId,
            $paginationData['limit'],
            $paginationData['offset']
        );

        $this->render('admin/categories/index', [
            'title'      => 'Категории',
            'categories' => $categories,
            'parentId'   => $parentId,
            'pagination' => $paginationData['pagination'],
            'path'       => $path,
            'layout'     => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');
        $this->render('admin/categories/form', [
            'title'      => 'Нова категория',
            'categories' => $this->categoryModel->getTree(),
            'category'   => null,
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore($this->categoryModel, $this->baseRoute, ['image_url', 'companies_background_url'], 'categories');
    }

    public function edit(int $id)
    {
        $this->checkAccess('admin');

        $category = $this->categoryModel->find($id);
        if (!$category) {
            $this->flash('error', 'Записът не е намерен.');
            $this->redirect($this->baseRoute);
        }

        $category['translations'] = $this->getMappedTranslations('category', $id);

        $nextId = $this->categoryModel->getNextId($id);
        $prevId = $this->categoryModel->getPrevId($id);

        $this->render('admin/categories/form', [
            'title'      => 'Редактиране: ' . $category['name'],
            'categories' => $this->categoryModel->getTree(),
            'category'   => $category,
            'nextId'   => $nextId,
            'prevId'   => $prevId,
            'languages'  => HelperService::AVAILABLE_LANGUAGES,
            'layout'     => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate($this->categoryModel, $id, $this->baseRoute, ['image_url', 'companies_background_url'], 'categories');
    }

    public function delete(int $id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->categoryModel, $id, $this->baseRoute, ['image_url', 'companies_background_url']);
    }

    public function updateOrder()
    {
        $this->checkAccess('admin');
        $this->handleOrderUpdate($this->categoryModel);
    }
}