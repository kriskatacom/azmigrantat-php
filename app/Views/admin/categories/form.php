<?php

use App\Core\View;

$isEdit = isset($category);
$action = $isEdit ? "/admin/categories/update/{$category['id']}" : "/admin/categories/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Категории', 'url' => '/admin/categories'],
            ['label' => $isEdit ? 'Редактиране' : 'Нова категория']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'image_url',
            'label' => 'Основна икона / Снимка',
            'value' => $category['image_url'] ?? null,
            'id'    => 'cat-main-img'
        ]); ?>

        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'companies_background_url',
            'label' => 'Фон за страницата с компании',
            'value' => $category['companies_background_url'] ?? null,
            'id'    => 'cat-bg-img'
        ]); ?>
    </div>
    <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Визуални елементи', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Име на категорията</label>
            <input type="text" name="name" id="cat-name" value="<?= htmlspecialchars($category['name'] ?? '') ?>" required class="form-control">
        </div>

        <?php View::component('category-select', 'admin/components', [
            'name'       => 'parent_id',
            'label'      => 'Родителска категория',
            'options'    => $categories,
            'selectedId' => $category['parent_id'] ?? null,
            'excludeId'  => $category['id'] ?? null,
        ]); ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">H1 Заглавие (Heading)</label>
            <input type="text" name="heading" value="<?= htmlspecialchars($category['heading'] ?? '') ?>" class="form-control">
        </div>

        <?php View::component('slug-input', 'admin/components', [
            'name'   => 'slug',
            'value'  => $category['slug'] ?? '',
            'source' => 'cat-name'
        ]); ?>
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Основни данни', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="space-y-4">
        <?php View::component('editor', 'admin/components', [
            'name'  => 'excerpt',
            'label' => 'Кратко описание (Excerpt)',
            'value' => $category['excerpt'] ?? ''
        ]); ?>

        <?php View::component('editor', 'admin/components', [
            'name'  => 'content',
            'label' => 'Пълно съдържание',
            'value' => $category['content'] ?? ''
        ]); ?>
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Съдържание и SEO', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <?php View::component('toggle', 'admin/components', [
        'name'  => 'is_active',
        'label' => 'Показвай в сайта',
        'value' => $category['is_active'] ?? true
    ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Статус', 'slot' => ob_get_clean()]); ?>

    <div class="mb-5 pt-2">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $category['is_active'] ?? true
        ]); ?>
    </div>
</form>
