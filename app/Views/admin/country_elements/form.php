<?php

use App\Core\View;

$isEdit = isset($element);
// Определяме country_id от елемента или от URL параметъра
$c_id = $element['country_id'] ?? ($_GET['country_id'] ?? $_GET['country_id'] ?? null);

$action = $isEdit ? "/admin/countries/country-elements/update/{$element['id']}" : "/admin/countries/country-elements/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Държави', 'url' => '/admin/countries'],
            ['label' => 'Елементи', 'url' => "/admin/countries/country-elements?country_id={$c_id}"],
            ['label' => $isEdit ? 'Редактиране на елемент' : 'Нов елемент към държава']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <input type="hidden" name="country_id" value="<?= $c_id ?>">

    <?php ob_start(); ?>
    <div class="max-w-xl">
        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'image_url',
            'label' => 'Изображение на елемента',
            'value' => $element['image_url'] ?? null,
            'id'    => 'element-image'
        ]); ?>
    </div>
    <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Визия', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="space-y-2">
        <label class="text-sm font-semibold text-gray-600">Име на елемента</label>
        <input type="text" name="name" id="element-name-input"
            value="<?= htmlspecialchars($element['name'] ?? '') ?>"
            required class="form-control" placeholder="напр. Посолства">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
        <?php View::component('slug-input', 'admin/components', [
            'name'   => 'slug',
            'value'  => $element['slug'] ?? '',
            'source' => 'element-name-input'
        ]); ?>

        <?php View::component('select-dropdown', 'admin/components', [
            'name'       => 'country_id',
            'label'      => 'Категория',
            'options'    => $countries,
            'selectedId' => $element['country_id'] ?? null,
        ]); ?>
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <?php View::component('editor', 'admin/components', [
        'name'  => 'content',
        'label' => 'Подробно описание',
        'value' => $element['content'] ?? ''
    ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Съдържание', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <?php View::component('toggle', 'admin/components', [
        'name'  => 'is_active',
        'label' => 'Показвай в страницата на държавата',
        'value' => $element['is_active'] ?? true
    ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Настройки на видимост', 'slot' => ob_get_clean()]); ?>

    <div class="pt-2 mb-5">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създай елемент' : 'Запази промените',
            'is_active' => $element['is_active'] ?? true
        ]); ?>
    </div>

</form>
