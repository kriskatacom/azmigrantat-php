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
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 space-y-5">
        <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
        <a href="/admin/categories" class="text-sm text-gray-500 hover:text-indigo-600">← Назад</a>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-indigo-500">Визуални елементи</h4>
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
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-indigo-500">Основни данни</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на категорията</label>
                    <input type="text" name="name" id="cat-name" value="<?= $category['name'] ?? '' ?>" required class="form-control">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Родителска категория</label>
                    <select name="parent_id" class="form-control bg-white">
                        <option value="">-- Основна категория --</option>
                        <?php foreach ($categories as $parent): ?>
                            <?php
                            // Скриваме текущата категория от избора, за да не стане родител сама на себе си
                            if ($isEdit && $parent['id'] == $category['id']) continue;
                            ?>

                            <option value="<?= $parent['id'] ?>" <?= (isset($category) && $category['parent_id'] == $parent['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($parent['name']) ?>
                            </option>

                            <?php if (!empty($parent['children'])): ?>
                                <?php foreach ($parent['children'] as $child): ?>
                                    <?php if ($isEdit && $child['id'] == $category['id']) continue; ?>
                                    <option value="<?= $child['id'] ?>" <?= (isset($category) && $category['parent_id'] == $child['id']) ? 'selected' : '' ?>>
                                        &nbsp;&nbsp;— <?= htmlspecialchars($child['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">H1 Заглавие (Heading)</label>
                <input type="text" name="heading" value="<?= $category['heading'] ?? '' ?>" class="form-control">
            </div>

            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $category['slug'] ?? '',
                'source' => 'cat-name'
            ]); ?>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-indigo-500">Съдържание и SEO</h4>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Кратко описание (Excerpt)</label>
                <textarea name="excerpt" rows="3" class="form-control"><?= $category['excerpt'] ?? '' ?></textarea>
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Пълно съдържание</label>
                <textarea name="content" id="editor" rows="10" class="form-control"><?= $category['content'] ?? '' ?></textarea>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 space-y-5">
        <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-indigo-500">Активност</h4>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $category['is_active'] ?? true
        ]); ?>
    </div>

    <?php View::component('submit-button', 'admin/components', [
        'text' => !$isEdit ? 'Създаване' : 'Запазване',
        'is_active' => $category['is_active']
    ]); ?>
</form>