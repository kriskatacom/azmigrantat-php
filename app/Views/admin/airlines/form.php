<?php

use App\Core\View;

$isEdit = isset($airline);
$action = $isEdit ? "/admin/airlines/update/{$airline['id']}" : "/admin/airlines/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Авиолинии', 'url' => '/admin/airlines'],
            ['label' => $isEdit ? 'Редактиране' : 'Нова авиолиния']
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
        <a href="/admin/airlines" class="text-sm text-gray-500 hover:text-primary-dark">← Назад</a>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-5 space-y-5">

        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'image_url',
            'label' => 'Изображение',
            'value' => $airline['image_url'] ?? null,
            'id'    => 'airline-logo'
        ]); ?>

        <?php View::component('lightbox', 'admin/components'); ?>

        <div class="grid md:grid-cols-2 gap-5">
            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на авиокомпанията</label>
                    <input type="text" name="name" id="airline-name" value="<?= $airline['name'] ?? '' ?>" required
                        placeholder="напр. Lufthansa"
                        class="form-control">
                </div>

                <?php View::component('slug-input', 'admin/components', [
                    'name'   => 'slug',
                    'value'  => $airline['slug'] ?? '',
                    'source' => 'airline-name'
                ]); ?>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
                    <input type="url" name="website_url" value="<?= $airline['website_url'] ?? '' ?>"
                        placeholder="https://www.lufthansa.com"
                        class="form-control">
                </div>
            </div>
        </div>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $airline['is_active'] ?? true
        ]); ?>

        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $airline['is_active']
        ]); ?>
    </form>
</div>