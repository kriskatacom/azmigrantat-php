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

<?php View::component('search-hero', 'partials', [
    'backgroundImage' => $banner['image_url'] ?? '',
    'title'           => HelperService::getTranslation($banner, 'name', 'banner'),
    'breadcrumbs'   => $breadcrumbs,
    'searchValue'     => $searchTerm ?? '',
]); ?>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'       => $taxis,
        'card_name'   => 'item-card',
        'link_key'    => 'website_url',
        'is_external' => true,
    ]); ?>
</main>