<?php

use App\Core\View;

$isEdit = isset($ad);
$action = $isEdit ? "/admin/offers/update/{$ad['id']}" : "/admin/offers/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Обяви', 'url' => '/admin/offers'],
            ['label' => $isEdit ? 'Редактиране' : 'Нова обява']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-5">

    <?php ob_start(); ?>
    <?php View::component('image-upload', 'admin/components', [
        'name'  => 'image_url',
        'label' => 'Рекламен банер (Изображение)',
        'value' => $ad['image_url'] ?? null,
        'id'    => 'ad-image'
    ]); ?>

    <?php View::component('lightbox', 'admin/components'); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Медия', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="space-y-4">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Име/Заглавие на обявата</label>
            <input type="text" name="name" id="ad-name"
                value="<?= htmlspecialchars($ad['name'] ?? '') ?>" required
                class="form-control">
        </div>

        <div class="grid md:grid-cols-2 gap-5">
            <?php View::component('select-dropdown', 'admin/components', [
                'name'        => 'company_id',
                'label'       => 'Компания',
                'selectedId'  => $ad['company_id'] ?? null,
                'options'     => $companies,
                'placeholder' => '-- Изберете компания --',
                'attributes'  => 'id="company-select"'
            ]); ?>

            <div id="user-selection-container" class="space-y-2 <?= (isset($ad) && !empty($ad['user_id'])) ? 'hidden' : '' ?>">
                <?php View::component('select-dropdown', 'admin/components', [
                    'name'        => 'user_id',
                    'label'       => 'Изберете собственик (ръчно)',
                    'selectedId'  => $ad['user_id'] ?? null,
                    'options'     => array_map(function ($u) {
                        return ['id' => $u['id'], 'name' => $u['name'] . ' (' . $u['email'] . ')'];
                    }, $users),
                    'placeholder' => '-- Изберете потребител --',
                    'attributes'  => 'id="user-manual-select"'
                ]); ?>
            </div>

            <div id="user-auto-container" class="space-y-2 <?= (isset($ad) && !empty($ad['user_id'])) ? '' : 'hidden' ?>">
                <label class="text-sm font-semibold text-gray-600">Автоматичен собственик</label>
                <div id="owner-display" class="form-control bg-gray-50 text-indigo-600 font-medium border-indigo-100 flex items-center gap-2">
                    <i class="fas fa-user-check text-xs"></i>
                    <span><?= htmlspecialchars($ad['user_name'] ?? 'Зареден автоматично') ?></span>
                </div>
                <input type="hidden" name="auto_user_id" id="auto-user-id" value="<?= $ad['user_id'] ?? '' ?>">
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const companySelect = document.getElementById('company-select');
            const adNameInput = document.getElementById('ad-name');

            if (companySelect) {
                const companiesData = <?= json_encode(array_column($companies, null, 'id')) ?>;

                companySelect.addEventListener('change', function() {
                    const companyId = this.value;
                    const userSelection = document.getElementById('user-selection-container');
                    const userAuto = document.getElementById('user-auto-container');
                    const ownerSpan = userAuto.querySelector('span');
                    const autoInput = document.getElementById('auto-user-id');
                    const manualSelect = document.getElementById('user-manual-select');

                    if (companyId && companiesData[companyId]) {
                        const company = companiesData[companyId];

                        if (adNameInput && adNameInput.value === '') {
                            adNameInput.value = "Обява на " + company.name;
                        }

                        if (company.user_id && company.user_id !== "") {
                            userSelection.classList.add('hidden');
                            userAuto.classList.remove('hidden');

                            ownerSpan.textContent = company.owner_name || 'Зареден потребител';

                            autoInput.value = company.user_id;
                            if (manualSelect) manualSelect.value = "";
                        } else {
                            userSelection.classList.remove('hidden');
                            userAuto.classList.add('hidden');
                            autoInput.value = "";
                            ownerSpan.textContent = "Собственик на компанията";
                        }
                    } else {
                        userSelection.classList.add('hidden');
                        userAuto.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    <?php View::component('card', 'admin/components', ['title' => 'Свързаност и Имена', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
    <div class="grid md:grid-cols-2 gap-5 items-end">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-600">Статус на обявата</label>
            <select name="status" class="form-control">
                <?php
                $statuses = [
                    'active'   => 'Активна (Показва се)',
                    'draft'    => 'Чернова (Скрита)',
                    'pending'  => 'Изчаква одобрение',
                    'canceled' => 'Отказана'
                ];
                foreach ($statuses as $val => $label): ?>
                    <option value="<?= $val ?>" <?= (isset($ad) && $ad['status'] == $val) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <?php View::component('card', 'admin/components', ['title' => 'Параметри на показване', 'slot' => ob_get_clean()]); ?>

    <div class="mb-10 pt-2">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => true
        ]); ?>
    </div>
</form>