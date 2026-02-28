<?php
use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Влакове', 'url' => '/admin/trains']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'ЖП Линии и Компании',
        'button_label' => 'Нов влак',
        'button_url'   => '/admin/trains/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Компания'],
        ['label' => 'Локация'],
        ['label' => 'Статус'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($trains as $train): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $train['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/trains/edit/<?= $train['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src'  => $train['image_url'],
                        'alt'  => $train['name'],
                    ]); ?>
                    <div>
                        <span class="block font-semibold text-gray-700 group-hover:text-primary-dark transition">
                            <?= htmlspecialchars($train['name']) ?>
                        </span>
                        <span class="text-[10px] text-gray-400 font-mono">
                            ID: #<?= str_pad($train['id'], 4, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>
                </a>
            </td>

            <td class="px-5 py-4">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-700">
                         <?= htmlspecialchars($train['city_name'] ?? '---') ?>
                    </span>
                    <span class="text-xs text-gray-400">
                        <?= htmlspecialchars($train['country_name'] ?? '---') ?>
                    </span>
                </div>
            </td>

            <td class="px-5 py-4">
                <?php if($train['is_active']): ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Активен
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-400 border border-gray-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span> Скрит
                    </span>
                <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/trains/edit/{$train['id']}",
                    'delete_url' => "/admin/trains/delete/{$train['id']}",
                    'name'       => $train['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="trains-table"'
    ]);
    
    View::component('sortable-script', 'admin/components', [
        'tableId' => '#trains-table',
        'url'     => '/admin/trains/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>