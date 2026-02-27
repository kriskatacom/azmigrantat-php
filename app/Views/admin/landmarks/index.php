<?php

use App\Core\View;
use App\Services\HelperService;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Забележителности', 'url' => '/admin/landmarks'],
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-bold text-gray-800 text-lg">Списък със забележителности</h3>
        <a href="/admin/landmarks/create" class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition">
            + Нова забележителност
        </a>
    </div>

    <?php


    $headers = [
        ['label' => 'Ред'],
        ['label' => 'Забележителност'],
        ['label' => 'Държава', 'align' => 'center'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($landmarks as $landmark):
        $imagePath = !empty($landmark['image_url']) ? $landmark['image_url'] : '/assets/images/placeholders/landmark.webp';

        $editUrl = "/admin/landmarks/edit/{$landmark['id']}";
        $deleteUrl = "/admin/landmarks/delete/{$landmark['id']}";
    ?>
        <tr class="hover:bg-gray-50 transition" data-id="<?= $landmark['id'] ?>">
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
                            alt="<?= htmlspecialchars($landmark['name']) ?>"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-semibold text-gray-700 italic group-hover:text-primary-dark group-hover:underline decoration-primary-light transition">
                            <?= htmlspecialchars($landmark['name']) ?>
                        </span>
                        <span class="text-xs text-gray-400 font-normal">
                            <?= htmlspecialchars($landmark['heading'] ?? '') ?>
                        </span>
                    </div>
                </a>
            </td>

            <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center justify-center bg-blue-50 text-blue-600 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">
                    🌍 <?= htmlspecialchars($landmark['country_name'] ?? 'Няма държава') ?>
                </span>
            </td>

            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                <a href="<?= $editUrl ?>" title="Редактиране" class="inline-block p-2 text-gray-400 hover:text-blue-500 hover:bg-gray-50 rounded-lg transition">
                    ✏️
                </a>

                <form action="<?= $deleteUrl ?>" method="POST" class="inline-block" onsubmit="return confirmDelete(event, '<?= htmlspecialchars($landmark['name']) ?>')">
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
        'attributes' => 'id="landmarks-table"'
    ]);

    View::component('sortable-script', 'admin/components', [
        'tableId' => '#landmarks-table',
        'url'     => '/admin/landmarks/update-order'
    ]);
    ?>
</div>

<?php if (isset($pagination)): ?>
    <?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
<?php endif; ?>

<script>
    function confirmDelete(event, name) {
        if (!confirm('Сигурни ли сте, че искате да изтриете "' + name + '"? Това действие е необратимо.')) {
            event.preventDefault();
            return false;
        }
        return true;
    }
</script>