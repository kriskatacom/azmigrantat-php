<?php
use App\Core\View;

$isEdit = isset($country);
$action = $isEdit ? "/admin/countries/update/{$country['id']}" : "/admin/countries/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Държави', 'url' => '/admin/countries'],
            ['label' => $isEdit ? 'Редактиране' : 'Нова държава']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
        <div class="max-w-xl">
            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'image_url',
                'label' => 'Основно изображение',
                'value' => $country['image_url'] ?? null,
                'id'    => 'country-image'
            ]); ?>
        </div>
        <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Визия', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на държавата</label>
                <input type="text" name="name" id="country-name-input" 
                    value="<?= htmlspecialchars($country['name'] ?? '') ?>" 
                    required class="form-control">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Заглавие на страницата (H1)</label>
                <input type="text" name="heading" 
                    value="<?= htmlspecialchars($country['heading'] ?? '') ?>" 
                    placeholder="напр. Добре дошли в България" class="form-control">
            </div>
        </div>

        <div class="mt-4">
            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $country['slug'] ?? '',
                'source' => 'country-name-input'
            ]); ?>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('editor', 'admin/components', [
            'name'  => 'excerpt',
            'label' => 'Описание / Текст за държавата',
            'value' => $country['excerpt'] ?? ''
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Съдържание', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $country['is_active'] ?? true
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Настройки на видимост', 'slot' => ob_get_clean()]); ?>

    <div class="mb-5 pt-2">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $country['is_active'] ?? true
        ]); ?>
    </div>

</form>