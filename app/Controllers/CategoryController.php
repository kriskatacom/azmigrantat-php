<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Category;

class CategoryController extends BaseController
{
    protected Category $category;
    protected string $baseRoute = '/admin/categories';

    public function __construct()
    {
        $this->middleware('admin');
        $this->category = new Category();
    }

    public function index()
    {
        $parentId = isset($_GET['parent_id']) ? (int)$_GET['parent_id'] : null;
        $this->category->setFilterParent($parentId);
        $paginationData = $this->paginate($this->category);

        $path = $parentId ? $this->category->getBreadcrumbs($parentId) : [];

        $categories = $this->category->getByParentPaginated(
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
            'layout'    => 'admin'
        ]);
    }

    public function create()
    {
        $categoriesTree = $this->category->getTree();

        $this->render('admin/categories/form', [
            'title'      => 'Нова категория',
            'categories' => $categoriesTree,
            'category'   => null,
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->handleStore($this->category, $this->baseRoute, ['image_url', 'companies_background_url'], 'categories');
    }

    public function edit(int $id)
    {
        $category = $this->category->find($id);
        if (!$category) {
            $this->flash('error', 'Записът не е намерен.');
            $this->redirect($this->baseRoute);
        }

        $this->render('admin/categories/form', [
            'title'      => 'Редактиране: ' . $category['name'],
            'categories' => $this->category->getTree(),
            'category'   => $category,
            'layout'     => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->handleUpdate($this->category, $id, $this->baseRoute, ['image_url', 'companies_background_url'], 'categories');
    }

    public function delete(int $id)
    {
        $this->handleDelete($this->category,  $id,  $this->baseRoute,  ['image_url', 'companies_background_url']);
    }

    public function updateOrder()
    {
        $this->handleOrderUpdate($this->category);
    }
}