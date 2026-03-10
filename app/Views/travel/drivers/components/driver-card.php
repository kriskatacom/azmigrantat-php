<?php

$type = $type ?? 'drivers';
$driver = $driver ?? [];
?>

<?php if ($type === 'drivers' && !empty($driver)): ?>
    <!-- Шофьори -->
    <div class="bg-primary-dark border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-5 flex items-start gap-5">
            <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-primary/20 shadow-sm shrink-0">
                <img alt="<?= htmlspecialchars($driver['username'] ?? $driver['name']) ?>"
                    src="<?= $driver['profile_image_url'] ?: '/images/default-avatar.jpg' ?>"
                    class="object-cover w-full h-full">
            </div>

            <div class="flex-1 space-y-2">
                <div class="flex justify-between items-start">
                    <div class="text-white text-xl font-bold"><?= htmlspecialchars($driver['name']) ?></div>
                    <?php if (!empty($driver['verified'])): ?>
                        <span class="text-blue-500" title="Потвърден шофьор">
                            <i class="fas fa-check-circle text-sm"></i>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-2 text-white">
                    <div>
                        <i class="fa-solid fa-user"></i>
                        <?= $driver['age'] ?> г.
                    </div>
                    <div>
                        <i class="fa-solid fa-car"></i>
                        <?= $driver['car_model'] ?>
                    </div>
                </div>

                <div class="text-white text-sm line-clamp-3">
                    <?php if (!empty($driver['description'])): ?>
                        <p class="mt-1 italic text-gray-500 line-clamp-3">
                            <?= $driver['description'] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <a href="/travel/shared-travel/drivers/<?= $driver['username'] ?>" class="btn-primary w-full text-center block">
                    Виж профила
                </a>
            </div>
        </div>
    </div>

<?php elseif ($type === 'posts' && !empty($driver) && $driver['driver_travel_status'] !== 'not_traveling'): ?>
    <!-- Обяви -->
    <?php
    $isDeparture = $driver['driver_travel_status'] === 'departure';
    $details = $isDeparture ? ($driver['travel_departure_details'] ?? '') : ($driver['travel_return_details'] ?? '');
    $image = $isDeparture ? ($driver['travel_departure_image'] ?? '') : ($driver['travel_return_image'] ?? '');
    $label = $isDeparture ? 'Отпътуване' : 'Връщане';
    ?>

    <?php if (!empty($image) || !empty($details)): ?>
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <?php if (!empty($image)): ?>
                <a href="/travel/shared-travel/drivers/<?= $driver['username'] ?>">
                    <img src="<?= $image ?>" alt="<?= $label ?>" class="w-full h-50 object-cover">
                </a>
            <?php else: ?>
                <div class="p-5 flex items-start gap-5">
                    <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-primary/20 shadow-sm shrink-0">
                        <img alt="<?= htmlspecialchars($driver['username'] ?? $driver['name']) ?>"
                            src="<?= $driver['profile_image_url'] ?: '/images/default-avatar.jpg' ?>"
                            class="object-cover w-full h-full">
                    </div>

                    <div class="flex-1 space-y-2">
                        <div class="flex justify-between items-start">
                            <div class="text-xl font-bold text-gray-800"><?= htmlspecialchars($driver['name']) ?></div>
                            <?php if (!empty($driver['verified'])): ?>
                                <span class="text-blue-500" title="Потвърден шофьор">
                                    <i class="fas fa-check-circle text-sm"></i>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="text-white text-sm line-clamp-3">
                            <?php if ($details): ?>
                                <p class="mt-1 italic text-gray-500">
                                    <?= $details ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <a href="/travel/shared-travel/drivers/<?= $driver['username'] ?>" class="btn-primary w-full text-center block">
                            Преглед на обявата
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>