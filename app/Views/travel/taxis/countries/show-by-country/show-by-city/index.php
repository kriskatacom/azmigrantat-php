<?php

use App\Core\View;
use App\Services\HelperService;

$taxisBannerName = HelperService::getTranslation($taxisBanner, 'name');
$countriesBannerName = HelperService::getTranslation($countriesBanner, 'name');
$countryName = HelperService::getTranslation($country, 'name');
$cityName = HelperService::getTranslation($city, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $taxisBannerName, 'href' => '/travel/taxis'],
    ['label' => $countriesBannerName, 'href' => '/travel/taxis/countries'],
    ['label' => $countryName, 'href' => '/travel/taxis/countries/' . $country['slug']],
    ['label' => $cityName],
];

if (!empty($taxis)) {
    foreach ($taxis as &$taxi) {
        $taxi['entity_type'] = 'taxi';
        if (!empty($taxi['website_url'])) {
            $taxi['website_url'] = HelperService::formatUrl($taxi['website_url']);
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
        'items'       => $taxis,
        'card_name'   => 'item-card',
        'link_key'    => 'website_url',
        'is_external' => true,
    ]); ?>
</main>
