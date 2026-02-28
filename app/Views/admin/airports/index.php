<?php
use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Летища', 'url' => '/admin/airports']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Летища',
        'button_label' => 'Ново летище',
        'button_url'   => '/admin/airports/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Летище'],
        ['label' => 'Държава'],
        ['label' => 'Статус'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($airports as $airport): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $airport['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>
            <td class="px-5 py-4">
                <div class="flex items-center gap-4">
                    <a href="/admin/airports/edit/<?= $airport['id'] ?>" class="flex items-center gap-4 group">
                        <?php View::component('table-image', 'admin/components', [
                            'src' => $airport['image_url'],
                            'alt' => $airport['name'],
                        ]); ?>
                    </a>
                    <div>
                        <span class="block font-semibold text-gray-700"><?= htmlspecialchars($airport['name']) ?></span>
                        <span class="text-[10px] text-gray-400 font-mono italic">slug: <?= $airport['slug'] ?></span>
                    </div>
                </div>
            </td>
            <td class="px-5 py-4">
                <span class="inline-flex items-center gap-1.5 font-medium text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">
                    🌍 <?= htmlspecialchars($airport['country_name'] ?? 'Няма държава') ?>
                </span>
            </td>
            <td class="px-5 py-4">
                <?php if ($airport['is_active']): ?>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-green-50 text-green-600 border border-green-100">Активно</span>
                <?php else: ?>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-red-50 text-red-600 border border-red-100">Скрито</span>
                <?php endif; ?>
            </td>
            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/airports/edit/{$airport['id']}",
                    'delete_url' => "/admin/airports/delete/{$airport['id']}",
                    'name'       => $airport['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="airports-table"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#airports-table',
        'url'     => '/admin/airports/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>