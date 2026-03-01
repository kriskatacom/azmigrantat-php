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
        ['label' => 'Елементи', 'align' => 'center'],
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
                <span class="inline-flex items-center justify-center bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full text-xs font-medium">
                    <?= $country['cities_count'] ?? 0 ?> градове
                </span>
            </td>

            <td class="px-5 py-4 text-center">
                <a href="/admin/countries/country-elements?country_id=<?= $country['id'] ?>"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-200 text-xs font-bold border border-indigo-100 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Елементи
                </a>
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