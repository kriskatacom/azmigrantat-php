<?php

use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Автобусни компании', 'url' => '/admin/bus-companies']]
    ]); ?>
</div>

<?php ob_start(); ?>
<?php View::component('search-bar', 'admin/components', [
    'action'      => '/admin/bus-companies',
    'placeholder' => 'Търсене по име на компания...',
]); ?>

<?php View::component('page-header', 'admin/components', [
    'title'        => 'Автобусни компании',
    'button_label' => 'Нова компания',
    'button_url'   => '/admin/bus-companies/create'
]); ?>

<?php
$headers = [
    ['label' => 'Ред'],
    ['label' => 'Компания'],
    ['label' => 'Контакти'],
    ['label' => 'SEO / Статус'],
    ['label' => 'Действия', 'align' => 'right']
];

ob_start();
foreach ($companies as $company): ?>
    <tr class="hover:bg-gray-50 transition" data-id="<?= $company['id'] ?>">
        <td class="px-5 py-4 w-10">
            <?php View::component('drag-handle', 'admin/components'); ?>
        </td>

        <td class="px-5 py-4">
            <a href="/admin/bus-companies/edit/<?= $company['id'] ?>" class="flex items-center gap-4 group">
                <?php View::component('table-image', 'admin/components', [
                    'src'  => $company['logo_url'],
                    'alt'  => $company['name'],
                ]); ?>
                <div>
                    <span class="block font-semibold text-gray-700 group-hover:text-indigo-600 transition">
                        <?= htmlspecialchars($company['name']) ?>
                    </span>
                    <span class="text-[10px] text-gray-400 font-mono">
                        ID: #<?= str_pad($company['id'], 4, '0', STR_PAD_LEFT) ?>
                    </span>
                </div>
            </a>
        </td>

        <td class="px-5 py-4">
            <div class="flex flex-col gap-1 text-sm">
                <?php if (!empty($company['phone'])): ?>
                    <span class="text-gray-600 flex items-center gap-1">
                        <span class="opacity-50 text-xs">📞</span> <?= htmlspecialchars($company['phone']) ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($company['website_url'])): ?>
                    <a href="<?= htmlspecialchars($company['website_url']) ?>" target="_blank" class="text-indigo-500 hover:underline text-xs flex items-center gap-1">
                        <span class="opacity-50 text-xs">🌐</span> Сайт на компанията
                    </a>
                <?php endif; ?>
                <?php if (empty($company['phone']) && empty($company['website_url'])): ?>
                    <span class="text-gray-400 italic">Няма въведени</span>
                <?php endif; ?>
            </div>
        </td>

        <td class="px-5 py-4">
            <div class="flex flex-col gap-1">
                <span class="text-gray-500 truncate max-w-45 text-xs" title="<?= htmlspecialchars($company['slug']) ?>">
                    <span class="opacity-50">🔗</span> /<?= htmlspecialchars($company['slug']) ?>
                </span>
                <span class="flex items-center gap-1 font-bold text-[10px] uppercase <?= $company['is_active'] ? 'text-emerald-600' : 'text-gray-400' ?>">
                    <span class="w-1.5 h-1.5 rounded-full <?= $company['is_active'] ? 'bg-emerald-500 animate-pulse' : 'bg-gray-300' ?>"></span>
                    <?= $company['is_active'] ? 'Активна' : 'Неактивна' ?>
                </span>
            </div>
        </td>

        <td class="px-5 py-4 text-right">
            <?php View::component('table-actions', 'admin/components', [
                'edit_url'   => "/admin/bus-companies/edit/{$company['id']}",
                'delete_url' => "/admin/bus-companies/delete/{$company['id']}",
                'name'       => $company['name']
            ]); ?>
        </td>
    </tr>
<?php endforeach;

View::component('table', 'admin/components', [
    'headers' => $headers,
    'slot' => ob_get_clean(),
    'attributes' => 'id="companies-table"'
]);
?>
<?php View::component('card', 'admin/components', ['slot' => ob_get_clean(), 'attributes' => 'p-0 overflow-hidden']); ?>

<div class="mt-5">
    <?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
</div>

<?php View::component('sortable-script', 'admin/components', [
    'tableId' => '#companies-table',
    'url'     => '/admin/bus-companies/update-order'
]); ?>