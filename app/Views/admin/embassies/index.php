<?php
use App\Core\View;
use App\Services\HelperService;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Посолства', 'url' => '/admin/embassies'],
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <div>
            <h3 class="font-bold text-gray-800 text-lg">Списък с посолства</h3>
        </div>
        <a href="/admin/embassies/create" class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition shadow-sm">
            + Ново посолство
        </a>
    </div>

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
        $imagePath = !empty($embassy['logo']) ? $embassy['logo'] : (!empty($embassy['image_url']) ? $embassy['image_url'] : '/assets/images/placeholders/embassy.webp');
        $editUrl = "/admin/embassies/edit/{$embassy['id']}";
        $deleteUrl = "/admin/embassies/delete/{$embassy['id']}";
    ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $embassy['id'] ?>">
            <td class="px-6 py-4 w-10">
                <div class="drag-handle text-gray-300 hover:text-gray-500 cursor-grab active:cursor-grabbing">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </div>
            </td>

            <td class="px-6 py-4">
                <a href="<?= $editUrl ?>" class="flex items-center gap-4 group">
                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-50 shrink-0 group-hover:ring-2 group-hover:ring-primary-light transition-all flex items-center justify-center p-1">
                        <img src="<?= HelperService::getImage($imagePath) ?>" 
                             alt="<?= htmlspecialchars($embassy['name']) ?>"
                             class="max-w-full max-h-full object-contain">
                    </div>
                    <div>
                        <span class="block font-semibold text-gray-700 group-hover:text-primary-dark group-hover:underline transition">
                            <?= htmlspecialchars($embassy['name']) ?>
                        </span>
                        <span class="text-[10px] text-gray-400 font-mono tracking-tighter uppercase">
                            ID: #<?= str_pad($embassy['id'], 4, '0', STR_PAD_LEFT) ?>
                        </span>
                    </div>
                </a>
            </td>

            <td class="px-6 py-4">
                <span class="text-sm text-gray-600 bg-blue-50 px-2 py-1 rounded-md border border-blue-100">
                    🌍 <?= htmlspecialchars($embassy['country_name'] ?? 'Няма държава') ?>
                </span>
            </td>

            <td class="px-6 py-4">
                <div class="flex flex-col gap-1">
                    <?php if(!empty($embassy['email'])): ?>
                        <span class="text-[11px] text-gray-500 flex items-center gap-1">
                            📧 <?= htmlspecialchars($embassy['email']) ?>
                        </span>
                    <?php endif; ?>
                    <?php if(!empty($embassy['phone'])): ?>
                        <span class="text-[11px] text-gray-500 flex items-center gap-1">
                            📞 <?= htmlspecialchars($embassy['phone']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </td>

            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                <a href="<?= $editUrl ?>" title="Редактиране" class="inline-block p-2 text-gray-400 hover:text-blue-500 hover:bg-gray-50 rounded-lg transition">
                    ✏️
                </a>

                <form action="<?= $deleteUrl ?>" method="POST" class="inline-block" onsubmit="return confirmDelete(event, '<?= $embassy['name'] ?? 'това посолство' ?>')">
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
        'attributes' => 'id="embassies-table"'
    ]);
    
    View::component('sortable-script', 'admin/components', [
        'tableId' => '#embassies-table',
        'url'     => '/admin/embassies/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>

<script>
    function confirmDelete(event, name) {
        if (!confirm('Сигурни ли сте, че искате да изтриете посолството на "' + name + '"?')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>