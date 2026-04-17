<?php

use App\Core\View;
use App\Services\HelperService;

?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Села', 'url' => '/admin/villages']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="pt-5 px-5">
        <?php View::component('search-bar', 'admin/components', [
            'action'      => '/admin/villages',
            'placeholder' => 'Търсене по име на село или локация...',
        ]); ?>
    </div>

    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Списък със села',
        'button_label' => 'Добави село',
        'button_url'   => '/admin/villages/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Село'],
        ['label' => 'Местоположение'],
        ['label' => 'Секции / Галерия'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($villages as $village): ?>
        <?php
        // Декодираме JSON данните за визуализация в таблицата (броене)
        $sections = json_decode($village['description_sections'] ?? '[]', true);
        $gallery = json_decode($village['gallery_urls'] ?? '[]', true);
        $sectionsCount = count($sections);
        $galleryCount = count($gallery);
        ?>

        <tr class="hover:bg-gray-50 transition" data-id="<?= $village['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/villages/edit/<?= $village['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $village['image_url'],
                        'alt' => $village['title']
                    ]); ?>
                    <div>
                        <span class="block font-semibold text-gray-700 group-hover:text-indigo-600 transition">
                            <?= htmlspecialchars($village['title']) ?>
                        </span>
                        <span class="text-xs text-gray-400 font-mono">
                            <?= htmlspecialchars($village['slug']) ?>
                        </span>
                    </div>
                </a>
            </td>

            <td class="px-5 py-4">
                <span class="inline-flex items-center gap-1.5 font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">
                    📍 <?= htmlspecialchars($village['location'] ?? 'Непосочено') ?>
                </span>
            </td>

            <td class="px-5 py-4">
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-1 text-gray-600" title="Брой описателни секции">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <span class="font-medium"><?= $sectionsCount ?></span>
                    </div>

                    <div class="flex items-center gap-1 text-gray-600" title="Брой снимки в галерията">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium"><?= $galleryCount ?></span>
                    </div>
                </div>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/villages/edit/{$village['id']}",
                    'delete_url' => "/admin/villages/delete/{$village['id']}",
                    'name'       => $village['title']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="villages-table"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#villages-table',
        'url'     => '/admin/villages/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>