<?php
use App\Core\View;

$isEdit = isset($train);
$action = $isEdit ? "/admin/trains/update/{$train['id']}" : "/admin/trains/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Влакове', 'url' => '/admin/trains'],
            ['label' => $isEdit ? 'Редактиране' : 'Нова ЖП компания']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'image_url',
                'label' => 'Лого / Изображение на компанията',
                'value' => $train['image_url'] ?? null,
                'id'    => 'train-logo'
            ]); ?>
        </div>
        <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Медия', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на ЖП оператора</label>
                <input type="text" name="name" id="train-name" 
                    value="<?= htmlspecialchars($train['name'] ?? '') ?>" required
                    placeholder="напр. БДЖ Пътнически превози" class="form-control">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
                <input type="url" name="website_url" 
                    value="<?= htmlspecialchars($train['website_url'] ?? '') ?>"
                    placeholder="https://www.bdz.bg" class="form-control">
            </div>
        </div>

        <div class="mt-4">
            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $train['slug'] ?? '',
                'source' => 'train-name'
            ]); ?>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Обща информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('location-selector', 'admin/components', [
            'countries'       => $countries,
            'cities'          => $cities ?? null,
            'selectedCountry' => $train['country_id'] ?? null,
            'selectedCity'    => $train['city_id'] ?? null
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Централно управление', 'slot' => ob_get_clean()]); ?>

    <div class="space-y-5">
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $train['is_active'] ?? true
        ]); ?>

        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $train['is_active'] ?? true
        ]); ?>
    </div>
</form>
