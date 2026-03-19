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

<?php View::component('search-hero', 'partials', [
    'backgroundImage' => $banner['image_url'] ?? '',
    'title'           => HelperService::getTranslation($banner, 'name', 'banner'),
    'breadcrumbs'   => $breadcrumbs,
    'searchValue'     => $searchTerm ?? '',
]); ?>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'       => $trains,
        'card_name'   => 'item-card',
        'link_key'    => 'website_url',
        'is_external' => true,
    ]); ?>
</main>