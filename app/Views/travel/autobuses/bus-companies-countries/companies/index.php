<?php

use App\Core\View;
use App\Services\HelperService;

$autobusesName = HelperService::getTranslation($autobusesBanner, 'name');
$countryName = HelperService::getTranslation($busCompaniesBanner, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $autobusesName, 'href' => '/travel/autobuses'],
    ['label' => $countryName, 'href' => '/travel/autobuses/bus-companies-countries'],
    ['label' => $bannerName],
];

if (!empty($companies)) {
    foreach ($companies as &$company) {
        $company['entity_type'] = 'bus_company';

        if (!empty($company['website_url'])) {
            $company['website_url'] = HelperService::formatUrl($company['website_url']);
        }
    }
}
?>

<?php View::component('search-hero', 'partials', [
    'backgroundImage' => $banner['image_url'] ?? '',
    'title'           => HelperService::getTranslation($banner, 'name', 'banner'),
    'breadcrumbs'     => $breadcrumbs,
    'searchValue'     => $searchTerm ?? '',
]); ?>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'       => $companies,
        'card_name'   => 'item-card',
        'link_key'    => 'website_url',
        'is_external' => true,
    ]); ?>
</main>