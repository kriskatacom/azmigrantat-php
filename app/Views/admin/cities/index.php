<?php

use App\Core\View;
use App\Services\HelperService;

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
        ['label' => 'Преводи'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($cities as $city): ?>
        <?php $total_languages = count(HelperService::AVAILABLE_LANGUAGES) - 1;
        $count = $city['translations_count'] ?? 0; ?>

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
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2 overflow-hidden">
                            <?php if ($count > 0): ?>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border-2 border-white bg-indigo-100 text-indigo-700 text-xs font-bold z-10">
                                    +<?= $count ?>
                                </span>
                            <?php endif; ?>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border-2 border-white bg-gray-100 text-gray-400 text-xs">
                                <?= $total_languages ?>
                            </span>
                        </div>
                        <span class="text-sm font-medium <?= $count == $total_languages ? 'text-emerald-600' : 'text-gray-500' ?>">
                            <?= $count ?> / <?= $total_languages ?> езика
                        </span>
                    </div>
                    
                    <div class="w-32 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full" style="width: <?= ($count / $total_languages) * 100 ?>%"></div>
                    </div>
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