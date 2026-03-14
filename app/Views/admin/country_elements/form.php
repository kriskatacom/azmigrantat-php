<?php
use App\Core\View;

$item = $element ?? []; // Променливата тук е $element от контролера
$entityName = 'country_element';
$baseRoute = '/admin/countries/country-elements';
$isEdit = isset($item['id']);

// Дефинираме кои полета искаме да превеждаме за елементите
$translatableFields = [
    'name'    => ['label' => 'Име на елемента', 'type' => 'text'],
    'content' => ['label' => 'Подробно описание', 'type' => 'editor']
];

$action = $isEdit ? "{$baseRoute}/update/{$item['id']}" : "{$baseRoute}/store";
$c_id = $item['country_id'] ?? ($_GET['country_id'] ?? null);

$elementData = [
    'id' => (int)($item['id'] ?? 0),
    'entity' => $entityName,
    'fields' => array_keys($translatableFields),
    'translations' => $item['translations'] ?? (object)[] 
];
?>

<script>
    window.elementFormData = <?= json_encode($elementData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<div
    x-data="translatableForm(window.elementFormData)"
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
                    ['label' => 'Държави', 'url' => '/admin/countries'],
                    ['label' => 'Елементи', 'url' => "{$baseRoute}?country_id={$c_id}"],
                    ['label' => $isEdit ? 'Редактиране на елемент' : 'Нов елемент']
                ]
            ]); ?>
        </div>

        <div class="flex items-center gap-3">
            <?php if ($isEdit): ?>
                <template x-if="typeof stats !== 'undefined'">
                    <div class="hidden sm:flex items-center gap-3 px-4 py-2 bg-white border border-slate-200 rounded-2xl shadow-sm mr-2">
                        <div class="flex flex-col leading-none">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-tight">Локализация</span>
                            <span class="text-[11px] font-bold text-slate-700 mt-1">
                                <span x-text="stats.translated" class="text-indigo-600"></span> / <span x-text="stats.total"></span> езика
                            </span>
                        </div>
                    </div>
                </template>

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

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
        <input type="hidden" name="country_id" value="<?= $c_id ?>">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                <?php ob_start(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-600">Име на елемента</label>
                        <input type="text" name="name" id="element-name-input" 
                            value="<?= htmlspecialchars($item['name'] ?? '') ?>" 
                            required class="form-control" placeholder="напр. Посолства">
                    </div>
                    <div class="space-y-2">
                        <?php View::component('select-dropdown', 'admin/components', [
                            'name'       => 'country_id',
                            'label'      => 'Държава',
                            'options'    => $countries,
                            'selectedId' => $c_id,
                        ]); ?>
                    </div>
                </div>
                <div class="mt-4">
                    <?php View::component('slug-input', 'admin/components', [
                        'name'   => 'slug',
                        'value'  => $item['slug'] ?? '',
                        'source' => 'element-name-input'
                    ]); ?>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <?php View::component('editor', 'admin/components', [
                    'name'  => 'content',
                    'label' => 'Подробно описание на елемента',
                    'value' => $item['content'] ?? ''
                ]); ?>
                <?php View::component('card', 'admin/components', ['title' => 'Съдържание', 'slot' => ob_get_clean()]); ?>
            </div>

            <div class="space-y-6">
                <?php ob_start(); ?>
                <div class="max-w-xl mx-auto">
                    <?php View::component('image-upload', 'admin/components', [
                        'name'  => 'image_url',
                        'label' => 'Изображение на елемента',
                        'value' => $item['image_url'] ?? null,
                        'id'    => 'element-image'
                    ]); ?>
                </div>
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
                        'is_active' => $item['is_active'] ?? true
                    ]); ?>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Статус', 'slot' => ob_get_clean()]); ?>
            </div>

        </div>
    </form>
</div>