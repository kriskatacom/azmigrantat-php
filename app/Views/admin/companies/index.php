<?php

use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Компании', 'url' => '/admin/companies']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Списък с компании',
        'button_label' => 'Нова компания',
        'button_url'   => '/admin/companies/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Компания'],
        ['label' => 'Локация', 'align' => 'center'],
        ['label' => 'Категория', 'align' => 'center'],
        ['label' => 'Собственик', 'align' => 'center'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($companies as $company): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $company['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/companies/edit/<?= $company['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $company['image_url'],
                        'alt' => $company['name']
                    ]); ?>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-700 italic group-hover:text-indigo-600 transition">
                            <?= htmlspecialchars($company['name']) ?>
                        </span>
                        <?php if (!empty($company['company_slogan'])): ?>
                            <span class="text-xs text-gray-400 font-normal">
                                <?= htmlspecialchars($company['company_slogan']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            </td>

            <td class="px-5 py-4 text-center">
                <div class="flex flex-col items-center gap-1">
                    <span class="inline-flex items-center justify-center bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs border border-blue-100">
                        🌍 <?= htmlspecialchars($company['country_name'] ?? 'Няма държава') ?>
                    </span>
                    <?php if (!empty($company['city_name'])): ?>
                        <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">
                            <?= htmlspecialchars($company['city_name']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </td>

            <td class="px-5 py-4 text-center">
                <span class="inline-flex items-center justify-center bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs border border-emerald-100">
                    📂 <?= htmlspecialchars($company['category_name'] ?? 'Без категория') ?>
                </span>
            </td>

            <td class="px-5 py-4 text-center">
                <?php if (!empty($company['owner_name'])): ?>
                    <div class="flex flex-col items-center">
                        <span class="text-xs font-semibold text-gray-700">
                            👤 <?= htmlspecialchars($company['owner_name']) ?>
                        </span>
                        <span class="text-[10px] text-indigo-500 font-medium lowercase italic">
                            <?= htmlspecialchars($company['owner_email']) ?>
                        </span>
                    </div>
                <?php else: ?>
                    <span class="text-xs text-gray-300 italic">Няма зададен</span>
                <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/companies/edit/{$company['id']}",
                    'delete_url' => "/admin/companies/delete/{$company['id']}",
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

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#companies-table',
        'url'     => '/admin/companies/update-order'
    ]);
    ?>
</div>

<?php if (isset($pagination)): ?>
    <div class="mt-6">
        <?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
    </div>
<?php endif; ?>