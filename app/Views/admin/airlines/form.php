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

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'image_url',
            'label' => 'Лого / Изображение на авиокомпанията',
            'value' => $airline['image_url'] ?? null,
            'id'    => 'airline-logo'
        ]); ?>
        
        <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Изображение', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="grid md:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на авиокомпанията</label>
                <input type="text" name="name" id="airline-name" 
                    value="<?= htmlspecialchars($airline['name'] ?? '') ?>" required
                    placeholder="напр. Lufthansa"
                    class="form-control">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
                <input type="url" name="website_url" 
                    value="<?= htmlspecialchars($airline['website_url'] ?? '') ?>"
                    placeholder="https://www.lufthansa.com"
                    class="form-control">
            </div>
        </div>

        <div class="mt-4">
            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $airline['slug'] ?? '',
                'source' => 'airline-name'
            ]); ?>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $airline['is_active'] ?? true
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Активност и Видимост', 'slot' => ob_get_clean()]); ?>

    <div class="mb-5 pt-2">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $airline['is_active'] ?? true
        ]); ?>
    </div>
</form>