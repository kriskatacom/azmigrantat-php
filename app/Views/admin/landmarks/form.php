<?php

use App\Core\View;

$item = $landmark ?? [];
$entityName = 'landmark';
$baseRoute = '/admin/landmarks';
$isEdit = isset($item['id']);

/**
 * Дефинираме полетата за превод.
 * Тук включваме име, подзаглавие, адрес, такса и трите редактора.
 */
$translatableFields = [
    'name'         => ['label' => 'Име на забележителността', 'type' => 'text'],
    'heading'      => ['label' => 'Подзаглавие (Heading)', 'type' => 'text'],
    'address'      => ['label' => 'Адрес', 'type' => 'text'],
    'ticket_tax'   => ['label' => 'Такса за билет', 'type' => 'text'],
    'excerpt'      => ['label' => 'Кратко описание', 'type' => 'editor'],
    'content'      => ['label' => 'Пълно описание', 'type' => 'editor'],
    'working_time' => ['label' => 'Работно време', 'type' => 'editor']
];

$action = $isEdit ? "{$baseRoute}/update/{$item['id']}" : "{$baseRoute}/store";

$landmarkData = [
    'id' => (int)($item['id'] ?? 0),
    'entity' => $entityName,
    'fields' => array_keys($translatableFields),
    'translations' => $item['translations'] ?? (object)[]
];
?>

