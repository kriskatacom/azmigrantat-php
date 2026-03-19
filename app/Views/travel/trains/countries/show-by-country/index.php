<?php

use App\Core\View;
use App\Services\HelperService;

$trainsName = HelperService::getTranslation($trainsBanner, 'name');
$countriesName = HelperService::getTranslation($countriesBanner, 'name');
$countryName = HelperService::getTranslation($country, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $trainsName, 'href' => '/travel/trains'],
    ['label' => $countriesName, 'href' => '/travel/trains/countries'],
    ['label' => $countryName],
];

if (!empty($cities)) {
    foreach ($cities as &$city) {
        $city['entity_type'] = 'city';
        $city['name'] = HelperService::getTranslation($city, 'name');
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
        'items'     => $cities,
        'card_name' => 'item-card',
        'base_url'  => '/travel/trains/countries/' . $country['slug'],
    ]); ?>
</main>
