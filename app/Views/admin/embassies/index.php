<?php
use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Посолства', 'url' => '/admin/embassies']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Списък с посолства',
        'button_label' => 'Ново посолство',
        'button_url'   => '/admin/embassies/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Посолство'],
        ['label' => 'Държава'],
        ['label' => 'Контакти'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($embassies as $embassy):
        $imagePath = $embassy['logo'] ?: ($embassy['image_url'] ?: '');
    ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $embassy['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/embassies/edit/<?= $embassy['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $imagePath,
                        'alt' => $embassy['name'],
                    ]); ?>
                    <div>
                        <span class="block font-semibold text-gray-700 group-hover:text-indigo-600 transition">
                            <?= htmlspecialchars($embassy['name']) ?>
                        </span>
                        <span class="text-gray-400 font-mono tracking-tighter uppercase">
                            ID: #<?= str_pad($embassy['id'], 4, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>
                </a>
            </td>

            <td class="px-5 py-4">
                <span class="inline-flex items-center gap-1.5 font-medium text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full border border-blue-100">
                    🌍 <?= htmlspecialchars($embassy['country_name'] ?? 'Няма държава') ?>
                </span>
            </td>

            <td class="px-5 py-4">
                <div class="flex flex-col gap-1.5">
                    <?php if(!empty($embassy['email'])): ?>
                        <span class="text-gray-500 flex items-center gap-1.5">
                            <span class="opacity-70">📧</span> <?= htmlspecialchars($embassy['email']) ?>
                        </span>
                    <?php endif; ?>
                    <?php if(!empty($embassy['phone'])): ?>
                        <span class="text-gray-500 flex items-center gap-1.5">
                            <span class="opacity-70">📞</span> <?= htmlspecialchars($embassy['phone']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/embassies/edit/{$embassy['id']}",
                    'delete_url' => "/admin/embassies/delete/{$embassy['id']}",
                    'name'       => $embassy['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="embassies-table"'
    ]);
    
    View::component('sortable-script', 'admin/components', [
        'tableId' => '#embassies-table',
        'url'     => '/admin/embassies/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>