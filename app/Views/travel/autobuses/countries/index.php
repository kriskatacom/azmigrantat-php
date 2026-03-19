<?php

use App\Core\View;
use App\Services\HelperService;

$autobusesBannerName = HelperService::getTranslation($autobusesBanner, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $autobusesBannerName, 'href' => '/travel/autobuses'],
    ['label' => $bannerName],
];
if (!empty($countries)) {
    foreach ($countries as &$country) {
        $country['entity_type'] = 'country';
        $country['name'] = HelperService::getTranslation($country, 'name');
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
        'items'     => $countries,
        'card_name' => 'item-card',
        'base_url'  => '/travel/autobuses/countries',
    ]); ?>
</main>
