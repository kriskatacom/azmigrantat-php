<?php
use App\Core\View;

$isEdit = isset($autobus);
$action = $isEdit ? "/admin/autobuses/update/{$autobus['id']}" : "/admin/autobuses/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Автобуси', 'url' => '/admin/autobuses'],
            ['label' => $isEdit ? 'Редактиране' : 'Нова компания']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
        <div class="max-w-xl">
            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'image_url',
                'label' => 'Лого / Транспортно средство',
                'value' => $autobus['image_url'] ?? null,
                'id'    => 'bus-logo'
            ]); ?>
        </div>
        <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Визуализация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на компанията</label>
                <input type="text" name="name" id="bus-name" 
                    value="<?= htmlspecialchars($autobus['name'] ?? '') ?>" required
                    placeholder="напр. Union Ivkoni"
                    class="form-control">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
                <input type="url" name="website_url" 
                    value="<?= htmlspecialchars($autobus['website_url'] ?? '') ?>"
                    placeholder="https://www.example.com"
                    class="form-control">
            </div>
        </div>

        <div class="mt-4">
            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $autobus['slug'] ?? '',
                'source' => 'bus-name'
            ]); ?>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Обща информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('location-selector', 'admin/components', [
            'countries'       => $countries,
            'cities'          => $cities ?? null,
            'selectedCountry' => $autobus['country_id'] ?? null,
            'selectedCity'    => $autobus['city_id'] ?? null
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Централно управление', 'slot' => ob_get_clean()]); ?>

    <div class="space-y-5">
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $autobus['is_active'] ?? true
        ]); ?>

        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $autobus['is_active'] ?? true
        ]); ?>
    </div>
</form>