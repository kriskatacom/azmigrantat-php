<?php
use App\Core\View;
use App\Services\HelperService;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Градове', 'url' => '/admin/cities'],
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h3 class="font-bold text-gray-800 text-lg">Списък с градове</h3>
        </div>
        <a href="/admin/cities/create" class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition shadow-sm">
            + Нов град
        </a>
    </div>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Град'],
        ['label' => 'Държава'],
        ['label' => 'SEO'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($cities as $city):
        $imagePath = !empty($city['image_url']) ? $city['image_url'] : '/assets/images/placeholders/city.webp';
        $editUrl = "/admin/cities/edit/{$city['id']}";
        $deleteUrl = "/admin/cities/delete/{$city['id']}";
    ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $city['id'] ?>">
            <td class="px-5 py-4 w-10">
                <div class="drag-handle text-gray-300 hover:text-gray-500 cursor-grab active:cursor-grabbing">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </div>
            </td>

            <td class="px-5 py-4">
                <a href="<?= $editUrl ?>" class="flex items-center gap-4 group">
                    <div class="w-32 h-20 rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50 shrink-0 group-hover:ring-2 group-hover:ring-primary-light transition-all">
                        <img src="<?= HelperService::getImage($imagePath) ?>" 
                             alt="<?= htmlspecialchars($city['name']) ?>"
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <span class="block font-semibold text-gray-700 group-hover:text-primary-dark group-hover:underline transition">
                            <?= htmlspecialchars($city['name']) ?>
                        </span>
                        <span class="text-[10px] text-gray-400 font-mono tracking-tighter uppercase">
                            ID: #<?= str_pad($city['id'], 4, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>
                </a>
            </td>

            <td class="px-5 py-4">
                <span class="text-sm text-gray-600 bg-blue-50 px-2 py-1 rounded-md border border-blue-100">
                    🌍 <?= htmlspecialchars($city['country_name'] ?? 'Няма държава') ?>
                </span>
            </td>

            <td class="px-5 py-4">
                <div class="flex flex-col gap-1">
                    <span class="text-gray-500 truncate max-w-75.5">
                        🔗 <?= htmlspecialchars($city['slug']) ?>
                    </span>
                    <?php if(!empty($city['heading'])): ?>
                        <span class="text-[10px] text-blue-400 italic">
                            H1: <?= htmlspecialchars($city['heading']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </td>

            <td class="px-5 py-4">
                <?php View::component('table-actions', 'admin/components', [
                    'editUrl'   => $editUrl,
                    'deleteUrl' => $deleteUrl,
                    'name'      => $city['name']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    $tableBody = ob_get_clean();

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => $tableBody,
        'attributes' => 'id="cities-table"'
    ]);
    
    View::component('sortable-script', 'admin/components', [
        'tableId' => '#cities-table',
        'url'     => '/admin/cities/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>