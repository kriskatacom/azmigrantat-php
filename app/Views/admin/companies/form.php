<?php

use App\Core\View;

$isEdit = isset($company);
$action = $isEdit ? "/admin/companies/update/{$company['id']}" : "/admin/companies/store";

$translatableFields = [
    'company_slogan'       => ['label' => 'Слоган', 'type' => 'text'],
    'your_location'        => ['label' => 'Точен адрес (Текст)', 'type' => 'text'],
    'services_description' => ['label' => 'Услуги на компанията', 'type' => 'editor'],
    'description'          => ['label' => 'Пълно описание', 'type' => 'editor'],
    'contacts_content'     => ['label' => 'Допълнителна информация за контакти', 'type' => 'editor']
];

$companyData = [
    'id' => (int)($company['id'] ?? 0),
    'entity' => 'company',
    'fields' => array_keys($translatableFields),
    'translations' => $company['translations'] ?? (object)[]
];
?>

<script>
    window.companyFormData = <?= json_encode($companyData, JSON_UNESCAPED_UNICODE) ?>;
</script>

<div x-data="translatableForm(window.companyFormData)" x-init="init()">

    <?php if ($isEdit): ?>
        <?php View::component('translation-modal', 'admin/components', [
            'languages'     => $languages,
            'fields'        => $translatableFields,
            'nextId'        => $nextId ?? null,
            'saveEndpoint'  => '/admin/translations/confirm',
            'redirectBase'  => "/admin/companies/edit",
            'entityType'    => 'company'
        ]); ?>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
        <div>
            <?php View::component('breadcrumbs', 'admin/components', [
                'items' => [
                    ['label' => 'Компании', 'url' => '/admin/companies'],
                    ['label' => $isEdit ? 'Редактиране' : 'Нова компания']
                ]
            ]); ?>
        </div>

        <?php if ($isEdit): ?>
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
        <?php endif; ?>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-6">

        <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'image_url',
                'label' => 'Предно изображение',
                'value' => $company['image_url'] ?? null,
                'id'    => 'company-main-image'
            ]); ?>

            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'offer_image_url',
                'label' => 'Изображение по за обявите',
                'value' => $company['offer_image_url'] ?? null,
                'id'    => 'company-offer-image'
            ]); ?>

            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'ads_image_url',
                'label' => 'Изображение за рекламите',
                'value' => $company['ads_image_url'] ?? null,
                'id'    => 'company-ads-image'
            ]); ?>

            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'bottom_image_url',
                'label' => 'Долно изображение (Footer)',
                'value' => $company['bottom_image_url'] ?? null,
                'id'    => 'company-bottom-image'
            ]); ?>
        </div>

        <?php View::component('lightbox', 'admin/components'); ?>
        <?php View::component('card', 'admin/components', ['title' => 'Медия и Реклама', 'slot' => ob_get_clean()]); ?>

        <?php ob_start(); ?>
        <div class="space-y-4">
            <?php
            $gallery = [];
            if (!empty($company['additional_images'])) {
                $gallery = json_decode($company['additional_images'], true) ?: [];
            }

            View::component('multi-image-upload', 'admin/components', [
                'name'   => 'additional_images[]',
                'images' => $gallery,
                'label'  => 'Галерия на обекта'
            ]);
            ?>
        </div>
        <?php View::component('card', 'admin/components', ['title' => 'Фото галерия', 'slot' => ob_get_clean()]); ?>

        <?php ob_start(); ?>
        <div class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на компанията</label>
                    <input type="text" name="name" id="company-name" value="<?= htmlspecialchars($company['name'] ?? '') ?>" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-light focus:ring-4 focus:ring-primary-light/10 transition-all outline-none">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Слоган</label>
                    <input type="text" name="company_slogan" value="<?= htmlspecialchars($company['company_slogan'] ?? '') ?>"
                        placeholder="Вашето мото..."
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-light focus:ring-4 focus:ring-primary-light/10 outline-none transition-all">
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-5">

                <?php View::component('select-dropdown', 'admin/components', [
                    'name'       => 'category_id',
                    'label'      => 'Категория',
                    'options'    => $categories,
                    'selectedId' => $company['category_id'] ?? null,
                ]); ?>

                <div>
                    <?php View::component('location-selector', 'admin/components', [
                        'countries'       => $countries,
                        'cities'          => $cities ?? null,
                        'selectedCountry' => $company['country_id'] ?? null,
                        'selectedCity'    => $company['city_id'] ?? null
                    ]); ?>
                </div>
            </div>

            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $company['slug'] ?? '',
                'source' => 'company-name'
            ]); ?>
        </div>
        <?php View::component('card', 'admin/components', ['title' => 'Класификация', 'slot' => ob_get_clean()]); ?>

        <?php ob_start(); ?>
        <div class="space-y-5">
            <?php View::component('editor', 'admin/components', [
                'name'  => 'services_description',
                'label' => 'Услуги на компанията',
                'value' => $company['services_description'] ?? null,
            ]); ?>

            <?php View::component('editor', 'admin/components', [
                'name'  => 'description',
                'label' => 'Пълно описание',
                'value' => $company['description'] ?? null,
            ]); ?>

            <?php View::component('editor', 'admin/components', [
                'name'  => 'contacts_content',
                'label' => 'Допълнителна информация за контакти',
                'value' => $company['contacts_content'] ?? null,
            ]); ?>
        </div>
        <?php View::component('card', 'admin/components', ['title' => 'Текстово съдържание', 'slot' => ob_get_clean()]); ?>

        <?php ob_start(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Точен адрес (Текст)</label>
                <input type="text" name="your_location" value="<?= htmlspecialchars($company['your_location'] ?? '') ?>" placeholder="ул. Независимост №1..."
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Facebook страница</label>
                <input type="text" name="facebook_page_link" value="<?= htmlspecialchars($company['facebook_page_link'] ?? '') ?>" placeholder="https://facebook.com/..."
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none">
            </div>
        </div>

        <div class="space-y-2 mt-5">
            <label class="text-sm font-semibold text-gray-600">Уебсайт</label>
            <input type="text" name="website_link" value="<?= htmlspecialchars($company['website_link'] ?? '') ?>" placeholder="https://example.com"
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 outline-none">
        </div>

        <div class="mt-5">
            <?php View::component('maps-input', 'admin/components', [
                'name'  => 'google_map',
                'label' => 'Google Map Embed/Link',
                'value' => $company['google_map'] ?? ''
            ]); ?>
        </div>

        <?php View::component('card', 'admin/components', ['title' => 'Локация и Връзки', 'slot' => ob_get_clean()]); ?>

        <?php ob_start(); ?>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Собственик (Потребител)</label>
            <select name="user_id" class="form-control bg-white cursor-pointer">
                <option value="">-- Без собственик (Административен) --</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= (isset($company) && $company['user_id'] == $user['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php View::component('card', 'admin/components', ['title' => 'Администриране', 'slot' => ob_get_clean()]); ?>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $company['is_active'] ?? true
        ]); ?>

        <div class="pt-5">
            <?php View::component('submit-button', 'admin/components', [
                'text' => !$isEdit ? 'Създаване' : 'Запазване',
                'is_active' => $company['is_active'] ?? true
            ]); ?>
        </div>

    </form>
</div>