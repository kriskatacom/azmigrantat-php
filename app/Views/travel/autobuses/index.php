<?php

use App\Core\View;
use App\Services\HelperService;

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $banner['name']]
];
?>

<section>
    <?= View::component('show-banner', 'partials', ['banner' => $banner]) ?>
    
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                <?= $banner['name'] ?>
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
    <?php if (!empty($airports)): ?>
        <?php View::component('map', 'travel/autobuses/components', [
            'items'  => $airports,
            'height' => '500px',
            'zoom'   => 4
        ]); ?>
    <?php endif; ?>
</main>