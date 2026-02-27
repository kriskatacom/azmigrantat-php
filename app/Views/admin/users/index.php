<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-bold text-gray-800 text-lg">Списък с потребители</h3>
        <a href="/admin/users/create" class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition">
            + Нов потребител
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold">Име</th>
                    <th class="px-6 py-4 font-semibold">Имейл</th>
                    <th class="px-6 py-4 font-semibold">Роля</th>
                    <th class="px-6 py-4 font-semibold text-center">Статус</th>
                    <th class="px-6 py-4 font-semibold text-right">Действия</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50/50 transition">
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
                            <?php if ($user['is_active']): ?>
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                            <?php else: ?>
                                <span class="inline-block w-2 h-2 rounded-full bg-red-400"></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button class="text-gray-400 hover:text-primary-light transition">✏️</button>
                            <button class="text-gray-400 hover:text-red-500 transition">🗑️</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php \App\Core\View::component('pagination', 'admin/components', ['pagination' => $pagination]); ?>