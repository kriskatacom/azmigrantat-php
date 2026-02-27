<?php
use App\Core\View;
use App\Services\HelperService;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Круизи', 'url' => '/admin/cruises'],
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h3 class="font-bold text-gray-800 text-lg">Круизни компании</h3>
        </div>
        <a href="/admin/cruises/create" class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition shadow-sm">
            + Нова компания
        </a>
    </div>

    <?php
    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Компания'],
        ['label' => 'Уебсайт'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($cruises as $cruise):
        $imagePath = !empty($cruise['image_url']) ? $cruise['image_url'] : '/assets/images/placeholders/cruise.webp';
        $editUrl = "/admin/cruises/edit/{$cruise['id']}";
        $deleteUrl = "/admin/cruises/delete/{$cruise['id']}";
    ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $cruise['id'] ?>">
            <td class="px-6 py-4 w-10">
                <div class="drag-handle text-gray-300 hover:text-gray-500 cursor-grab active:cursor-grabbing">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </div>
            </td>

            <td class="px-6 py-4">
                <a href="<?= $editUrl ?>" class="flex items-center gap-4 group">
                    <div class="w-20 h-12 rounded-lg overflow-hidden border border-gray-100 shadow-sm bg-white shrink-0 group-hover:ring-2 group-hover:ring-primary-light transition-all flex items-center justify-center p-1">
                        <img src="<?= HelperService::getImage($imagePath) ?>" 
                             alt="<?= htmlspecialchars($cruise['name']) ?>"
                             class="max-w-full max-h-full object-contain">
                    </div>
                    <span class="font-semibold text-gray-700 group-hover:text-primary-dark transition">
                        <?= htmlspecialchars($cruise['name']) ?>
                    </span>
                </a>
            </td>

            <td class="px-6 py-4">
                <?php if(!empty($cruise['website_url'])): ?>
                    <a href="<?= $cruise['website_url'] ?>" target="_blank" class="text-blue-500 hover:underline text-sm flex items-center gap-1">
                        🌐 Посети сайта
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                <?php else: ?>
                    <span class="text-gray-300 text-xs italic">Няма линк</span>
                <?php endif; ?>
            </td>

            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                <a href="<?= $editUrl ?>" title="Редактиране" class="inline-block p-2 text-gray-400 hover:text-blue-500 hover:bg-gray-50 rounded-lg transition">
                    ✏️
                </a>

                <form action="<?= $deleteUrl ?>" method="POST" class="inline-block" onsubmit="return confirmDelete(event, '<?= $cruise['name'] ?>')">
                    <button type="submit" title="Изтриване" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                        🗑️
                    </button>
                </form>
            </td>
        </tr>
    <?php endforeach;

    $tableBody = ob_get_clean();

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => $tableBody,
        'attributes' => 'id="cruises-table"'
    ]);
    
    View::component('sortable-script', 'admin/components', [
        'tableId' => '#cruises-table',
        'url'     => '/admin/cruises/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination ?? null]); ?>

<script>
    function confirmDelete(event, name) {
        if (!confirm('Сигурни ли сте, че искате да изтриете компанията "' + name + '"?')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>