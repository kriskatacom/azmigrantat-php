<?php
/**
 * @var array $driver Данните за конкретния шофьор
 */
?>
<div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow flex items-start gap-5 border border-gray-100">
    <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-primary/20 shadow-sm shrink-0">
        <img alt="<?= htmlspecialchars($driver['username'] ?? $driver['name']) ?>" 
             src="<?= $driver['profile_image_url'] ?: '/images/default-avatar.jpg' ?>" 
             class="object-cover w-full h-full">
    </div>

    <div class="flex-1 space-y-2">
        <div class="flex justify-between items-start">
            <div class="text-xl font-bold text-gray-800">
                <?= htmlspecialchars($driver['username'] ?? $driver['name']) ?>
            </div>
            <?php if (!empty($driver['verified'])): ?>
                <span class="text-blue-500" title="Потвърден шофьор">
                    <i class="fas fa-check-circle text-sm"></i>
                </span>
            <?php endif; ?>
        </div>

        <div class="text-gray-600 text-sm line-clamp-3">
            <span class="font-medium text-primary-dark">
                <?= htmlspecialchars($driver['from_city_name'] ?? 'Няма инфо') ?> 
                <i class="fas fa-long-arrow-alt-right mx-1 text-gray-400"></i> 
                <?= htmlspecialchars($driver['to_city_name'] ?? 'Няма инфо') ?>
            </span>
            <br>
            <?php if (!empty($driver['travel_starts_at'])): ?>
                <i class="far fa-calendar-alt mr-1"></i> 
                <?= date('d M, H:i', strtotime($driver['travel_starts_at'])) ?>ч.
            <?php endif; ?>
            <p class="mt-1 italic text-gray-500">
                <?= htmlspecialchars(mb_strimwidth(strip_tags($driver['post_description'] ?? ''), 0, 100, "...")) ?>
            </p>
        </div>

        <div class="pt-2">
            <a href="/travel/shared-travel/drivers/<?= $driver['username'] ?>" 
               class="btn-primary w-full text-center block text-sm py-2 rounded-lg transition-colors">
                Преглед на обявата
            </a>
        </div>
    </div>
</div>
