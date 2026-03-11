<?php

use App\Core\View;
use App\Services\HelperService;

$breadcrumbs = [
    ['label' => HelperService::trans('travel')]
];
?>

<section>
    <div class="aspect-video max-h-100 w-full">
        <img src="<?= !empty($banner['image_url']) ? $banner['image_url'] : '/assets/img/default-banner.jpg' ?>" class="w-full h-full object-cover transition-opacity duration-500" alt="<?= htmlspecialchars($banner['name'] ?? 'Banner Image') ?>">
    </div>
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                Пътувай с "Аз мигрантът"!
            </h1>
            <?php View::component('breadcrumbs', 'partials', [
                'items' => $breadcrumbs,
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $banners,
        'card_name' => 'item-card',
        'show_search' => false,
    ]); ?>
</main>
