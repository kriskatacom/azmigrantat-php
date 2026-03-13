<?php

use App\Core\View;

$item = $category ?? [];
$entityName = 'category';
$baseRoute = '/admin/categories';
$isEdit = isset($item['id']);

$translatableFields = [
    'name'    => ['label' => 'Име на категорията', 'type' => 'text'],
    'heading' => ['label' => 'Заглавие на страницата', 'type' => 'text'],
    'excerpt' => ['label' => 'Кратко описание', 'type' => 'editor'],
    'content' => ['label' => 'Подробно съдържание', 'type' => 'editor']
];

$action = $isEdit ? "{$baseRoute}/update/{$item['id']}" : "{$baseRoute}/store";

$categoryData = [
    'id' => (int)($item['id'] ?? 0),
    'entity' => $entityName,
    'fields' => array_keys($translatableFields),
    'translations' => $item['translations'] ?? (object)[]
];
?>

<script>
    window.categoryFormData = <?= json_encode($categoryData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<div
    x-data="translatableForm(window.categoryFormData)"
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
                    ['label' => 'Категории', 'url' => '/admin/categories'],
                    ['label' => $isEdit ? 'Редактиране' : 'Нова категория']
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

                <button type="button"
                    @click="openTranslation();"
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
                        $nextTick(() => { 
                            // Изчакваме модала да се инициализира и пускаме магията
                            if (!loading) magicTranslate(); 
                        });
                    "
                    class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 px-6 rounded-xl font-bold shadow-lg shadow-indigo-200 transition-all active:scale-95 ring-offset-2 focus:ring-2 focus:ring-indigo-500">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>AI Авто-превод</span>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

        <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'image_url',
                'label' => 'Основна икона / Снимка',
                'value' => $category['image_url'] ?? null,
                'id'    => 'cat-main-img'
            ]); ?>

            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'companies_background_url',
                'label' => 'Фон за страницата с компании',
                'value' => $category['companies_background_url'] ?? null,
                'id'    => 'cat-bg-img'
            ]); ?>
        </div>
        <?php View::component('lightbox', 'admin/components'); ?>
        <?php View::component('card', 'admin/components', ['title' => 'Визуални елементи', 'slot' => ob_get_clean()]); ?>

        <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Име на категорията</label>
                <input type="text" name="name" id="cat-name" value="<?= htmlspecialchars($category['name'] ?? '') ?>" required class="form-control">
            </div>

            <?php View::component('select-dropdown', 'admin/components', [
                'name'       => 'parent_id',
                'label'      => 'Родителска категория',
                'options'    => $categories,
                'selectedId' => $category['parent_id'] ?? null,
                'excludeId'  => $category['id'] ?? null,
            ]); ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">H1 Заглавие (Heading)</label>
                <input type="text" name="heading" value="<?= htmlspecialchars($category['heading'] ?? '') ?>" class="form-control">
            </div>

            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $category['slug'] ?? '',
                'source' => 'cat-name'
            ]); ?>
        </div>
        <?php View::component('card', 'admin/components', ['title' => 'Основни данни', 'slot' => ob_get_clean()]); ?>

        <?php ob_start(); ?>
        <div class="space-y-4">
            <?php View::component('editor', 'admin/components', [
                'name'  => 'excerpt',
                'label' => 'Кратко описание (Excerpt)',
                'value' => $category['excerpt'] ?? ''
            ]); ?>

            <?php View::component('editor', 'admin/components', [
                'name'  => 'content',
                'label' => 'Пълно съдържание',
                'value' => $category['content'] ?? ''
            ]); ?>
        </div>
        <?php View::component('card', 'admin/components', ['title' => 'Съдържание и SEO', 'slot' => ob_get_clean()]); ?>

        <?php ob_start(); ?>
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $category['is_active'] ?? true
        ]); ?>
        <?php View::component('card', 'admin/components', ['title' => 'Статус', 'slot' => ob_get_clean()]); ?>

        <div class="mb-5 pt-2">
            <?php View::component('submit-button', 'admin/components', [
                'text' => !$isEdit ? 'Създаване' : 'Запазване',
                'is_active' => $category['is_active'] ?? true
            ]); ?>
        </div>
    </form>
</div>