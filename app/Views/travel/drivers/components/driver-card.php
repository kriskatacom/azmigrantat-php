<?php

use App\Services\HelperService;

$type = $type ?? 'drivers';
$driver = $driver ?? [];
?>

<?php if ($type === 'drivers' && !empty($driver)): ?>
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
                        <span class="text-blue-500" title="<?= HelperService::trans('verified_driver_alt') ?>">
                            <i class="fas fa-check-circle text-sm"></i>
                        </span>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-2 text-white">
                    <div>
                        <i class="fa-solid fa-user"></i>
                        <?= $driver['age'] ?> <?= HelperService::trans('years_short') ?>
                    </div>
                    <?php if (!empty($driver['car_model'])): ?>
                        <div>
                            <i class="fa-solid fa-car"></i>
                            <?= htmlspecialchars($driver['car_model']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="text-white text-sm line-clamp-3">
                    <?php if (!empty($driver['description'])): ?>
                        <p class="mt-1 italic text-gray-200 line-clamp-3">
                            <?= $driver['description'] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <a href="<?= HelperService::url('/travel/shared-travel/drivers/' . $driver['username']) ?>" class="btn-primary w-full text-center block">
                    <?= HelperService::trans('view_profile') ?>
                </a>
            </div>
        </div>
    </div>

<?php elseif ($type === 'posts' && !empty($driver) && $driver['driver_travel_status'] !== 'not_traveling'): ?>
    <?php
    $isDeparture = $driver['driver_travel_status'] === 'departure';
    $details = $isDeparture ? ($driver['travel_departure_details'] ?? '') : ($driver['travel_return_details'] ?? '');
    $image = $isDeparture ? ($driver['travel_departure_image'] ?? '') : ($driver['travel_return_image'] ?? '');
    $label = $isDeparture ? HelperService::trans('travel_departure') : HelperService::trans('travel_return');
    ?>

    <?php if (!empty($image) || !empty($details)): ?>
        <div class="bg-primary-dark border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <?php if (!empty($image)): ?>
                <a href="<?= HelperService::url('/travel/shared-travel/drivers/' . $driver['username']) ?>" class="relative block">
                    <img src="<?= $image ?>" alt="<?= $label ?>" class="w-full h-50 object-cover">
                    <div class="absolute top-2 left-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                        <?= $label ?>
                    </div>
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
                            <div class="text-white text-xl font-bold"><?= htmlspecialchars($driver['name']) ?></div>
                            <?php if (!empty($driver['verified'])): ?>
                                <span class="text-blue-500" title="<?= HelperService::trans('verified_driver_alt') ?>">
                                    <i class="fas fa-check-circle text-sm"></i>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="text-white text-sm line-clamp-3">
                            <span class="text-xs uppercase font-bold text-gray-400 block mb-1"><?= $label ?></span>
                            <?php if ($details): ?>
                                <p class="mt-1 italic text-gray-200">
                                    <?= $details ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <a href="<?= HelperService::url('/travel/shared-travel/drivers/' . $driver['username']) ?>" class="btn-primary w-full text-center block">
                            <?= HelperService::trans('view_post') ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
