<?php
use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Автобуси', 'url' => '/admin/autobuses']]
    ]); ?>
</div>

<?php ob_start(); ?>
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Автобусни линии',
        'button_label' => 'Нова линия',
        'button_url'   => '/admin/autobuses/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Превозвач'],
        ['label' => 'Маршрут'],
        ['label' => 'SEO / Слъг'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($autobuses as $bus): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $bus['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/autobuses/edit/<?= $bus['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src'  => $bus['image_url'],
                        'alt'  => $bus['name'],
                    ]); ?>
                    <div>
                        <span class="block font-semibold text-gray-700 group-hover:text-indigo-600 transition">
                            <?= htmlspecialchars($bus['name']) ?>
                        </span>
                        <span class="text-[10px] text-gray-400 font-mono">
                            ID: #<?= str_pad($bus['id'], 4, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>
                </a>
            </td>

            <td class="px-5 py-4">
                <div class="flex items-center gap-2 text-sm">
                    <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-md font-medium border border-slate-200">
                        <?= htmlspecialchars($bus['from_city_name'] ?? '---') ?>
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-md font-medium border border-indigo-100">
                        <?= htmlspecialchars($bus['to_city_name'] ?? '---') ?>
                    </span>
                </div>
            </td>

            <td class="px-5 py-4">
                <div class="flex flex-col gap-1">
                    <span class="text-gray-500 truncate max-w-45" title="<?= htmlspecialchars($bus['slug']) ?>">
                        <span class="opacity-50">🔗</span> <?= htmlspecialchars($bus['slug']) ?>
                    </span>
                    <span class="flex items-center gap-1 font-bold text-xs uppercase <?= $bus['is_active'] ? 'text-emerald-600' : 'text-gray-400' ?>">
                        <span class="w-1.5 h-1.5 rounded-full <?= $bus['is_active'] ? 'bg-emerald-500 animate-pulse' : 'bg-gray-300' ?>"></span>
                        <?= $bus['is_active'] ? 'Активна' : 'Неактивна' ?>
                    </span>
                </div>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/autobuses/edit/{$bus['id']}",
                    'delete_url' => "/admin/autobuses/delete/{$bus['id']}",
                    'name'       => $bus['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="autobuses-table"'
    ]);
    ?>
<?php View::component('card', 'admin/components', ['slot' => ob_get_clean(), 'attributes' => 'p-0 overflow-hidden']); ?>

<div class="mt-5">
    <?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
</div>

<?php View::component('sortable-script', 'admin/components', [
    'tableId' => '#autobuses-table',
    'url'     => '/admin/autobuses/update-order'
]); ?>