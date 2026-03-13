<?php
use App\Core\View;

$item = $airport ?? [];
$entityName = 'airport';
$baseRoute = '/admin/airports';
$isEdit = isset($item['id']);

/**
 * Дефинираме транслируемите полета за летището.
 */
$translatableFields = [
    'name'        => ['label' => 'Име на летището', 'type' => 'text'],
    'description' => ['label' => 'Описание / Информация', 'type' => 'editor']
];

$action = $isEdit ? "{$baseRoute}/update/{$item['id']}" : "{$baseRoute}/store";

$airportData = [
    'id' => (int)($item['id'] ?? 0),
    'entity' => $entityName,
    'fields' => array_keys($translatableFields),
    'translations' => $item['translations'] ?? (object)[]
];
?>

<script>
    window.airportFormData = <?= json_encode($airportData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<div
    x-data="translatableForm(window.airportFormData)"
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
                    ['label' => 'Летища', 'url' => $baseRoute],
                    ['label' => $isEdit ? 'Редактиране' : 'Ново летище']
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <?php ob_start(); ?>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-semibold text-gray-600">Име на летището</label>
                        <input type="text" name="name" id="airport-name" value="<?= htmlspecialchars($item['name'] ?? '') ?>" required
                            placeholder="напр. Летище София" class="form-control">
                    </div>

                    <div class="space-y-2">
                        <label class="font-semibold text-gray-600">Уебсайт</label>
                        <input type="text" name="website_url" value="<?= htmlspecialchars($item['website_url'] ?? '') ?>" required
                            placeholder="напр. https://sofia-airport.eu" class="form-control">
                    </div>
                    
                    <?php View::component('select-dropdown', 'admin/components', [
                        'name'       => 'country_id',
                        'label'      => 'Държава',
                        'placeholder'      => '-- Изберете държава --',
                        'options'    => $countries,
                        'selectedId' => $item['country_id'] ?? null,
                    ]); ?>
                </div>

                <div class="mt-4">
                    <?php View::component('slug-input', 'admin/components', [
                        'name'   => 'slug',
                        'value'  => $item['slug'] ?? '',
                        'source' => 'airport-name'
                    ]); ?>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <?php View::component('editor', 'admin/components', [
                    'name'  => 'description',
                    'label' => 'Допълнителна информация за летището',
                    'value' => $item['description'] ?? ''
                ]); ?>
                <?php View::component('card', 'admin/components', ['title' => 'Описание', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <?php View::component('maps-input', 'admin/components', [
                    'name'     => 'location_link',
                    'label'    => 'Google Maps Локация',
                    'value'    => $item['location_link'] ?? '',
                    'latValue' => $item['latitude'] ?? '',
                    'lngValue' => $item['longitude'] ?? ''
                ]); ?>
                <?php View::component('card', 'admin/components', ['title' => 'Географска локация', 'slot' => ob_get_clean()]); ?>
            </div>

            <div class="space-y-6">
                <?php ob_start(); ?>
                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'image_url',
                    'label' => 'Снимка на летището',
                    'value' => $item['image_url'] ?? null,
                    'id'    => 'airport-image'
                ]); ?>
                <?php View::component('lightbox', 'admin/components'); ?>
                <?php View::component('card', 'admin/components', ['title' => 'Визия', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <?php View::component('toggle', 'admin/components', [
                    'name'  => 'is_active',
                    'label' => 'Показвай в сайта',
                    'value' => $item['is_active'] ?? true
                ]); ?>
                <div class="mt-5 pt-5 border-t border-slate-100">
                    <?php View::component('submit-button', 'admin/components', [
                        'text' => !$isEdit ? 'Създаване' : 'Запазване',
                        'is_active' => $item['is_active'] ?? true,
                    ]); ?>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Настройки', 'slot' => ob_get_clean()]); ?>
            </div>
        </div>
    </form>
</div>