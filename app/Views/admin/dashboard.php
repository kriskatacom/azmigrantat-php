<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Общо Потребители</p>
        <h2 class="text-3xl font-bold text-gray-800"><?= $stats['total_users'] ?></h2>
        <?php if ($stats['growth'] > 0): ?>
            <div class="mt-2 text-green-500 text-sm italic font-medium">
                ↑ <?= $stats['growth'] ?>% от последните 30 дни
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Активни Държави</p>
        <h2 class="text-3xl font-bold text-gray-800"><?= $stats['active_countries'] ?></h2>
    </div>
</div>

<div class="mt-10 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
        <h3 class="font-bold text-gray-800">Последни регистрации</h3>
        <a href="/admin/users" class="text-primary-light text-sm font-semibold hover:underline">Виж всички</a>
    </div>
    <div class="p-6">
        <?php if (!empty($recentActivities)): ?>
            <div class="divide-y divide-gray-100">
                <?php foreach ($recentActivities as $activity): ?>
                    <div class="py-3 flex justify-between items-center hover:bg-gray-50 transition px-2 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-light/10 flex items-center justify-center text-primary-dark text-xs font-bold">
                                <?= mb_substr($activity['name'], 0, 1) ?>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($activity['name']) ?></p>
                                <p class="text-xs text-gray-400"><?= $activity['email'] ?></p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">
                            <?= date('d.m.Y H:i', strtotime($activity['created_at'])) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-400 text-center py-10">Няма нови дейности за показване.</p>
        <?php endif; ?>
    </div>
</div>