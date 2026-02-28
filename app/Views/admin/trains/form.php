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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
        <a href="/admin/trains" class="text-sm text-gray-500 hover:text-primary-dark">← Назад</a>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-10">

        <div class="space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-primary-dark">Медия</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'image_url',
                    'label' => 'Лого / Изображение',
                    'value' => $train['image_url'] ?? null,
                    'id'    => 'train-logo'
                ]); ?>
            </div>
            <?php View::component('lightbox', 'admin/components'); ?>
        </div>

        <div class="space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-primary-dark">Обща информация</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на ЖП оператора</label>
                    <input type="text" name="name" id="train-name" value="<?= $train['name'] ?? '' ?>" required
                        placeholder="напр. БДЖ Пътнически превози" class="form-control">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
                    <input type="url" name="website_url" value="<?= $train['website_url'] ?? '' ?>"
                        placeholder="https://www.bdz.bg" class="form-control">
                </div>
            </div>

            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $train['slug'] ?? '',
                'source' => 'train-name'
            ]); ?>
        </div>

        <div class="space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-primary-dark">Централно управление</h4>
            <?php View::component('location-selector', 'admin/components', [
                'countries'       => $countries,
                'cities'          => $cities ?? null,
                'selectedCountry' => $train['country_id'] ?? null,
                'selectedCity'    => $train['city_id'] ?? null
            ]); ?>
        </div>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $train['is_active'] ?? true
        ]); ?>

        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $train['is_active'] ?? true
        ]); ?>
    </form>
</div>