<?php

use App\Core\View;

$isEdit = isset($country);
$action = $isEdit ? "/admin/countries/update/{$country['id']}" : "/admin/countries/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Държави', 'url' => '/admin/countries'],
            ['label' => isset($country) ? 'Редактиране' : 'Нова държава']
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
        <a href="/admin/countries" class="text-sm text-gray-500 hover:text-primary-dark">← Назад</a>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на държавата</label>
                <input type="text" name="name" id="country-name-input" value="<?= $country['name'] ?? '' ?>" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 ...">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Заглавие на страницата (H1)</label>
                <input type="text" name="heading" value="<?= $country['heading'] ?? '' ?>"
                    placeholder="напр. Добре дошли в България"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition">
            </div>
        </div>

        <?php View::component('slug-input', 'admin/components', [
            'name'   => 'slug',
            'value'  => $country['slug'] ?? '',
            'source' => 'name'
        ]); ?>

        <?php View::component('editor', 'admin/components', [
            'name'  => 'excerpt',
            'label' => 'Описание',
            'value' => $country['excerpt'] ?? ''
        ]); ?>

        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'image_url',
            'label' => 'Изображение на държавата',
            'value' => $country['image_url'] ?? null,
            'id'    => 'country-image'
        ]); ?>

        <?php View::component('lightbox', 'admin/components'); ?>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $country['is_active'] ?? true
        ]); ?>

        <div class="pt-5 border-t border-gray-100 flex gap-4">
            <button type="submit" class="bg-primary-dark text-white px-10 py-3 rounded-xl font-bold shadow-lg hover:bg-opacity-90 transition">
                Запазване
            </button>
        </div>
    </form>
</div>