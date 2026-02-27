<?php

use App\Core\View;

$isEdit = isset($embassy);
$action = $isEdit ? "/admin/embassies/update/{$embassy['id']}" : "/admin/embassies/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Посолства', 'url' => '/admin/embassies'],
            ['label' => $isEdit ? 'Редактиране' : 'Ново посолство']
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
        <a href="/admin/embassies" class="text-sm text-gray-500 hover:text-primary-dark">← Назад</a>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-10">
        <div class="space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2">Визуални елементи</h4>

            <div class="grid xl:grid-cols-2 2xl:grid-cols-3 gap-5">
                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'logo',
                    'label' => 'Знаме',
                    'value' => $embassy['logo'] ?? null,
                    'id'    => 'embassy-logo'
                ]); ?>

                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'right_heading_image',
                    'label' => 'Герб',
                    'value' => $embassy['right_heading_image'] ?? null,
                    'id'    => 'embassy-right-heading-image'
                ]); ?>

                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'image_url',
                    'label' => 'Основна снимка (Сграда)',
                    'value' => $embassy['image_url'] ?? null,
                    'id'    => 'embassy-main-image'
                ]); ?>

                <?php View::component('lightbox', 'admin/components'); ?>
            </div>

            <?php View::component('gallery-upload', 'admin/components', [
                'name'   => 'additional_images',
                'label'  => 'Допълнителна галерия',
                'images' => !empty($embassy['additional_images']) ? json_decode($embassy['additional_images'], true) : []
            ]); ?>
        </div>

        <div class="space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2">Обща информация</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на институцията</label>
                    <input type="text" name="name" id="embassy-name" value="<?= $embassy['name'] ?? '' ?>" required
                        placeholder="напр. Посолство на Република България"
                        class="form-control">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Държава</label>
                    <select name="country_id" required class="form-control bg-white">
                        <option value="">-- Изберете държава --</option>
                        <?php foreach ($countries as $country): ?>
                            <option value="<?= $country['id'] ?>" <?= (isset($embassy) && $embassy['country_id'] == $country['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($country['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $embassy['slug'] ?? '',
                'source' => 'embassy-name'
            ]); ?>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Заглавие (H1)</label>
                <input type="text" name="heading" value="<?= $embassy['heading'] ?? '' ?>"
                    class="form-control">
            </div>
        </div>

        <div class="space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2">Контакти и Локация</h4>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Телефон</label>
                    <input type="text" name="phone" value="<?= $embassy['phone'] ?? '' ?>" class="form-control">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Email</label>
                    <input type="email" name="email" value="<?= $embassy['email'] ?? '' ?>" class="form-control">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Уебсайт</label>
                    <input type="url" name="website_link" value="<?= $embassy['website_link'] ?? '' ?>" placeholder="https://..." class="form-control">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Адрес</label>
                    <input type="text" name="address" value="<?= $embassy['address'] ?? '' ?>" class="form-control">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Спешен телефон</label>
                    <input type="text" name="emergency_phone" value="<?= $embassy['emergency_phone'] ?? '' ?>" class="form-control">
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-5">
            <?php View::component('editor', 'admin/components', [
                'name'  => 'working_time',
                'label' => 'Работно време',
                'value' => $embassy['working_time'] ?? ''
            ]); ?>

            <?php View::component('editor', 'admin/components', [
                'name'  => 'content',
                'label' => 'Основно съдържание',
                'value' => $embassy['content'] ?? ''
            ]); ?>
        </div>

        <?php View::component('maps-input', 'admin/components', [
            'name'  => 'google_map',
            'label' => 'Google Map (Iframe или Координати)',
            'value' => $embassy['google_map'] ?? ''
        ]); ?>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $embassy['is_active'] ?? true
        ]); ?>

        <?php View::component('submit-button', 'admin/components', ['text' => !$isEdit ? 'Създаване' : 'Запазване']); ?>
    </form>
</div>
