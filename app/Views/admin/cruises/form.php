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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('form-header', 'admin/components', [
        'title' => $title,
        'back_url' => $baseRoute
    ]); ?>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <div class="space-y-2">
                <?php View::component('image-upload', 'admin/components', [
                    'name'   => 'image_url',
                    'label'  => 'Изображение',
                    'value'  => $cruise['image_url'] ?? null,
                    'id'     => 'cruise-logo'
                ]); ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на круизната компания</label>
                <input type="text" name="name" id="cruise-name"
                    value="<?= $cruise['name'] ?? '' ?>"
                    required placeholder="напр. MSC Cruises" class="form-control">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
                <input type="url" name="website_url"
                    value="<?= $cruise['website_url'] ?? '' ?>"
                    placeholder="https://www.msccruises.com" class="form-control">
            </div>
        </div>

        <?php View::component('slug-input', 'admin/components', [
            'name'   => 'slug',
            'value'  => $cruise['slug'] ?? '',
            'source' => 'cruise-name'
        ]); ?>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $cruise['is_active'] ?? true
        ]); ?>

        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $cruise['is_active']
        ]); ?>
    </form>
</div>

<?php View::component('lightbox', 'admin/components'); ?>
