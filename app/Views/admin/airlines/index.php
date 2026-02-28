<?php

use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Авиолинии', 'url' => '/admin/airlines']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Авиолинии',
        'button_label' => 'Нова авиолиния',
        'button_url'   => '/admin/airlines/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Авиокомпания'],
        ['label' => 'Уебсайт'],
        ['label' => 'Статус'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($airlines as $airline): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $airline['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>
            <td class="px-5 py-4">
                <div class="flex items-center gap-4">
                    <a href="/admin/airlines/edit/<?= $airline['id'] ?>" class="flex items-center gap-4 group">
                        <?php View::component('table-image', 'admin/components', [
                            'src' => $airline['image_url'],
                            'alt' => $airline['name'],
                        ]); ?>
                    </a>
                    <div>
                        <span class="block font-semibold text-gray-700"><?= htmlspecialchars($airline['name']) ?></span>
                        <span class="text-[10px] text-gray-400 font-mono italic">slug: <?= $airline['slug'] ?></span>
                    </div>
                </div>
            </td>
            <td class="px-5 py-4">
                <?php if ($airline['website_url']): ?>
                    <a href="<?= $airline['website_url'] ?>" target="_blank" class="text-sm text-indigo-500 hover:underline">
                        Посети сайта ↗
                    </a>
                <?php else: ?>
                    <span class="text-gray-300 text-sm">—</span>
                <?php endif; ?>
            </td>
            <td class="px-5 py-4">
                <?php if ($airline['is_active']): ?>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-green-50 text-green-600 border border-green-100">Активна</span>
                <?php else: ?>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-red-50 text-red-600 border border-red-100">Спряна</span>
                <?php endif; ?>
            </td>
            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/airlines/edit/{$airline['id']}",
                    'delete_url' => "/admin/airlines/delete/{$airline['id']}",
                    'name'       => $airline['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="airlines-table"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#airlines-table',
        'url'     => '/admin/airlines/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>