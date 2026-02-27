<?php
use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Забележителности', 'url' => '/admin/landmarks']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Списък със забележителности',
        'button_label' => 'Нова забележителност',
        'button_url'   => '/admin/landmarks/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Забележителност'],
        ['label' => 'Държава', 'align' => 'center'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($landmarks as $landmark): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $landmark['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/landmarks/edit/<?= $landmark['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $landmark['image_url'],
                        'alt' => $landmark['name']
                    ]); ?>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-700 italic group-hover:text-indigo-600 transition">
                            <?= htmlspecialchars($landmark['name']) ?>
                        </span>
                        <?php if (!empty($landmark['heading'])): ?>
                            <span class="text-gray-400 font-normal">
                                <?= htmlspecialchars($landmark['heading']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            </td>

            <td class="px-5 py-4 text-center">
                <span class="inline-flex items-center justify-center bg-blue-50 text-blue-600 px-3 py-1 rounded-full border border-blue-100">
                    🌍 <?= htmlspecialchars($landmark['country_name'] ?? 'Няма държава') ?>
                </span>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/landmarks/edit/{$landmark['id']}",
                    'delete_url' => "/admin/landmarks/delete/{$landmark['id']}",
                    'name'       => $landmark['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="landmarks-table"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#landmarks-table',
        'url'     => '/admin/landmarks/update-order'
    ]);
    ?>
</div>

<?php if (isset($pagination)): ?>
    <?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
<?php endif; ?>
