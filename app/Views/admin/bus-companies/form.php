<?php

use App\Core\View;

$isEdit = isset($company);
$action = $isEdit ? "/admin/bus-companies/update/{$company['id']}" : "/admin/bus-companies/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Автобусни компании', 'url' => '/admin/bus-companies'],
            ['label' => $isEdit ? 'Редактиране' : 'Нова компания']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
    <div class="grid lg:grid-cols-1 gap-5">
        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'logo_url',
            'label' => 'Лого на компанията',
            'value' => $company['logo_url'] ?? null,
            'id'    => 'company-logo'
        ]); ?>
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Брандинг', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Име на компанията</label>
            <input type="text" name="name" id="company-name"
                value="<?= htmlspecialchars($company['name'] ?? '') ?>" required
                placeholder="напр. Етап Адресс"
                class="form-control">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
            <input type="url" name="website_url"
                value="<?= htmlspecialchars($company['website_url'] ?? '') ?>"
                placeholder="https://www.example.com"
                class="form-control">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Телефон за връзка</label>
            <input type="text" name="phone"
                value="<?= htmlspecialchars($company['phone'] ?? '') ?>"
                placeholder="+359..."
                class="form-control">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Имейл адрес</label>
            <input type="email" name="email"
                value="<?= htmlspecialchars($company['email'] ?? '') ?>"
                placeholder="office@company.com"
                class="form-control">
        </div>
    </div>

    <div class="mt-4 space-y-2">
        <label class="text-sm font-semibold text-gray-600">Описание</label>
        <textarea name="description" rows="3" class="form-control" placeholder="Кратка информация за компанията..."><?= htmlspecialchars($company['description'] ?? '') ?></textarea>
    </div>

    <div class="mt-4">
        <?php View::component('slug-input', 'admin/components', [
            'name'   => 'slug',
            'value'  => $company['slug'] ?? '',
            'source' => 'company-name'
        ]); ?>
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Обща информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <?php View::component('location-selector', 'admin/components', [
        'countries'       => $countries,
        'cities'          => $cities ?? null,
        'selectedCountry' => $company['country_id'] ?? null,
        'selectedCity'    => $company['city_id'] ?? null
    ]); ?>
    <?php View::component('card', 'admin/components', [
        'title' => 'Централно управление / Локация',
        'slot' => ob_get_clean()
    ]); ?>

    <?php ob_start(); ?>
    <?php View::component('toggle', 'admin/components', [
        'name'  => 'is_active',
        'label' => 'Активна (показвай в сайта)',
        'value' => $company['is_active'] ?? true
    ]); ?>

    <div class="mt-4">
        <label class="text-sm font-semibold text-gray-600">Подредба (sort_order)</label>
        <input type="number" name="sort_order"
            value="<?= $company['sort_order'] ?? 0 ?>"
            class="form-control w-32">
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Статус и подредба', 'slot' => ob_get_clean()]); ?>

    <div class="mt-5 pt-5 border-t border-slate-100 flex items-center justify-between">
        <a href="/admin/bus-companies" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Отказ</a>
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създай компания' : 'Запази промените'
        ]); ?>
    </div>
</form>

<?php View::component('lightbox', 'admin/components'); ?>
