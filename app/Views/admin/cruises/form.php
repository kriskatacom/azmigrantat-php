<?php

use App\Core\View;

$isEdit = isset($cruise);
$action = $isEdit ? "/admin/countries/update/{$cruise['id']}" : "/admin/countries/store";
// Забележка: Увери се, че в контролера маршрутите са правилни. 
// Ако ползваш CruiseController, екшънът трябва да е:
$action = $isEdit ? "/admin/cruises/update/{$cruise['id']}" : "/admin/cruises/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Круизи', 'url' => '/admin/cruises'],
            ['label' => $isEdit ? 'Редактиране' : 'Нова компания']
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
        <a href="/admin/cruises" class="text-sm text-gray-500 hover:text-primary-dark">← Назад</a>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на круизната компания</label>
                <input type="text" name="name" id="cruise-name" value="<?= $cruise['name'] ?? '' ?>" required placeholder="напр. MSC Cruises" class="form-control">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
                <input type="url" name="website_url" value="<?= $cruise['website_url'] ?? '' ?>" placeholder="https://www.msccruises.com" class="form-control">
            </div>
        </div>

        <?php View::component('slug-input', 'admin/components', [
            'name'   => 'slug',
            'value'  => $cruise['slug'] ?? '',
            'source' => 'cruise-name'
        ]); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <div class="space-y-2">
                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'image_url',
                    'label' => 'Лого на компанията',
                    'value' => $cruise['image_url'] ?? null,
                    'id'    => 'cruise-logo'
                ]); ?>
                <p class="text-xs text-gray-400 italic mt-1 uppercase tracking-tight">Препоръчителен формат: PNG или WEBP с прозрачен фон.</p>
            </div>
        </div>

        <?php View::component('submit-button', 'admin/components', ['text' => !$isEdit ? 'Създаване' : 'Запазване']); ?>

        <?php if ($isEdit): ?>
            <input type="hidden" name="return_url" value="/admin/cruises">
        <?php endif; ?>
    </form>
</div>

<?php View::component('lightbox', 'admin/components'); ?>