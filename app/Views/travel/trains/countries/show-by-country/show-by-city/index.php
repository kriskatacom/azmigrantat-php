<?php

use App\Core\View;
use App\Services\HelperService;

$trainsName = HelperService::getTranslation($trainsBanner, 'name');
$countriesName = HelperService::getTranslation($countriesBanner, 'name');
$countryName = HelperService::getTranslation($country, 'name');
$cityName = HelperService::getTranslation($city, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $trainsName, 'href' => '/travel/trains'],
    ['label' => $countriesName, 'href' => '/travel/trains/countries'],
    ['label' => $countryName, 'href' => '/travel/trains/countries/' . $country['slug']],
    ['label' => $cityName],
];

if (!empty($trains)) {
    foreach ($trains as &$train) {
        $train['entity_type'] = 'train';
        if (!empty($train['website_url'])) {
            $train['website_url'] = HelperService::formatUrl($train['website_url']);
        }
    }
}
?>

<section>
    <?php 
    $banner['name'] = $bannerName;
    echo View::component('show-banner', 'partials', ['banner' => $banner]); 
    ?>

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
        'items'       => $trains,
        'card_name'   => 'item-card',
        'link_key'    => 'website_url',
        'is_external' => true
    ]); ?>
</main>
