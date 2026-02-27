<?php
use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Градове', 'url' => '/admin/cities']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Списък с градове',
        'button_label' => 'Нов град',
        'button_url'   => '/admin/cities/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Град'],
        ['label' => 'Държава'],
        ['label' => 'SEO статус'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($cities as $city): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $city['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/cities/edit/<?= $city['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $city['image_url'],
                        'alt' => $city['name']
                    ]); ?>
                    <div>
                        <span class="block font-semibold text-gray-700 group-hover:text-indigo-600 transition">
                            <?= htmlspecialchars($city['name']) ?>
                        </span>
                        <span class="text-gray-400 font-mono">
                            ID: #<?= str_pad($city['id'], 4, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>
                </a>
            </td>

            <td class="px-5 py-4">
                <span class="inline-flex items-center gap-1.5 font-medium text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">
                    🌍 <?= htmlspecialchars($city['country_name'] ?? 'Няма държава') ?>
                </span>
            </td>

            <td class="px-5 py-4">
                <div class="flex flex-col gap-1 max-w-50">
                    <span class="text-gray-500 truncate flex items-center gap-1" title="<?= htmlspecialchars($city['slug']) ?>">
                        <span class="opacity-50 text-indigo-500">🔗</span> <?= htmlspecialchars($city['slug']) ?>
                    </span>
                    <?php if(!empty($city['heading'])): ?>
                        <span class="text-emerald-600 font-medium flex items-center gap-1">
                            <span class="bg-emerald-100 px-1 rounded">H1</span> 
                            <?= htmlspecialchars($city['heading']) ?>
                        </span>
                    <?php else: ?>
                        <span class="text-amber-500 italic">Липсва H1 заглавие</span>
                    <?php endif; ?>
                </div>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/cities/edit/{$city['id']}",
                    'delete_url' => "/admin/cities/delete/{$city['id']}",
                    'name'       => $city['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="cities-table"'
    ]);
    
    View::component('sortable-script', 'admin/components', [
        'tableId' => '#cities-table',
        'url'     => '/admin/cities/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>