<?php

use App\Core\View;
use App\Services\HelperService;

$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $bannerName]
];

foreach ($banners as &$item) {
    $item['entity_type'] = 'banner';
    if (!empty($item['href'])) {
        $item['href'] = HelperService::formatUrl($item['href']);
    }
}

if (!empty($airports)) {
    foreach ($airports as &$airport) {
        $airport['entity_type'] = 'airport';
        $airport['name'] = HelperService::getTranslation($airport, 'name');
    }
}
?>

<section>
    <?php $banner['name'] = $bannerName;
    echo View::component('show-banner', 'partials', ['banner' => $banner]); ?>
    
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                <?= htmlspecialchars($bannerName) ?>
            </h1>
            <?php View::component('breadcrumbs', 'partials', [
                'items' => $breadcrumbs,
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'       => $banners,
        'card_name'   => 'item-card',
        'show_search' => false,
    ]); ?>

    <?php if (!empty($airports)): ?>
        <div class="mt-10">
            <?php View::component('map', 'travel/autobuses/components', [
                'items'  => $airports,
                'height' => '500px',
                'zoom'   => 4
            ]); ?>
        </div>
    <?php endif; ?>
</main>