<?php
use App\Core\View;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Круизи', 'url' => '/admin/cruises']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Круизни компании',
        'button_label' => 'Нова компания',
        'button_url'   => '/admin/cruises/create'
    ]); ?>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Компания'],
        ['label' => 'Уебсайт'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($cruises as $cruise): ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $cruise['id'] ?>">
            <td class="px-5 py-4 w-10">
                <?php View::component('drag-handle', 'admin/components'); ?>
            </td>

            <td class="px-5 py-4">
                <a href="/admin/cruises/edit/<?= $cruise['id'] ?>" class="flex items-center gap-4 group">
                    <?php View::component('table-image', 'admin/components', [
                        'src' => $cruise['image_url'],
                        'alt' => $cruise['name']
                    ]); ?>
                    <span class="font-semibold text-gray-700 italic group-hover:text-primary-dark"><?= htmlspecialchars($cruise['name']) ?></span>
                </a>
            </td>

            <td class="px-5 py-4">
                <?php if(!empty($cruise['website_url'])): ?>
                    <a href="<?= $cruise['website_url'] ?>" target="_blank" 
                       class="text-indigo-500 hover:text-indigo-700 text-sm flex items-center gap-1 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        Посети сайта
                    </a>
                <?php else: ?>
                    <span class="text-gray-300 text-xs italic">Няма линк</span>
                <?php endif; ?>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/cruises/edit/{$cruise['id']}",
                    'delete_url' => "/admin/cruises/delete/{$cruise['id']}",
                    'name'       => $cruise['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="cruises-table"'
    ]);
    
    View::component('sortable-script', 'admin/components', [
        'tableId' => '#cruises-table',
        'url'     => '/admin/cruises/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination ?? null]); ?>