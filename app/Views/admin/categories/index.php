<?php

use App\Core\View;
?>

<div class="mb-5">
    <?php 
    $breadcrumbItems = [
        ['label' => 'Всички категории', 'url' => '/admin/categories']
    ];

    if (!empty($path)) {
        foreach ($path as $crumb) {
            $breadcrumbItems[] = $crumb;
        }
    }

    View::component('breadcrumbs', 'admin/components', [
        'items' => $breadcrumbItems
    ]); 
    ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => isset($parentId) ? 'Подкатегории' : 'Основни категории',
        'button_label' => 'Нова категория',
        'button_url'   => '/admin/categories/create' . (isset($parentId) ? '?parent_id=' . $parentId : '')
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Име / Тип'],
        ['label' => 'Слъг'],
        ['label' => 'Подкатегории', 'align' => 'center'], // Новата колона
        ['label' => 'Статус'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($categories as $cat): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $cat['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/categories/edit/<?= $cat['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src'  => $cat['image_url'],
                        'alt'  => $cat['name'],
                    ]); ?>
                    <div>
                        <span class="block font-semibold text-gray-700 group-hover:text-indigo-600 transition">
                            <?= htmlspecialchars($cat['name']) ?>
                        </span>
                        <?php if ($cat['parent_id']): ?>
                            <span class="text-[10px] px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded uppercase font-bold">ID: #<?= $cat['id'] ?></span>
                        <?php else: ?>
                            <span class="text-[10px] px-1.5 py-0.5 bg-indigo-50 text-indigo-600 rounded uppercase font-bold">Главна</span>
                        <?php endif; ?>
                    </div>
                </a>
            </td>

            <td class="px-5 py-4 text-sm text-gray-500 font-mono">
                /<?= htmlspecialchars($cat['slug']) ?>
            </td>

            <td class="px-5 py-4 text-center">
                <a href="/admin/categories?parent_id=<?= $cat['id'] ?>"
                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg border border-slate-200 hover:border-indigo-100 transition-all text-xs font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Управление
                </a>
            </td>

            <td class="px-5 py-4">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold <?= $cat['is_active'] ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-50 text-gray-400' ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= $cat['is_active'] ? 'bg-emerald-500' : 'bg-gray-300' ?>"></span>
                    <?= $cat['is_active'] ? 'Активна' : 'Скрита' ?>
                </span>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/categories/edit/{$cat['id']}",
                    'delete_url' => "/admin/categories/delete/{$cat['id']}",
                    'name'       => $cat['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="categories-table"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#categories-table',
        'url'     => '/admin/categories/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
