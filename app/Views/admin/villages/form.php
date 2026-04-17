<?php

use App\Core\View;

$item = $village ?? [];
$entityName = 'village';
$baseRoute = '/admin/villages';
$isEdit = isset($item['id']);

// Дефинираме кои полета подлежат на превод (за модала и AI превода)
$translatableFields = [
    'title'       => ['label' => 'Име на селото', 'type' => 'text'],
    'heading'     => ['label' => 'Заглавие (H1)', 'type' => 'text'],
    'location'    => ['label' => 'Местоположение', 'type' => 'text'],
];

$action = $isEdit ? "{$baseRoute}/update/{$item['id']}" : "{$baseRoute}/store";

$villageData = [
    'id'           => (int)($item['id'] ?? 0),
    'entity'       => $entityName,
    'fields'       => array_keys($translatableFields),
    'translations' => $item['translations'] ?? (object)[]
];
?>

<script>
    window.villageFormData = <?= json_encode($villageData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<div x-data="translatableForm(window.villageFormData)" x-init="init()">

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
                    ['label' => 'Села', 'url' => $baseRoute],
                    ['label' => $isEdit ? 'Редактиране на ' . $item['title'] : 'Ново село']
                ]
            ]); ?>
        </div>

        <div class="flex items-center gap-3">
            <?php if ($isEdit): ?>
                <button type="button" @click="openTranslation();" class="btn-white">
                    <i class="fa-solid fa-language"></i> <span>Преводи</span>
                </button>
                <button type="button" @click="magicTranslate();" class="btn-indigo">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> <span>AI Авто-превод</span>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <?php ob_start(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на селото</label>
                    <input type="text" name="title" id="village-title-input" value="<?= htmlspecialchars($item['title'] ?? '') ?>" required class="form-control">
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Местоположение</label>
                    <input type="text" name="location" value="<?= htmlspecialchars($item['location'] ?? '') ?>" placeholder="напр. Родопи, Община Смолян" class="form-control">
                </div>
            </div>
            <div class="mt-4">
                <?php View::component('slug-input', 'admin/components', [
                    'name'   => 'slug',
                    'value'  => $item['slug'] ?? '',
                    'source' => 'village-title-input'
                ]); ?>
            </div>
            <div class="space-y-2 mt-4">
                <label class="text-sm font-semibold text-gray-600">SEO Заглавие (H1)</label>
                <input type="text" name="heading" value="<?= htmlspecialchars($item['heading'] ?? '') ?>" class="form-control">
            </div>
            <?php View::component('card', 'admin/components', ['title' => 'Обща информация', 'slot' => ob_get_clean()]); ?>

            <?php ob_start(); ?>
            <div x-data="{ 
                sections: <?= !empty($item['description_sections']) ? json_encode($item['description_sections'], JSON_UNESCAPED_UNICODE) : '{}' ?>,
                addSection() {
                    const title = prompt('Заглавие на секцията (напр. География, История):');
                    if(title) { this.sections[title] = ''; }
                },
                removeSection(key) {
                    if(confirm('Изтриване на секцията?')) { delete this.sections[key]; }
                }
            }">
                <template x-for="(content, title) in sections" :key="title">
                    <div class="mb-4 p-4 border border-gray-100 rounded-xl bg-gray-50/50 relative">
                        <button type="button" @click="removeSection(title)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                        <label class="block text-sm font-bold text-gray-700 mb-2" x-text="title"></label>
                        <input type="hidden" name="section_title[]" :value="title">
                        <textarea name="section_content[]" x-model="sections[title]" rows="4" class="form-control"></textarea>
                    </div>
                </template>
                <button type="button" @click="addSection()" class="w-full py-3 border-2 border-dashed border-gray-200 rounded-xl text-gray-500 hover:border-indigo-300 hover:text-indigo-600 transition-all font-medium">
                    + Добави нова секция (История, География...)
                </button>
            </div>
            <?php View::component('card', 'admin/components', ['title' => 'Описателни секции (JSON)', 'slot' => ob_get_clean()]); ?>

            <?php ob_start(); ?>
            <?php View::component('gallery-upload', 'admin/components', [
                'name'   => 'gallery_urls',
                'images' => $item['gallery_urls'] ?? []
            ]); ?>
            <?php View::component('card', 'admin/components', ['title' => 'Фото галерия', 'slot' => ob_get_clean()]); ?>
        </div>

        <div class="space-y-6">
            <?php ob_start(); ?>
            <div class="space-y-5">
                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'image_url',
                    'label' => 'Основна снимка',
                    'value' => $item['image_url'] ?? null
                ]); ?>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Google Maps (Embed Code)</label>
                    <textarea name="google_map" rows="3" class="form-control text-xs font-mono" placeholder="<iframe>...</iframe>"><?= htmlspecialchars($item['google_map'] ?? '') ?></textarea>
                </div>
            </div>
            <?php View::component('card', 'admin/components', ['title' => 'Медия и Карта', 'slot' => ob_get_clean()]); ?>

            <?php ob_start(); ?>
            <div class="space-y-4">
                <?php View::component('toggle', 'admin/components', [
                    'name'  => 'is_active',
                    'label' => 'Активно в сайта',
                    'value' => $item['is_active'] ?? true
                ]); ?>
                <div class="pt-4 border-t border-gray-100">
                    <?php View::component('submit-button', 'admin/components', [
                        'text' => $isEdit ? 'Обнови селото' : 'Запиши селото'
                    ]); ?>
                </div>
            </div>
            <?php View::component('card', 'admin/components', ['title' => 'Публикация', 'slot' => ob_get_clean()]); ?>
        </div>
    </form>
</div>