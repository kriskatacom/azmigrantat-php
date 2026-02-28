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

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
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

        <div class="mt-5">
            <?php View::component('gallery-upload', 'admin/components', [
                'name'   => 'additional_images',
                'label'  => 'Допълнителна галерия',
                'images' => !empty($embassy['additional_images']) ? json_decode($embassy['additional_images'], true) : []
            ]); ?>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Визуални елементи', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на институцията</label>
                <input type="text" name="name" id="embassy-name" value="<?= htmlspecialchars($embassy['name'] ?? '') ?>" required
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

        <div class="mt-4">
            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $embassy['slug'] ?? '',
                'source' => 'embassy-name'
            ]); ?>
        </div>

        <div class="space-y-2 mt-4">
            <label class="text-sm font-semibold text-gray-600">Заглавие (H1)</label>
            <input type="text" name="heading" value="<?= htmlspecialchars($embassy['heading'] ?? '') ?>" class="form-control">
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Обща информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Телефон</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($embassy['phone'] ?? '') ?>" class="form-control">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($embassy['email'] ?? '') ?>" class="form-control">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Уебсайт</label>
                <input type="url" name="website_link" value="<?= htmlspecialchars($embassy['website_link'] ?? '') ?>" placeholder="https://..." class="form-control">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Адрес</label>
                <input type="text" name="address" value="<?= htmlspecialchars($embassy['address'] ?? '') ?>" class="form-control">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Спешен телефон</label>
                <input type="text" name="emergency_phone" value="<?= htmlspecialchars($embassy['emergency_phone'] ?? '') ?>" class="form-control">
            </div>
        </div>

        <div class="mt-5">
            <?php View::component('maps-input', 'admin/components', [
                'name'  => 'google_map',
                'label' => 'Google Map (Iframe или Координати)',
                'value' => $embassy['google_map'] ?? ''
            ]); ?>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Контакти и Локация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="space-y-6">
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
    <?php View::component('card', 'admin/components', ['title' => 'Подробно описание', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $embassy['is_active'] ?? true
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Настройки на видимост', 'slot' => ob_get_clean()]); ?>

    <div class="pt-5">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $embassy['is_active'] ?? true
        ]); ?>
    </div>

</form>