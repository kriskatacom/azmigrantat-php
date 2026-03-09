<?php

use App\Core\View;

$isEdit = isset($airport);
$action = $isEdit ? "/admin/airports/update/{$airport['id']}" : "/admin/airports/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Летища', 'url' => '/admin/airports'],
            ['label' => $isEdit ? 'Редактиране' : 'Ново летище']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-6">

    <?php ob_start(); ?>
    <div class="max-w-xl">
        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'image_url',
            'label' => 'Снимка на летището',
            'value' => $airport['image_url'] ?? null,
            'id'    => 'airport-image'
        ]); ?>
    </div>
    <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Визуализация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="font-semibold text-gray-600">Име на летището</label>
            <input type="text" name="name" id="airport-name" value="<?= htmlspecialchars($airport['name'] ?? '') ?>" required
                placeholder="напр. Летище София" class="form-control">
        </div>
        
        <?php View::component('select-dropdown', 'admin/components', [
            'name'       => 'country_id',
            'label'      => 'Държава',
            'placeholder'      => '-- Изберете държава --',
            'options'    => $countries,
            'selectedId' => $airport['country_id'] ?? null,
        ]); ?>
    </div>

    <div class="mt-4">
        <?php View::component('slug-input', 'admin/components', [
            'name'   => 'slug',
            'value'  => $airport['slug'] ?? '',
            'source' => 'airport-name'
        ]); ?>
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <?php View::component('maps-input', 'admin/components', [
        'name'     => 'location_link',
        'label'    => 'Google Maps Локация',
        'value'    => $airport['location_link'] ?? '',
        'latValue' => $airport['latitude'] ?? '',
        'lngValue' => $airport['longitude'] ?? ''
    ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Географска локация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <?php View::component('editor', 'admin/components', [
        'name'  => 'description',
        'label' => 'Допълнителна информация за летището',
        'value' => $airport['description'] ?? ''
    ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Описание', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <?php View::component('toggle', 'admin/components', [
        'name'  => 'is_active',
        'label' => 'Показвай в сайта',
        'value' => $airport['is_active'] ?? true
    ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Статус', 'slot' => ob_get_clean()]); ?>

    <div class="mb-3 pt-2">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $airport['is_active'] ?? true,
        ]); ?>
    </div>
</form>