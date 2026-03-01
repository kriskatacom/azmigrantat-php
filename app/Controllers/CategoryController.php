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
    protected string $baseRoute = '/admin/categories';

    public function __construct()
    {
        $this->middleware('admin', ['except' => ['categoriesShow']]);

        $this->categoryModel = new Category();
    }

    // Public Routes

    public function categoriesShow($countrySlug, $citySlug, $categoriesPath = null)
    {
        $countryModel = new Country();
        $cityModel = new City();
        $companyModel = new Company();

        $country = $countryModel->where('slug', $countrySlug)[0] ?? null;
        if (!$country || !$country['is_active']) return $this->abort404('Държавата не е намерена.');

        $city = $cityModel->all([
            'where' => ['slug' => $citySlug, 'country_id' => $country['id'], 'is_active' => 1]
        ])[0] ?? null;
        if (!$city) return $this->abort404('Градът не е намерен.');

        $displayItems = [];
        $showType = 'categories';
        $category = null;
        $categoryPathArr = [];
        $baseUrl = "/{$countrySlug}/cities/{$citySlug}/";

        if (!empty($categoriesPath)) {
            $categoriesPath = trim($categoriesPath, '/');
            $categoryPathArr = explode('/', $categoriesPath);
            $currentCategorySlug = end($categoryPathArr);

            $category = $this->categoryModel->all([
                'where' => ['slug' => $currentCategorySlug, 'is_active' => 1]
            ])[0] ?? null;

            if (!$category) return $this->abort404('Категорията не е намерена.');

            $subCategories = $this->categoryModel->all([
                'where' => ['parent_id' => $category['id'], 'is_active' => 1],
                'order' => 'sort_order ASC'
            ]);

            if (empty($subCategories)) {
                $showType = 'companies';
                $displayItems = $companyModel->all([
                    'where' => [
                        'category_id' => $category['id'],
                        'city_id'     => $city['id'],
                        'is_active'   => 1
                    ],
                    'order' => 'sort_order ASC'
                ]);
                $baseUrl = "/{$countrySlug}/cities/{$citySlug}/{$categoriesPath}/";
            } else {
                $displayItems = $subCategories;
                $baseUrl = "/{$countrySlug}/cities/{$citySlug}/{$categoriesPath}/";
            }
        } else {
            $displayItems = $this->categoryModel->all([
                'where' => ['parent_id' => null, 'is_active' => 1],
                'order' => 'sort_order ASC'
            ]);
        }

        View::render('categories/show', [
            'title'        => ($category ? $category['name'] : 'Категории') . ' - ' . $city['name'],
            'country'      => $country,
            'city'         => $city,
            'category'     => $category,
            'items'        => $displayItems,
            'showType'     => $showType,
            'base_url'     => $baseUrl,
            'categoryPath' => $categoryPathArr
        ]);
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
        $this->render('admin/categories/form', [
            'title'      => 'Нова категория',
            'categories' => $this->categoryModel->getTree(),
            'category'   => null,
            'layout'     => 'admin'
        ]);
    }

    public function store()
    {
        $this->handleStore($this->categoryModel, $this->baseRoute, ['image_url', 'companies_background_url'], 'categories');
    }

    public function edit(int $id)
    {
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
        $this->handleUpdate($this->categoryModel, $id, $this->baseRoute, ['image_url', 'companies_background_url'], 'categories');
    }

    public function delete(int $id)
    {
        $this->handleDelete($this->categoryModel, $id, $this->baseRoute, ['image_url', 'companies_background_url']);
    }

    public function updateOrder()
    {
        $this->handleOrderUpdate($this->categoryModel);
    }
}