<?php

use App\Core\View;
use App\Services\HelperService;

$languages = HelperService::AVAILABLE_LANGUAGES;
$totalLangsCount = count($languages);
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Преводи', 'url' => '/admin/translations']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <?php View::component('page-header', 'admin/components', [
        'title'        => 'Езикови преводи',
        'button_label' => 'Добави нов ключ',
        'button_url'   => '/admin/translations/create'
    ]); ?>

    <div class="px-6 py-4 bg-white border-b border-gray-100">
        <form action="/admin/translations" method="GET" class="flex flex-col md:flex-row items-center gap-4">

            <div class="relative grow w-full">
                <input type="text"
                    name="search"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    placeholder="Търсене по ключ или превод..."
                    class="form-control">
            </div>

            <div class="relative w-full md:w-72">
                <select name="lang" onchange="this.form.submit()" class="form-control">
                    <?php foreach ($languages as $code => $lang): ?>
                        <option value="<?= $code ?>" <?= ($_GET['lang'] ?? 'bg') === $code ? 'selected' : '' ?>>
                            <?= $lang['flag'] ?> <?= $lang['name'] ?> (<?= strtoupper($code) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full md:w-100 flex itemsborder-gray-200 rounded-xl transition-all">
                <?php View::component('toggle', 'admin/components', [
                    'name'  => 'incomplete',
                    'label' => 'Само незавършени',
                    'value' => isset($_GET['incomplete']) && $_GET['incomplete'] === '1'
                ]); ?>
            </div>

        </form>
    </div>

    <?php
    $headers = [
        ['label' => 'Системен ключ'],
        ['label' => 'Прогрес'],
        ['label' => 'Превод (' . strtoupper($_GET['lang'] ?? 'bg') . ')'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($translations as $item):
        $completed = $item['completed_count'] ?? 0;
        $percentage = ($completed / $totalLangsCount) * 100;

        $colorClass = 'bg-gray-400';
        if ($percentage == 100) $colorClass = 'bg-green-500';
        elseif ($percentage > 50) $colorClass = 'bg-primary';
        elseif ($percentage > 0) $colorClass = 'bg-amber-500';
    ?>
        <tr class="hover:bg-gray-50 transition">
            <td class="px-5 py-4">
                <div class="flex flex-col">
                    <span class="text-sm font-mono font-bold text-primary-dark bg-primary-light/10 px-2 py-0.5 rounded self-start">
                        <?= htmlspecialchars($item['translation_key']) ?>
                    </span>
                    <span class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">
                        ID: #<?= str_pad($item['id'], 5, '0', STR_PAD_LEFT) ?>
                    </span>
                </div>
            </td>

            <td class="px-5 py-4">
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-xs font-bold text-gray-600"><?= $completed ?>/<?= $totalLangsCount ?></span>
                        <span class="text-[10px] font-bold text-gray-400"><?= round($percentage) ?>%</span>
                    </div>
                    <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full <?= $colorClass ?> transition-all duration-500" style="width: <?= $percentage ?>%"></div>
                    </div>
                </div>
            </td>

            <td class="px-5 py-4">
                <div class="max-w-md">
                    <span class="text-sm text-gray-700 line-clamp-2 italic">
                        "<?= htmlspecialchars($item['translation_value'] ?: '--- липсва ---') ?>"
                    </span>
                </div>
            </td>

            <td class="px-5 py-4 text-right">
                <?php View::component('table-actions', 'admin/components', [
                    'edit_url'   => "/admin/translations/edit/{$item['id']}",
                    'delete_url' => "/admin/translations/delete/{$item['id']}",
                    'name'       => $item['translation_key']
                ]); ?>
            </td>
        </tr>
    <?php endforeach;

    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => ob_get_clean(),
        'attributes' => 'id="translations-table"'
    ]);
    ?>
</div>

<div class="mt-6">
    <?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
</div>

<script>
    document.querySelector('input[name="incomplete"]').addEventListener('change', function() {
        this.form.submit();
    });
</script>
