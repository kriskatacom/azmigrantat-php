<?php use App\Core\View; ?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Държави', 'url' => '/admin/countries'],
            ['label' => 'Елементи за ' . ($country['name'] ?? 'държавата')]
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title' => 'Елементи към ' . htmlspecialchars($country['name'] ?? ''),
        'button_label' => 'Нов елемент',
        'button_url' => '/admin/countries/country-elements/create?country_id=' . ($country['id'] ?? '')
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Елемент'],
        ['label' => 'Статус', 'align' => 'center'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($elements as $element): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $element['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>
            <td class="px-5 py-4">
                <a href="/admin/countries/country-elements/edit/<?= $element['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $element['image_url'],
                        'alt' => $element['name']
                    ]); ?>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-700 group-hover:text-indigo-600 transition-colors">
                            <?= htmlspecialchars($element['name']) ?>
                        </span>
                        <?php if(!empty($element['slug'])): ?>
                            <span class="text-[10px] text-gray-400 font-mono italic">slug: <?= $element['slug'] ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            </td>
            
            <td class="px-5 py-4 text-center">
                <?php if ($element['is_active']): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Активен
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Скрит
                    </span>
                <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-right whitespace-nowrap">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url' => "/admin/countries/country-elements/edit/{$element['id']}",
                    'delete_url' => "/admin/countries/country-elements/delete/{$element['id']}",
                    'name' => $element['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;
    
    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="country-elements-table"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#country-elements-table',
        'url' => '/admin/countries/country-elements/update-order'
    ]);
    ?>
</div>
