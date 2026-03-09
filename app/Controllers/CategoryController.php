<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Core\View;
use App\Models\Category;
use App\Models\Country;
use App\Models\City;
use App\Models\Company;

class CategoryController extends BaseController
{
    protected Category $categoryModel;
    protected Country $countryModel;
    protected City $cityModel;
    protected Company $companyModel;

    protected string $baseRoute = '/admin/categories';

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->countryModel = new Country();
        $this->cityModel = new City();
        $this->companyModel = new Company();
    }

    // Public Routes

    public function categoriesShow($countrySlug, $citySlug, $categoriesPath = null)
    {
        $country = $this->countryModel->where('slug', $countrySlug)[0] ?? null;
        if (!$country || !$country['is_active']) return $this->abort404('Държавата не е намерена.');

        $city = $this->cityModel->all([
            'where' => ['slug' => $citySlug, 'country_id' => $country['id'], 'is_active' => 1]
        ])[0] ?? null;
        if (!$city) return $this->abort404('Градът не е намерен.');

        $categoryPathArr = $categoriesPath ? explode('/', trim($categoriesPath, '/')) : [];
        $lastSlug = !empty($categoryPathArr) ? end($categoryPathArr) : null;

        if ($lastSlug === 'about') {
            $companySlug = $categoryPathArr[count($categoryPathArr) - 2] ?? null;

            if ($companySlug) {
                $company = $this->companyModel->all([
                    'where' => ['slug' => $companySlug, 'city_id' => $city['id'], 'is_active' => 1]
                ])[0] ?? null;

                if ($company) {
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
                return $this->renderCompanyDetail($company, $country, $city, $categoryPathArr);
            }
        }

        $category = null;
        if ($lastSlug) {
            $category = $this->categoryModel->all(['where' => ['slug' => $lastSlug, 'is_active' => 1]])[0] ?? null;
            if (!$category) return $this->abort404('Страницата не е намерена.');
        }

        $breadcrumbs = [
            ['label' => $country['name'], 'href' => '/' . $country['slug']],
            ['label' => 'Градове', 'href' => '/' . $country['slug'] . '/cities'],
            ['label' => $city['name'], 'href' => '/' . $country['slug'] . '/cities/' . $city['slug']],
        ];

        $currentPath = '/' . $country['slug'] . '/cities/' . $city['slug'];
        $runningPath = '';

        foreach ($categoryPathArr as $slug) {
            $runningPath .= '/' . $slug;
            $catInfo = $this->categoryModel->all(['where' => ['slug' => $slug]])[0] ?? null;

            $breadcrumbs[] = [
                'label' => $catInfo ? $catInfo['name'] : mb_convert_case(str_replace('-', ' ', $slug), MB_CASE_TITLE, "UTF-8"),
                'href'   => $currentPath . $runningPath
            ];
        }

        $data = $this->resolveDisplayItems($category, $city['id']);

        View::render('categories/show', [
            'title'        => ($category ? $category['name'] : 'Категории') . ' - ' . $city['name'],
            'country'      => $country,
            'city'         => $city,
            'category'     => $category,
            'items'        => $data['items'],
            'showType'     => $data['type'],
            'base_url'     => "/{$countrySlug}/cities/{$citySlug}/" . ($categoriesPath ? trim($categoriesPath, '/') . '/' : ''),
            'categoryPath' => $categoryPathArr,
            'breadcrumbs'  => $breadcrumbs,
        ]);
    }

    private function renderCompanyAbout($company, $country, $city, $pathArray)
    {
        View::render('companies/about', [
            'company' => $company,
            'country' => $country,
            'city' => $city,
            'categoryPath' => $pathArray
        ]);
    }

    private function renderCompanyDetail($company, $country, $city, $pathArray)
    {
        $category = null;
        if (!empty($company['category_id'])) {
            $category = $this->categoryModel->all(['where' => ['id' => $company['category_id']]])[0] ?? null;
        }

        $breadcrumbs = [
            ['label' => $country['name'], 'href' => '/' . $country['slug']],
            ['label' => 'Градове', 'href' => '/' . $country['slug'] . '/cities'],
            ['label' => $city['name'], 'href' => '/' . $country['slug'] . '/cities/' . $city['slug']],
        ];

        $currentPath = '/' . $country['slug'] . '/cities/' . $city['slug'];
        $runningPath = '';

        if (!empty($pathArray)) {
            foreach ($pathArray as $slug) {
                if ($slug === $company['slug']) {
                    continue;
                }

                $runningPath .= '/' . $slug;
                $cat = $this->categoryModel->all(['where' => ['slug' => $slug]])[0] ?? null;

                $breadcrumbs[] = [
                    'label' => $cat ? $cat['name'] : mb_convert_case(str_replace('-', ' ', $slug), MB_CASE_TITLE, "UTF-8"),
                    'href'  => $currentPath . $runningPath
                ];
            }
        }

        $breadcrumbs[] = ['label' => '', 'href' => ''];

        View::render('companies/details', [
            'title'       => $company['name'] . ' - ' . $city['name'],
            'company'     => $company,
            'country'     => $country,
            'city'        => $city,
            'category'    => $category,
            'breadcrumbs' => $breadcrumbs
        ]);
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

    private function abort404($message)
    {
        http_response_code(404);
        echo $message;
        exit;
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

        $this->render('admin/categories/form', [
            'title'      => 'Редактиране: ' . $category['name'],
            'categories' => $this->categoryModel->getTree(),
            'category'   => $category,
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