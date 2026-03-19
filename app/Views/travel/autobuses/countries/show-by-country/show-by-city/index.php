<?php

use App\Core\View;
use App\Services\HelperService;

$autobusesName = HelperService::getTranslation($autobusesBanner, 'name');
$countriesBannerName = HelperService::getTranslation($countriesBanner, 'name');
$countryName = HelperService::getTranslation($country, 'name');
$cityName = HelperService::getTranslation($city, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $autobusesName, 'href' => '/travel/autobuses'],
    ['label' => $countriesBannerName, 'href' => '/travel/autobuses/countries'],
    ['label' => $countryName, 'href' => '/travel/autobuses/countries/' . $country['slug']],
    ['label' => $cityName],
];

if (!empty($autobuses)) {
    foreach ($autobuses as &$autobus) {
        $autobus['entity_type'] = 'autobus';
        
        if (!empty($autobus['website_url'])) {
            $autobus['website_url'] = HelperService::formatUrl($autobus['website_url']);
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
        'items'       => $autobuses,
        'card_name'   => 'item-card',
        'link_key'    => 'website_url',
        'is_external' => true
    ]); ?>
</main>