<script>
    window.landmarkFormData = <?= json_encode($landmarkData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<div
    x-data="translatableForm(window.landmarkFormData)"
    x-init="init()"
    @keydown.window.ctrl.arrow-left="<?php if (isset($prevId) && $prevId): ?> window.location.href = '<?= $baseRoute ?>/edit/<?= $prevId ?>?live=1' <?php endif; ?>"
    @keydown.window.ctrl.arrow-right="<?php if (isset($nextId) && $nextId): ?> window.location.href = '<?= $baseRoute ?>/edit/<?= $nextId ?>?live=1' <?php endif; ?>">

    <?php if ($isEdit): ?>
        <?php View::component('translation-modal', 'admin/components', [
            'languages'     => $languages,
            'fields'        => $translatableFields,
            'nextId'        => $nextId ?? null,
            'saveEndpoint'  => '/admin/translations/confirm',
            'redirectBase'  => "{$baseRoute}/edit",
            'entityType'    => $entityName
        ]); ?>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
        <div>
            <?php View::component('breadcrumbs', 'admin/components', [
                'items' => [
                    ['label' => 'Забележителности', 'url' => $baseRoute],
                    ['label' => $isEdit ? 'Редактиране' : 'Нова забележителност']
                ]
            ]); ?>
        </div>

        <div class="flex items-center gap-3">
            <?php if ($isEdit): ?>
                <div class="flex items-center bg-white shadow-sm border border-slate-200 rounded-xl p-1">
                    <a href="<?= (isset($prevId) && $prevId) ? "{$baseRoute}/edit/{$prevId}" : '#' ?>"
                        class="flex items-center justify-center w-9 h-9 rounded-lg transition-all <?= (isset($prevId) && $prevId) ? 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' : 'text-slate-200 cursor-not-allowed' ?>">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    <div class="w-px h-4 bg-slate-200 mx-1"></div>
                    <a href="<?= (isset($nextId) && $nextId) ? "{$baseRoute}/edit/{$nextId}" : '#' ?>"
                        class="flex items-center justify-center w-9 h-9 rounded-lg transition-all <?= (isset($nextId) && $nextId) ? 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' : 'text-slate-200 cursor-not-allowed' ?>">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>

                <button type="button" @click="openTranslation();"
                    class="flex items-center gap-2 bg-white border border-slate-200 hover:border-indigo-600 text-slate-600 hover:text-indigo-600 py-2.5 px-5 rounded-xl font-bold shadow-sm transition-all active:scale-95">
                    <i class="fa-solid fa-language"></i>
                    <span>Преводи</span>
                </button>

                <button type="button"
                    @click="
                        const url = new URL(window.location); 
                        url.searchParams.set('live', '1'); 
                        window.history.pushState({}, '', url); 
                        openTranslation(); 
                        $nextTick(() => { if (!loading) magicTranslate(); });
                    "
                    class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 px-6 rounded-xl font-bold shadow-lg shadow-indigo-200 transition-all active:scale-95">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>AI Авто-превод</span>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div class="space-y-6">
            <?php ob_start(); ?>
            <div class="space-y-5">
                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'image_url',
                    'label' => 'Основно изображение',
                    'value' => $item['image_url'] ?? null,
                    'id'    => 'landmark-image'
                ]); ?>

                <?php View::component('lightbox', 'admin/components'); ?>

                <div class="pt-5 border-t border-slate-100">
                    <?php
                    $gallery = !empty($item['additional_images']) ? json_decode($item['additional_images'], true) : [];
                    View::component('multi-image-upload', 'admin/components', [
                        'name'   => 'additional_images[]',
                        'label'  => 'Фотогалерия на обекта',
                        'images' => $gallery
                    ]);
                    ?>
                </div>
            </div>
            <?php View::component('card', 'admin/components', ['title' => 'Медия', 'slot' => ob_get_clean()]); ?>

            <?php ob_start(); ?>
            <?php View::component('toggle', 'admin/components', [
                'name'  => 'is_active',
                'label' => 'Показвай в сайта',
                'value' => $item['is_active'] ?? true,
            ]); ?>
            <div class="mt-5 pt-5 border-t border-slate-100">
                <?php View::component('submit-button', 'admin/components', [
                    'text' => !$isEdit ? 'Създаване' : 'Запазване',
                    'is_active' => $item['is_active'] ?? true
                ]); ?>
            </div>
            <?php View::component('card', 'admin/components', ['title' => 'Статус', 'slot' => ob_get_clean()]); ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <?php ob_start(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Име на забележителността</label>
                        <input type="text" name="name" id="landmark-name" value="<?= htmlspecialchars($item['name'] ?? '') ?>" required
                            class="form-control">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Подзаглавие (Heading)</label>
                        <input type="text" name="heading" value="<?= htmlspecialchars($item['heading'] ?? '') ?>"
                            placeholder="напр. Перлата на Родопите" class="form-control">
                    </div>
                </div>

                <div class="space-y-2 mt-4">
                    <label class="text-sm font-semibold text-gray-600">Държава</label>
                    <select name="country_id" required class="form-control bg-white">
                        <option value="">Изберете държава...</option>
                        <?php foreach ($countries as $country): ?>
                            <option value="<?= $country['id'] ?>" <?= (isset($item['country_id']) && $item['country_id'] == $country['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($country['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mt-4">
                    <?php View::component('slug-input', 'admin/components', [
                        'name'   => 'slug',
                        'value'  => $item['slug'] ?? '',
                        'source' => 'landmark-name'
                    ]); ?>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <div class="space-y-6">
                    <?php View::component('editor', 'admin/components', [
                        'name'  => 'excerpt',
                        'label' => 'Кратко описание (Excerpt)',
                        'value' => $item['excerpt'] ?? null,
                    ]); ?>

                    <?php View::component('editor', 'admin/components', [
                        'name'  => 'content',
                        'label' => 'Пълно описание на обекта',
                        'value' => $item['content'] ?? null,
                    ]); ?>

                    <?php View::component('editor', 'admin/components', [
                        'name'  => 'working_time',
                        'label' => 'Работно време',
                        'value' => $item['working_time'] ?? null,
                    ]); ?>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Съдържание', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Адрес</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($item['address'] ?? '') ?>" placeholder="ул. Примерна 123..." class="form-control">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Телефон</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($item['phone'] ?? '') ?>" placeholder="+359..." class="form-control">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Уебсайт</label>
                        <input type="text" name="website_link" value="<?= htmlspecialchars($item['website_link'] ?? '') ?>" placeholder="https://..." class="form-control">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Такса за билет</label>
                        <input type="text" name="ticket_tax" value="<?= htmlspecialchars($item['ticket_tax'] ?? '') ?>" placeholder="напр. 10 лв." class="form-control">
                    </div>
                </div>

                <div class="mt-5">
                    <?php View::component('maps-input', 'admin/components', [
                        'name'  => 'google_map',
                        'label' => 'Локация на обекта',
                        'value' => $item['google_map'] ?? ''
                    ]); ?>
                </div>

                <div class="space-y-2 mt-5 pt-5 border-t border-slate-100">
                    <label class="text-sm font-semibold text-gray-600">Линк за навигация</label>
                    <input type="text" name="your_location" value="<?= htmlspecialchars($item['your_location'] ?? '') ?>" placeholder="Google Maps линк..." class="form-control">
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Локация и Контакти', 'slot' => ob_get_clean()]); ?>
            </div>
        </div>
    </form>
</div>