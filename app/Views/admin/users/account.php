<?php

use App\Core\View;
use App\Models\User;

$sessionUser = User::auth();
$isEdit = isset($driver);

$isAdmin = ($sessionUser['role'] === 'admin');
$isDriver = ($sessionUser['role'] === 'driver');

$targetUserId = $isEdit ? $driver['user_id'] : $sessionUser['id'];
$action = "/admin/users/update/{$targetUserId}";

if ($isDriver) {
    $breadcrumbs = [
        ['label' => 'Моят профил', 'url' => "/travel/shared-travel/drivers/" . $user['username']],
        ['label' => 'Редактиране на профила']
    ];
} else {
    $breadcrumbs = [
        ['label' => 'Потребители', 'url' => '/admin/users'],
        ['label' => $isEdit ? "Редактиране: " . htmlspecialchars($driver['name'] ?? 'Профил') : 'Нов шофьор']
    ];
}
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => $breadcrumbs
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
    <div class="grid xl:grid-cols-3 gap-5">
        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'profile_image_url',
            'label' => 'Профилна снимка',
            'value' => $driver['profile_image_url'] ?? null,
            'id'    => 'driver-profile-image'
        ]); ?>

        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'cover_image_url',
            'label' => 'Корица (Cover)',
            'value' => $driver['cover_image_url'] ?? null,
            'id'    => 'driver-cover-image'
        ]); ?>

        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'post_image_url',
            'label' => 'Снимка на автомобила',
            'value' => $driver['post_image_url'] ?? null,
            'id'    => 'driver-car-image'
        ]); ?>

        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'travel_departure_image',
            'label' => 'Обява при тръгване',
            'value' => $driver['travel_departure_image'] ?? null,
            'id'    => 'driver-travel-departure-image'
        ]); ?>

        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'travel_return_image',
            'label' => 'Обява при връщане',
            'value' => $driver['travel_return_image'] ?? null,
            'id'    => 'driver-travel-return-image'
        ]); ?>
    </div>

    <div class="pt-5 border-t border-gray-100 mt-5">
        <?php
        $gallery = [];
        if (!empty($driver['gallery_images'])) {
            $gallery = is_array($driver['gallery_images'])
                ? $driver['gallery_images']
                : json_decode($driver['gallery_images'], true);
        }

        View::component('multi-image-upload', 'admin/components', [
            'name'   => 'gallery_images[]',
            'label'  => 'Фотогалерия на автомобила / Пътуването',
            'images' => $gallery
        ]);
        ?>
    </div>
    <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Медия и снимки', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Име на шофьора</label>
            <input type="text" name="name" id="driver-name" value="<?= htmlspecialchars($driver['name'] ?? '') ?>" required class="form-control">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Модел автомобил</label>
            <input type="text" name="car_model" value="<?= htmlspecialchars($driver['car_model'] ?? '') ?>"
                placeholder="напр. VW Passat (СВ 1234 АВ)" class="form-control">
        </div>

        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Facebook страница</label>
            <input type="text" name="facebook_page_link" value="<?= htmlspecialchars($driver['facebook_page_link'] ?? '') ?>" placeholder="https://facebook.com/..." class="form-control">
        </div>
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Профил и автомобил', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Телефон за връзка</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($driver['phone'] ?? '') ?>" class="form-control">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Email (ако е различен)</label>
            <input type="email" name="email" value="<?= htmlspecialchars($driver['email'] ?? '') ?>" class="form-control">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Възраст</label>
            <input type="number" name="age" value="<?= htmlspecialchars($driver['age'] ?? '') ?>" class="form-control">
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Статус на пътуване</label>
            <select name="driver_travel_status" class="form-control bg-white">
                <option value="not_traveling" <?= ($driver['driver_travel_status'] ?? '') === 'not_traveling' ? 'selected' : '' ?>>
                    ❌ Няма пътуване
                </option>
                <option value="departure" <?= ($driver['driver_travel_status'] ?? '') === 'departure' ? 'selected' : '' ?>>
                    🚚 На път (тръгване)
                </option>
                <option value="return" <?= ($driver['driver_travel_status'] ?? '') === 'return' ? 'selected' : '' ?>>
                    🔙 На път (връщане)
                </option>
            </select>
        </div>
    </div>

    <?php View::component('card', 'admin/components', ['title' => 'Информация за връзка', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="space-y-6">
        <?php View::component('editor', 'admin/components', [
            'name'  => 'description',
            'label' => 'Лично представяне / Правила в колата',
            'value' => $driver['description'] ?? ''
        ]); ?>

        <?php View::component('editor', 'admin/components', [
            'name'  => 'travel_departure_details',
            'label' => 'Детайли за текущото пътуване (на тръгване)',
            'value' => $driver['travel_departure_details'] ?? ''
        ]); ?>

        <?php View::component('editor', 'admin/components', [
            'name'  => 'travel_return_details',
            'label' => 'Детайли за текущото пътуване (на връщане)',
            'value' => $driver['travel_return_details'] ?? ''
        ]); ?>
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Описания', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <?php View::component('toggle', 'admin/components', [
        'name'  => 'is_active',
        'label' => '<i class="fas fa-eye mr-1 text-gray-500"></i> Видимост в сайта',
        'value' => $driver['is_active'] ?? true,
        'description' => 'Ако е изключено, профилът ще бъде скрит за клиентите.'
    ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Настройки на видимост', 'slot' => ob_get_clean()]); ?>

    <div class="pt-5">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $driver['is_active'] ?? true
        ]); ?>
    </div>
</form>