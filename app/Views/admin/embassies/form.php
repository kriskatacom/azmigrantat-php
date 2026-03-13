<?php

use App\Core\View;

$item = $embassy ?? [];
$entityName = 'embassy';
$baseRoute = '/admin/embassies';
$isEdit = isset($item['id']);

$translatableFields = [
    'name'         => ['label' => 'Име на институцията', 'type' => 'text'],
    'heading'      => ['label' => 'Заглавие (H1)', 'type' => 'text'],
    'address'      => ['label' => 'Адрес', 'type' => 'text'],
    'working_time' => ['label' => 'Работно време', 'type' => 'editor'],
    'content'      => ['label' => 'Основно съдържание', 'type' => 'editor']
];

$action = $isEdit ? "{$baseRoute}/update/{$item['id']}" : "{$baseRoute}/store";

$embassyData = [
    'id' => (int)($item['id'] ?? 0),
    'entity' => $entityName,
    'fields' => array_keys($translatableFields),
    'translations' => $item['translations'] ?? (object)[]
];
?>

<script>
    window.embassyFormData = <?= json_encode($embassyData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<div
    x-data="translatableForm(window.embassyFormData)"
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
                    ['label' => 'Посолства', 'url' => $baseRoute],
                    ['label' => $isEdit ? 'Редактиране' : 'Ново посолство']
                ]
            ]); ?>
        </div>

        <div class="flex items-center gap-3">
            <?php if ($isEdit): ?>
                <div class="flex items-center bg-white shadow-sm border border-slate-200 rounded-xl p-1">
                    <a href="<?= (isset($prevId) && $prevId) ? $baseRoute . '/edit/' . $prevId : '#' ?>"
                        class="flex items-center justify-center w-9 h-9 rounded-lg transition-all <?= (isset($prevId) && $prevId) ? 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' : 'text-slate-200 cursor-not-allowed' ?>">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    <div class="w-px h-4 bg-slate-200 mx-1"></div>
                    <a href="<?= (isset($nextId) && $nextId) ? $baseRoute . '/edit/' . $nextId : '#' ?>"
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <?php ob_start(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Име на институцията</label>
                        <input type="text" name="name" id="embassy-name" value="<?= htmlspecialchars($item['name'] ?? '') ?>" required
                            placeholder="напр. Посолство на България" class="form-control">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Държава</label>
                        <select name="country_id" required class="form-control bg-white shadow-none border-slate-200">
                            <option value="">-- Изберете държава --</option>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?= $country['id'] ?>" <?= (isset($item['country_id']) && $item['country_id'] == $country['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($country['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <?php View::component('slug-input', 'admin/components', [
                        'name'   => 'slug',
                        'value'  => $item['slug'] ?? '',
                        'source' => 'embassy-name'
                    ]); ?>
                </div>

                <div class="space-y-2 mt-4">
                    <label class="text-sm font-semibold text-gray-600">Заглавие (H1)</label>
                    <input type="text" name="heading" value="<?= htmlspecialchars($item['heading'] ?? '') ?>"
                        placeholder="Заглавие, което ще се вижда в сайта" class="form-control">
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Обща информация', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Телефон</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($item['phone'] ?? '') ?>" class="form-control">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($item['email'] ?? '') ?>" class="form-control">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Уебсайт</label>
                        <input type="url" name="website_link" value="<?= htmlspecialchars($item['website_link'] ?? '') ?>" placeholder="https://..." class="form-control">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Адрес</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($item['address'] ?? '') ?>" class="form-control">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Спешен телефон</label>
                        <input type="text" name="emergency_phone" value="<?= htmlspecialchars($item['emergency_phone'] ?? '') ?>" class="form-control">
                    </div>
                </div>

                <div class="mt-5">
                    <?php View::component('maps-input', 'admin/components', [
                        'name'  => 'google_map',
                        'label' => 'Google Map (Iframe или Координати)',
                        'value' => $item['google_map'] ?? ''
                    ]); ?>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Контакти и Локация', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <div class="space-y-6">
                    <?php View::component('editor', 'admin/components', [
                        'name'  => 'working_time',
                        'label' => 'Работно време',
                        'value' => $item['working_time'] ?? ''
                    ]); ?>

                    <?php View::component('editor', 'admin/components', [
                        'name'  => 'content',
                        'label' => 'Основно съдържание',
                        'value' => $item['content'] ?? ''
                    ]); ?>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Подробно описание', 'slot' => ob_get_clean()]); ?>
            </div>

            <div class="space-y-6">
                <?php ob_start(); ?>
                <div class="space-y-5">
                    <?php View::component('image-upload', 'admin/components', [
                        'name'  => 'logo',
                        'label' => 'Знаме',
                        'value' => $item['logo'] ?? null,
                        'id'    => 'embassy-logo'
                    ]); ?>

                    <?php View::component('image-upload', 'admin/components', [
                        'name'  => 'right_heading_image',
                        'label' => 'Герб',
                        'value' => $item['right_heading_image'] ?? null,
                        'id'    => 'embassy-right-heading-image'
                    ]); ?>

                    <?php View::component('image-upload', 'admin/components', [
                        'name'  => 'image_url',
                        'label' => 'Основна снимка (Сграда)',
                        'value' => $item['image_url'] ?? null,
                        'id'    => 'embassy-main-image'
                    ]); ?>
                </div>
                <div class="mt-5 pt-5 border-t border-slate-100">
                    <?php View::component('gallery-upload', 'admin/components', [
                        'name'   => 'additional_images',
                        'label'  => 'Допълнителна галерия',
                        'images' => !empty($item['additional_images']) ? json_decode($item['additional_images'], true) : []
                    ]); ?>
                </div>
                <?php View::component('lightbox', 'admin/components'); ?>
                <?php View::component('card', 'admin/components', ['title' => 'Визуални елементи', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <?php View::component('toggle', 'admin/components', [
                    'name'  => 'is_active',
                    'label' => 'Показвай в сайта',
                    'value' => $item['is_active'] ?? true
                ]); ?>
                <div class="mt-5 pt-5 border-t border-slate-100">
                    <?php View::component('submit-button', 'admin/components', [
                        'text' => !$isEdit ? 'Създаване' : 'Запазване',
                        'is_active' => $item['is_active'] ?? true
                    ]); ?>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Статус и запис', 'slot' => ob_get_clean()]); ?>
            </div>
        </div>
    </form>
</div>
