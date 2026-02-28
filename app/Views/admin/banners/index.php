<?php

use App\Core\View;

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
        <h2 class="font-bold text-gray-800 text-lg">Управление на банери</h2>

        <div class="flex items-center gap-5">
            <select onchange="window.location.href='/admin/banners?group_key=' + this.value"
                class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500 transition shadow-sm outline-none">
                <option value="">Всички групи</option>
                <?php foreach ($groups as $group): ?>
                    <option value="<?= htmlspecialchars($group) ?>" <?= $selectedGroup == $group ? 'selected' : '' ?>>
                        📁 <?= htmlspecialchars($group) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <a href="/admin/banners/create" class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-700 transition flex items-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Нов банер
            </a>
        </div>
    </div>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Визуализация'],
        ['label' => 'Детайли'],
        ['label' => 'Група / Позиция'],
        ['label' => 'Конфигурация'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($banners as $banner): ?>
        <tr class="hover:bg-gray-50 transition border-b border-gray-50 last:border-0" data-id="<?= $banner['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/banners/edit/<?= $banner['id'] ?>" class="group block">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $banner['image_url'],
                        'alt' => $banner['name'] ?? 'Banner',
                    ]); ?>
                </a>
            </td>

            <td class="px-5 py-4">
                <div class="max-w-xs">
                    <a href="/admin/banners/edit/<?= $banner['id'] ?>" class="font-bold text-gray-700 hover:text-indigo-600 transition block mb-1">
                        <?= htmlspecialchars($banner['name'] ?: 'Без име') ?>
                    </a>
                    <p class="text-xs text-gray-400 line-clamp-2 italic"><?= htmlspecialchars($banner['description'] ?? '') ?></p>
                </div>
            </td>

            <td class="px-5 py-4 text-sm">
                <div class="flex flex-col gap-1.5">
                    <span class="font-bold text-[10px] uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100 self-start">
                        📁 <?= htmlspecialchars($banner['group_key'] ?: 'default') ?>
                    </span>
                    <span class="text-gray-500 flex items-center gap-1 text-[11px]">
                        📍 <?= $positions[$banner['content_place']] ?? $banner['content_place'] ?>
                    </span>
                </div>
            </td>

            <td class="px-5 py-4">
                <div class="flex flex-wrap gap-1.5">
                    <?php
                    $flags = [
                        ['L' => 'BTN', 'V' => $banner['show_button']],
                        ['L' => 'NAME', 'V' => $banner['show_name']],
                        ['L' => 'OVL', 'V' => $banner['show_overlay']]
                    ];
                    foreach ($flags as $flag): ?>
                        <span class="px-1.5 py-0.5 rounded-sm text-[9px] font-black border <?= $flag['V'] ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-100' ?>">
                            <?= $flag['L'] ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </td>

            <td class="px-5 py-4">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/banners/edit/{$banner['id']}",
                    'delete_url' => "/admin/banners/delete/{$banner['id']}",
                    'name'       => $banner['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="banners-table-all"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#banners-table-all',
        'url'     => '/admin/banners/update-order'
    ]);
    ?>
</div>

<?php if (isset($pagination)): ?>
    <?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
<?php endif; ?>