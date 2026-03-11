<?php

use App\Core\View;
use App\Services\HelperService;

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $autobusesBanner['name'], 'href' => '/travel/autobuses'],
    ['label' => $countriesBanner['name'], 'href' => '/travel/autobuses/countries'],
    ['label' => $country['name']],
];
?>

<section>
    <?= View::component('show-banner', 'partials', ['banner' => $banner]) ?>

    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <?php if (!empty($banner['name'])): ?>
                <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                    <?= $banner['name'] ?>
                </h1>
            <?php endif; ?>

            <?php View::component('breadcrumbs', 'partials', [
                'items' => $breadcrumbs,
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $cities,
        'card_name' => 'item-card',
        'base_url' => '/travel/autobuses/countries/' . $country['slug']
    ]); ?>
</main>
