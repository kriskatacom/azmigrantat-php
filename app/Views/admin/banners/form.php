<?php

use App\Core\View;

$item = $banner ?? [];
$entityName = 'banner';
$baseRoute = '/admin/banners';
$isEdit = isset($item['id']);

$positions = [
    'center_center' => 'Център',
    'top_left' => 'Горе вляво',
    'top_center' => 'Горе център',
    'top_right' => 'Горе вдясно',
    'center_left' => 'Център вляво',
    'center_right' => 'Център вдясно',
    'bottom_left' => 'Долу вляво',
    'bottom_center' => 'Долу център',
    'bottom_right' => 'Долу вдясно'
];

/**
 * Дефинираме полетата за превод.
 * Тук включваме името (заглавие), описанието и текста на бутона.
 */
$translatableFields = [
    'name'        => ['label' => 'Заглавие на банера', 'type' => 'text'],
    'description' => ['label' => 'Описание (подзаглавие)', 'type' => 'text'],
    'button_text' => ['label' => 'Текст на бутона', 'type' => 'text']
];

$action = $isEdit ? "{$baseRoute}/update/{$item['id']}" : "{$baseRoute}/store";

$bannerData = [
    'id' => (int)($item['id'] ?? 0),
    'entity' => $entityName,
    'fields' => array_keys($translatableFields),
    'translations' => $item['translations'] ?? (object)[]
];
?>

<script>
    window.bannerFormData = <?= json_encode($bannerData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<div
    x-data="translatableForm(window.bannerFormData)"
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
                    ['label' => 'Банери', 'url' => $baseRoute],
                    ['label' => $isEdit ? 'Редактиране' : 'Нов банер']
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
                <?php View::component('image-upload', 'admin/components', [
                    'name' => 'image_url',
                    'value' => $item['image_url'] ?? null,
                    'label' => 'Фоново изображение',
                    'id' => 'banner-bg'
                ]); ?>
                <?php View::component('lightbox', 'admin/components'); ?>
                <?php View::component('card', 'admin/components', ['title' => 'Визуализация', 'slot' => ob_get_clean()]); ?>

                <?php ob_start(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Заглавие на банера</label>
                        <input type="text" name="name" id="banner-name" value="<?= htmlspecialchars($item['name'] ?? '') ?>" class="form-control">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Описание (подзаглавие)</label>
                        <textarea name="description" rows="3" class="form-control"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Текст на бутона</label>
                            <input type="text" name="button_text" value="<?= htmlspecialchars($item['button_text'] ?? 'Научи повече') ?>" class="form-control">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Линк на бутона (URL)</label>
                            <input type="text" name="href" value="<?= htmlspecialchars($item['href'] ?? '') ?>" placeholder="https://..." class="form-control">
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">SEO / Техническа цел (Link)</label>
                        <input type="text" name="link" value="<?= htmlspecialchars($item['link'] ?? '') ?>" placeholder="Вътрешна референция..." class="form-control">
                    </div>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Съдържание и Текстове', 'slot' => ob_get_clean()]); ?>
            </div>

            <div class="space-y-6">
                <?php ob_start(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Група (Group Key)</label>
                        <select name="group_key" class="form-control bg-white">
                            <option value="" <?= empty($item['group_key']) ? 'selected' : '' ?>>Без група</option>
                            <?php foreach (BANNER_GROUPS as $key => $label): ?>
                                <option value="<?= $key ?>" <?= ($item['group_key'] ?? '') == $key ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Позиция на съдържанието</label>
                        <select name="content_place" class="form-control bg-white">
                            <?php foreach ($positions as $key => $label): ?>
                                <option value="<?= $key ?>" <?= ($item['content_place'] ?? 'center_center') == $key ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Височина (px)</label>
                        <input type="number" name="height" value="<?= $item['height'] ?? 520 ?>" class="form-control">
                    </div>
                </div>
                <?php View::component('card', 'admin/components', ['title' => 'Позициониране', 'slot' => ob_get_clean()]); ?>

                <div class="bg-[#1e293b] p-6 rounded-2xl shadow-sm text-white">
                    <h3 class="font-bold mb-4 flex items-center gap-2 text-blue-400">
                        <i class="fa-solid fa-eye text-xs"></i>
                        Показване на:
                    </h3>
                    <div class="space-y-3">
                        <?php
                        $toggles = [
                            'show_name' => 'Заглавие',
                            'show_description' => 'Описание',
                            'show_overlay' => 'Тъмен овърлей',
                            'show_button' => 'Бутон'
                        ];
                        foreach ($toggles as $field => $label):
                            $checked = ($item[$field] ?? 1) == 1 ? 'checked' : '';
                        ?>
                            <label class="flex items-center justify-between cursor-pointer group">
                                <span class="text-sm text-gray-300 group-hover:text-white transition"><?= $label ?></span>
                                <div class="relative">
                                    <input type="checkbox" name="<?= $field ?>" value="1" class="sr-only peer" <?= $checked ?>>
                                    <div class="w-11 h-6 bg-gray-600 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                                </div>
                                <input type="hidden" name="<?= $field ?>" value="0" x-data x-init="$el.disabled = $el.previousElementSibling.checked" @change="$el.disabled = $el.previousElementSibling.checked">
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php View::component('toggle', 'admin/components', [
                    'name'  => 'is_active',
                    'label' => 'Активен банер',
                    'value' => $item['is_active'] ?? true
                ]); ?>

                <div class="pt-2">
                    <?php View::component('submit-button', 'admin/components', [
                        'text' => !$isEdit ? 'Създаване' : 'Запазване',
                        'is_active' => $item['is_active'] ?? true
                    ]); ?>
                </div>
            </div>
        </div>
    </form>
</div>
