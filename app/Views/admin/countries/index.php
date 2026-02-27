<?php use App\Core\View; ?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Държави', 'url' => '/admin/countries']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title' => 'Списък с държави',
        'button_label' => 'Нова държава',
        'button_url' => '/admin/countries/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Държава'],
        ['label' => 'Градове', 'align' => 'center'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($countries as $country): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $country['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>
            <td class="px-5 py-4">
                <a href="/admin/countries/edit/<?= $country['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $country['image_url'],
                        'alt' => $country['name']
                    ]); ?>
                    <span class="font-semibold text-gray-700 italic group-hover:text-primary-dark"><?= htmlspecialchars($country['name']) ?></span>
                </a>
            </td>
            <td class="px-5 py-4 text-center">
                <span class="inline-flex items-center justify-center bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full">
                    <?= $country['cities_count'] ?? 0 ?> градове
                </span>
            </td>
            <td class="px-5 py-4 text-right whitespace-nowrap">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url' => "/admin/countries/edit/{$country['id']}",
                    'delete_url' => "/admin/countries/delete/{$country['id']}",
                    'name' => $country['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;
    
    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="countries-table"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#countries-table',
        'url' => '/admin/countries/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>