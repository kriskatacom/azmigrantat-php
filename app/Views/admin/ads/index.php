<?php use App\Core\View; ?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Реклами', 'url' => '/admin/ads']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title' => 'Реклами на компании',
        'button_label' => 'Нова реклама',
        'button_url' => '/admin/ads/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Реклама'],
        ['label' => 'Компания'],
        ['label' => 'Статус', 'align' => 'center'],
        ['label' => 'Дата', 'align' => 'center'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($ads as $ad): 
        $statusClasses = [
            'active'   => 'bg-green-100 text-green-700',
            'draft'    => 'bg-gray-100 text-gray-600',
            'pending'  => 'bg-yellow-100 text-yellow-700',
            'canceled' => 'bg-red-100 text-red-700'
        ];
        $currentStatusClass = $statusClasses[$ad['status']] ?? 'bg-gray-100 text-gray-600';
    ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $ad['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>
            <td class="px-5 py-4">
                <a href="/admin/ads/edit/<?= $ad['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $ad['image_url'],
                        'alt' => $ad['name']
                    ]); ?>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-700 group-hover:text-primary-dark transition-colors">
                            <?= htmlspecialchars($ad['name']) ?>
                        </span>
                        <span class="text-xs text-gray-400">ID: #<?= $ad['id'] ?></span>
                    </div>
                </a>
            </td>
            <td class="px-5 py-4">
                <span class="text-sm text-gray-600 font-medium">
                    <?= htmlspecialchars($ad['company_name'] ?? 'Няма компания') ?>
                </span>
            </td>
            <td class="px-5 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $currentStatusClass ?>">
                    <?= $ad['status'] ?>
                </span>
            </td>
            <td class="px-5 py-4 text-center text-sm text-gray-500 italic">
                <?= date('d.m.Y', strtotime($ad['created_at'])) ?>
            </td>
            <td class="px-5 py-4 text-right whitespace-nowrap">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url' => "/admin/ads/edit/{$ad['id']}",
                    'delete_url' => "/admin/ads/delete/{$ad['id']}",
                    'name' => $ad['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;
    
    $tableSlot = ob_get_clean();

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => $tableSlot,
        'attributes' => 'id="ads-table"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#ads-table',
        'url' => '/admin/ads/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>