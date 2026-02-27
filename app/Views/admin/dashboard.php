<?php

use App\Services\HelperService;
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Общо Потребители</p>
        <h2 class="text-3xl font-bold text-gray-800"><?= $stats['total_users'] ?></h2>

        <?php
        if ($stats['growth'] > 0): ?>
            <div class="mt-2 text-green-500 text-sm italic font-medium">
                ↑ <?= $stats['growth'] ?>% от последните 30 дни
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Забележителности</p>
        <div class="flex items-baseline gap-2">
            <h2 class="text-3xl font-bold text-gray-800"><?= $stats['total_landmarks'] ?></h2>
            <span class="text-xs text-gray-400 font-normal">общо</span>
        </div>
        <div class="mt-2 text-green-500 text-sm font-medium">
            🏛️ <?= $stats['active_landmarks'] ?> от <?= $stats['total_landmarks'] ?> са активни в момента
        </div>
    </div>

    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-medium">Активни Държави</p>
        <h2 class="text-3xl font-bold text-gray-800"><?= $stats['total_countries'] ?></h2>
        <div class="mt-2 text-gray-400 text-sm">
            🌍 Управление на локации
        </div>
    </div>

    <div class="bg-primary-dark p-5 rounded-xl shadow-sm flex flex-col justify-center">
        <a href="/admin/landmarks/create" class="flex items-center justify-center gap-2 bg-white text-primary-dark px-4 py-2 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg text-sm">
            <span>+</span> Добави Обект
        </a>
    </div>
</div>

<div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-5">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span> Последни регистрации
            </h3>
            <a href="/admin/users" class="text-xs font-bold hover:underline">Виж всички</a>
        </div>
        <div class="py-2 px-5">
            <?php if (!empty($recentUsers)): ?>
                <div class="divide-y divide-gray-50">
                    <?php foreach ($recentUsers as $activity): ?>
                        <div class="py-3 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs font-bold border border-gray-200">
                                    <?= mb_substr($activity['name'], 0, 1) ?>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($activity['name']) ?></p>
                                    <p class="text-[11px] text-gray-400"><?= $activity['email'] ?></p>
                                </div>
                            </div>
                            <span class="text-[10px] bg-gray-50 px-2 py-1 rounded text-gray-400 font-mono">
                                <?= date('d.m.Y', strtotime($activity['created_at'])) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-400 text-center py-6 text-sm italic">Няма нови потребители.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Нови забележителности
            </h3>
            <a href="/admin/landmarks" class="text-xs font-bold hover:underline">Управление</a>
        </div>
        <div class="py-2 px-5">
            <?php if (!empty($recentLandmarks)): ?>
                <div class="divide-y divide-gray-50">
                    <?php foreach ($recentLandmarks as $landmark): ?>
                        <div class="py-3 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-8 rounded-md bg-gray-100 overflow-hidden border border-gray-200">
                                    <img src="<?= HelperService::getImage($landmark['image_url']) ?>" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($landmark['name']) ?></p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">
                                        <?= $landmark['is_active'] ? '<span class="text-green-500">Активна</span>' : '<span class="text-red-400">Чернова</span>' ?>
                                    </p>
                                </div>
                            </div>
                            <a href="/admin/landmarks/edit/<?= $landmark['id'] ?>" class="p-1.5 hover:bg-gray-100 rounded-lg transition text-gray-400 hover:text-green-500">
                                ✏️
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-400 text-center py-6 text-sm italic">Няма добавени забележителности.</p>
            <?php endif; ?>
        </div>
    </div>
</div>