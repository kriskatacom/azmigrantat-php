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

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
        <a href="/admin/cities" class="text-sm text-gray-500 hover:text-primary-dark">← Назад към списъка</a>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <div class="space-y-2">
                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'image_url',
                    'label' => 'Снимка на града',
                    'value' => $city['image_url'] ?? null,
                    'id'    => 'city-image'
                ]); ?>
            </div>

            <?php View::component('lightbox', 'admin/components'); ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на града</label>
                <input type="text" name="name" id="city-name-input" value="<?= $city['name'] ?? '' ?>"
                    required placeholder="напр. Рим" class="form-control">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Държава</label>
                <select name="country_id" required class="form-control">
                    <option value="">-- Изберете държава --</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?= $country['id'] ?>"
                            <?= (isset($city['country_id']) && $city['country_id'] == $country['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($country['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">SEO Заглавие (H1)</label>
            <input type="text" name="heading" value="<?= $city['heading'] ?? '' ?>"
                placeholder="напр. Забележителности в Рим" class="form-control">
        </div>

        <?php View::component('slug-input', 'admin/components', [
            'name'   => 'slug',
            'value'  => $city['slug'] ?? '',
            'source' => 'city-name-input'
        ]); ?>

        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Кратко описание (Excerpt)</label>
            <textarea name="excerpt" rows="4" placeholder="Кратко представяне на града..."
                class="form-control"><?= $city['excerpt'] ?? '' ?></textarea>
        </div>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $city['is_active'] ?? true
        ]); ?>

        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $city['is_active']
        ]); ?>
    </form>
</div>
