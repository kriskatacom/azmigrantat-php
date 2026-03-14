<?php

use App\Core\View;
use App\Services\HelperService;

$autobusesName = HelperService::getTranslation($autobusesBanner, 'name');
$countriesBannerName = HelperService::getTranslation($countriesBanner, 'name');
$countryName = HelperService::getTranslation($country, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $autobusesName, 'href' => '/travel/autobuses'],
    ['label' => $countriesBannerName, 'href' => '/travel/autobuses/countries'],
    ['label' => $countryName],
];
if (!empty($cities)) {
    foreach ($cities as &$city) {
        $city['entity_type'] = 'city';
        $city['name'] = HelperService::getTranslation($city, 'name');
    }
}
?>

<section>
    <?php $banner['name'] = $bannerName;
    echo View::component('show-banner', 'partials', ['banner' => $banner]); ?>

    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <?php if (!empty($bannerName)): ?>
                <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                    <?= htmlspecialchars($bannerName) ?>
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
        'base_url'  => '/travel/autobuses/countries/' . $country['slug']
    ]); ?>
</main>