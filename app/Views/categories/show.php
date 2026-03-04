<?php

use App\Core\View;
use App\Services\HelperService;

?>

<section>
    <div class="aspect-video max-h-100 w-full">
        <?php if (!empty($category)): ?>
            <img src="<?= $category['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($country['name']) ?>">
        <?php else: ?>
            <img src="<?= $city['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($country['name']) ?>">
        <?php endif; ?>
    </div>
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-semibold tracking-wide">
                <?php if (!empty($category)): ?>
                    <?= htmlspecialchars($category['name']) ?> в <?= $city['name']; ?> - <?= $country['name'] ?>
                <?php else: ?>
                    Информационен справочник на <?= htmlspecialchars($city['name']) ?> - <?= $country['name'] ?>
                <?php endif; ?>
            </h1>
            <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
        </div>
    </div>
</section>

<main>
    <?php if (!empty($items)): ?>
        <?php View::component('load-more-grid', 'partials', [
            'items'     => $items,
            'card_name' => 'country-card',
            'base_url'  => $base_url,
            'limit'     => 8
        ]); ?>
    <?php else: ?>
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="bg-gray-100 rounded-full p-6 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">Все още няма добавено съдържание</h2>
            <p class="text-gray-500 max-w-sm mb-8">
                В момента категорията е празна, но нашият екип работи по добавянето на нови обекти в <?= htmlspecialchars($city['name']) ?>.
            </p>
            <a href="/<?= $country['slug'] ?>/cities/<?= $city['slug'] ?>" class="inline-flex items-center gap-2 bg-primary-dark text-white px-6 py-3 rounded-lg font-medium hover:bg-opacity-90 transition-all shadow-md">
                <?php HelperService::icon('left-arrow-icon'); ?>
                Назад към града
            </a>
        </div>
    <?php endif; ?>
</main>
