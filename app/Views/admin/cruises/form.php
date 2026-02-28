<?php
use App\Core\View;

$isEdit = isset($cruise);
$baseRoute = '/admin/cruises';
$action = $isEdit ? "{$baseRoute}/update/{$cruise['id']}" : "{$baseRoute}/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Круизи', 'url' => $baseRoute],
            ['label' => $isEdit ? 'Редактиране' : 'Нова компания']
        ]
    ]); ?>
</div>

<?php View::component('form-header', 'admin/components', [
    'title' => $title,
    'back_url' => $baseRoute
]); ?>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
        <?php View::component('image-upload', 'admin/components', [
            'name'   => 'image_url',
            'label'  => 'Лого / Изображение на компанията',
            'value'  => $cruise['image_url'] ?? null,
            'id'     => 'cruise-logo'
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Изображение', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на круизната компания</label>
                <input type="text" name="name" id="cruise-name"
                    value="<?= htmlspecialchars($cruise['name'] ?? '') ?>"
                    required placeholder="напр. MSC Cruises" class="form-control">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
                <input type="url" name="website_url"
                    value="<?= htmlspecialchars($cruise['website_url'] ?? '') ?>"
                    placeholder="https://www.msccruises.com" class="form-control">
            </div>
        </div>

        <div class="mt-4">
            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $cruise['slug'] ?? '',
                'source' => 'cruise-name'
            ]); ?>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $cruise['is_active'] ?? true
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Статус и Видимост', 'slot' => ob_get_clean()]); ?>

    <div class="pt-2 mb-5">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $cruise['is_active'] ?? true,
        ]); ?>
    </div>
</form>

<?php View::component('lightbox', 'admin/components'); ?>
