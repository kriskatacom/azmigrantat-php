<?php

use App\Core\View;

$isEdit = isset($banner);
$action = $isEdit ? "/admin/banners/update/{$banner['id']}" : "/admin/banners/store";

$positions = [
    'center_center' => 'Център',
    'top_left' => 'Горе вляво',
    'top_center' => 'Горе център',
    'top_right' => 'Горе вдясно',
    'center_left' => 'Център вляво',
    'center_right' => 'Център вдясно',
    'bottom_left' => 'Долу вляво',
    'bottom_center' => 'Долу център',
    'bottom_right' => 'Долу вдясно'
];
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Банери', 'url' => '/admin/banners'],
            ['label' => $isEdit ? 'Редактиране' : 'Нов банер']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-green-500 rounded-full"></span> Визуализация
                </h3>

                <?php View::component('image-upload', 'admin/components', [
                    'name' => 'image_url',
                    'value' => $banner['image_url'] ?? null,
                    'label' => 'Фоново изображение'
                ]); ?>

                <?php View::component('lightbox', 'admin/components'); ?>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-blue-500 rounded-full"></span> Основна информация
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Заглавие на банера</label>
                        <input type="text" name="name" value="<?= $banner['name'] ?? '' ?>" class="form-control">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Описание (подзаглавие)</label>
                        <textarea name="description" rows="3" class="form-control"><?= $banner['description'] ?? '' ?></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Текст на бутона</label>
                            <input type="text" name="button_text" value="<?= $banner['button_text'] ?? 'Научи повече' ?>" class="form-control">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Линк (URL)</label>
                            <input type="text" name="href" value="<?= $banner['href'] ?? '' ?>" placeholder="https://..." class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4">Настройки</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Група (Group Key)</label>
                        <select name="group_key" class="form-control">
                            <option value="HOME_ELEMENTS" <?= ($banner['group_key'] ?? '') == 'HOME_ELEMENTS' ? 'selected' : '' ?>>HOME_ELEMENTS</option>
                            <option value="" <?= ($banner['group_key'] ?? '') == '' ? 'selected' : '' ?>>Без група</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Позиция на съдържанието</label>
                        <select name="content_place" class="form-control">
                            <?php foreach ($positions as $key => $label): ?>
                                <option value="<?= $key ?>" <?= ($banner['content_place'] ?? 'center_center') == $key ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Височина (px)</label>
                        <input type="number" name="height" value="<?= $banner['height'] ?? 520 ?>"
                            class="form-control">
                    </div>
                </div>
            </div>

            <div class="bg-[#1e293b] p-6 rounded-2xl shadow-sm text-white">
                <h3 class="font-bold mb-4 flex items-center gap-2">Показване на:</h3>

                <div class="space-y-3">
                    <?php
                    $toggles = [
                        'show_name' => 'Заглавие',
                        'show_description' => 'Описание',
                        'show_overlay' => 'Тъмен овърлей',
                        'show_button' => 'Бутон'
                    ];
                    foreach ($toggles as $field => $label):
                        $checked = ($banner[$field] ?? 1) == 1 ? 'checked' : '';
                    ?>
                        <label class="flex items-center justify-between cursor-pointer group">
                            <span class="text-sm text-gray-300 group-hover:text-white transition"><?= $label ?></span>
                            <div class="relative">
                                <input type="checkbox" name="<?= $field ?>" class="sr-only peer" <?= $checked ?>>
                                <div class="w-11 h-6 bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $banner['is_active'] ?? true
        ]); ?>
    </div>

    <?php View::component('submit-button', 'admin/components', [
        'text' => !$isEdit ? 'Създаване' : 'Запазване',
        'is_active' => $banner['is_active']
    ]); ?>
</form>