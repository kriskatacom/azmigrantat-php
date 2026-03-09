<?php
use App\Core\View;

$isEdit = isset($city);
$action = $isEdit ? "/admin/cities/update/{$city['id']}" : "/admin/cities/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Градове', 'url' => '/admin/cities'],
            ['label' => $isEdit ? 'Редактиране' : 'Нов град']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'image_url',
                'label' => 'Снимка на града',
                'value' => $city['image_url'] ?? null,
                'id'    => 'city-image'
            ]); ?>
        </div>
        <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Визия', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на града</label>
                <input type="text" name="name" id="city-name-input" 
                    value="<?= htmlspecialchars($city['name'] ?? '') ?>"
                    required placeholder="напр. Рим" class="form-control">
            </div>

            <?php View::component('select-dropdown', 'admin/components', [
                'name'       => 'country_id',
                'label'      => 'Държава',
                'placeholder'      => '-- Изберете държава --',
                'options'    => $countries,
                'selectedId' => $city['country_id'] ?? null,
            ]); ?>
        </div>

        <div class="mt-4">
            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $city['slug'] ?? '',
                'source' => 'city-name-input'
            ]); ?>
        </div>

        <div class="space-y-2 mt-4">
            <label class="text-sm font-semibold text-gray-600">SEO Заглавие (H1)</label>
            <input type="text" name="heading" value="<?= htmlspecialchars($city['heading'] ?? '') ?>"
                placeholder="напр. Забележителности в Рим" class="form-control">
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Кратко описание (Excerpt)</label>
            <textarea name="excerpt" rows="4" placeholder="Кратко представяне на града..."
                class="form-control"><?= htmlspecialchars($city['excerpt'] ?? '') ?></textarea>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Описание', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $city['is_active'] ?? true
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Настройки на видимост', 'slot' => ob_get_clean()]); ?>

    <div class="mb-5 pt-2">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $city['is_active'] ?? true
        ]); ?>
    </div>

</form>