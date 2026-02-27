<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-bold text-gray-800 text-lg">Списък с потребители</h3>
        <a href="/admin/users/create" class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition">
            + Нов потребител
        </a>
    </div>

    <?php

use App\Core\View;

    $headers = [
        ['label' => 'Име'],
        ['label' => 'Имейл'],
        ['label' => 'Роля'],
        ['label' => 'Статус', 'align' => 'center'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($users as $user): ?>
        <tr class="hover:bg-gray-200/50 transition">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-500 uppercase">
                        <?= mb_substr($user['name'], 0, 1) ?>
                    </div>
                    <span class="font-medium text-gray-700"><?= htmlspecialchars($user['name']) ?></span>
                </div>
            </td>
            <td class="px-6 py-4 text-gray-600 text-sm italic">
                <?= htmlspecialchars($user['email']) ?>
            </td>
            <td class="px-6 py-4">
                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' ?>">
                    <?= $user['role'] ?>
                </span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block w-2 h-2 rounded-full <?= $user['is_active'] ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]' : 'bg-red-400' ?>"></span>
            </td>
            <td class="px-6 py-4 text-right space-x-2">
                <button class="text-gray-400 hover:text-primary-light transition">✏️</button>
                <button class="text-gray-400 hover:text-red-500 transition">🗑️</button>
            </td>
        </tr>
    <?php endforeach;

    $tableBody = ob_get_clean();
    View::component('table', 'admin/components', [
        'headers' => $headers,
        'slot' => $tableBody
    ]);
    ?>
</div>

<?php View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>
