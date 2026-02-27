<?php

use App\Core\View;
use App\Services\HelperService;

$selectedGroup = $_GET['group_key'] ?? '';

$positions = [
    'top_left' => 'Горе вляво',
    'top_center' => 'Горе център',
    'top_right' => 'Горе вдясно',
    'center_left' => 'Център вляво',
    'center_center' => 'Център',
    'center_right' => 'Център вдясно',
    'bottom_left' => 'Долу вляво',
    'bottom_center' => 'Долу център',
    'bottom_right' => 'Долу вдясно'
];
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Банери', 'url' => '/admin/banners']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h2 class="font-bold text-gray-800 text-lg">Управление на банери</h2>
        </div>

        <div class="border-b border-gray-100 flex justify-between items-center gap-5 bg-gray-50/50">
            <div class="text-left">
                <select onchange="window.location.href='/admin/banners?group_key=' + this.value" class="px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                    <option value="">Всички групи</option>
                    <?php
                    foreach ($groups as $group): ?>
                        <option value="<?= htmlspecialchars($group) ?>" <?= $selectedGroup == $group ? 'selected' : '' ?>>
                            <?= htmlspecialchars($group) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <a href="/admin/banners/create" class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition shadow-sm">
                + Нов банер
            </a>
        </div>
    </div>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Визуализация'],
        ['label' => 'Детайли'],
        ['label' => 'Група / Позиция'],
        ['label' => 'Статус'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($banners as $banner):
        $imagePath = !empty($banner['image']) ? $banner['image'] : '/assets/images/placeholders/banner.webp';
        $editUrl = "/admin/banners/edit/{$banner['id']}";
        $deleteUrl = "/admin/banners/delete/{$banner['id']}";
    ?>
        <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0" data-id="<?= $banner['id'] ?>">
            <td class="px-5 py-4 w-10">
                <div class="drag-handle text-gray-300 hover:text-gray-500 cursor-grab active:cursor-grabbing">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </div>
            </td>

            <td class="px-5 py-4">
                <a href="<?= $editUrl ?>" class="flex items-center gap-4 group">
                    <div class="w-32 h-20 rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50 shrink-0 group-hover:ring-2 group-hover:ring-primary-light transition-all">
                        <img src="<?= HelperService::getImage($imagePath) ?>" 
                             alt="<?= htmlspecialchars($banner['name']) ?>"
                             class="w-full h-full object-cover">
                    </div>
                </a>
            </td>

            <td class="px-5 py-4">
                <div class="space-y-1">
                    <a href="<?= $editUrl ?>" class="font-bold text-gray-700 hover:text-primary-dark transition block">
                        <?= htmlspecialchars($banner['name'] ?: 'Без име') ?>
                    </a>
                    <p class="text-gray-400 line-clamp-1"><?= htmlspecialchars($banner['description'] ?? '') ?></p>
                </div>
            </td>

            <td class="px-5 py-4">
                <div class="flex flex-col gap-1">
                    <span class="font-bold uppercase tracking-tight">
                        📁 <?= htmlspecialchars($banner['group_key'] ?: 'default') ?>
                    </span>
                    <span class="text-gray-500">
                        📍 <?= $positions[$banner['content_place']] ?? $banner['content_place'] ?>
                    </span>
                </div>
            </td>

            <td class="px-5 py-4">
                <div class="flex flex-wrap gap-2">
                    <?php
                    $flags = [
                        'Бутон' => $banner['show_button'],
                        'Име' => $banner['show_name'],
                        'Овърлей' => $banner['show_overlay']
                    ];
                    foreach ($flags as $label => $isActive): ?>
                        <span class="px-1.5 py-0.5 rounded font-bold uppercase border <?= $isActive ? 'bg-green-50 border-green-100' : 'bg-gray-50 border-gray-100' ?>">
                            <?= $label ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </td>

            <td class="px-5 py-4">
                <?php View::component('table-actions', 'admin/components', [
                    'editUrl'   => "/admin/banners/edit/{$banner['id']}",
                    'deleteUrl' => "/admin/banners/delete/{$banner['id']}",
                    'name'      => $banner['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    $tableBody = ob_get_clean();

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => $tableBody,
        'attributes' => 'id="banners-table-all"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#banners-table-all',
        'url'     => '/admin/banners/update-order'
    ]);
    ?>

    <?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
</div>
