<?php

use App\Core\View;
use App\Services\HelperService;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Държави', 'url' => '/admin/countries'],
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-bold text-gray-800 text-lg">Списък с държави</h3>
        <a href="/admin/countries/create" class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition">
            + Нова държава
        </a>
    </div>

    <?php

    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Държава'],
        ['label' => 'Градове', 'align' => 'center'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($countries as $country):
        $imagePath = !empty($country['image_url']) ? $country['image_url'] : '/assets/images/placeholders/country.webp';
    ?>
        <?php $editUrl = "/admin/countries/edit/{$country['id']}"; ?>
        <?php $deleteUrl = "/admin/countries/delete/{$country['id']}"; ?>

        <tr class="hover:bg-gray-50 transition" data-id="<?= $country['id'] ?>">
            <td class="px-6 py-4 w-10">
                <div class="drag-handle text-gray-300 hover:text-gray-500 cursor-grab active:cursor-grabbing">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                    </svg>
                </div>
            </td>

            <td class="px-6 py-4">
                <a href="<?= $editUrl ?>" class="flex items-center gap-4 group">
                    <div class="w-32 h-20 rounded-md overflow-hidden border border-gray-100 shadow-sm bg-gray-50 shrink-0 group-hover:ring-2 group-hover:ring-primary-light transition-all">
                        <img src="<?= HelperService::getImage($imagePath) ?>"
                            alt="<?= htmlspecialchars($country['name']) ?>"
                            class="w-full h-full object-cover">
                    </div>
                    <span class="font-semibold text-gray-700 italic group-hover:text-primary-dark group-hover:underline decoration-primary-light transition">
                        <?= htmlspecialchars($country['name']) ?>
                    </span>
                </a>
            </td>

            <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center justify-center bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-full">
                    <?= $country['cities_count'] ?? 0 ?> градове
                </span>
            </td>

            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                <a href="<?= $editUrl ?>" title="Редактиране" class="inline-block p-2 text-gray-400 hover:text-blue-500 hover:bg-gray-50 rounded-lg transition">
                    ✏️
                </a>

                <form action="<?= $deleteUrl ?>" method="POST" class="inline-block" onsubmit="return confirmDelete(event, '<?= $country['name'] ?? 'този запис' ?>')">
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
        'attributes' => 'id="countries-table"'
    ]);
    
    View::component('sortable-script', 'admin/components', [
        'tableId' => '#countries-table',
        'url'     => '/admin/countries/update-order'
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>

<script>
    function confirmDelete(event, name) {
        if (!confirm('Сигурни ли сте, че искате да изтриете "' + name + '"? Това действие е необратимо.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>