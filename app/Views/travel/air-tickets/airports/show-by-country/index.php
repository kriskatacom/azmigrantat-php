<?php

use App\Core\View;
use App\Services\HelperService;

$countryName = HelperService::getTranslation($country, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');
$airTicketsName = HelperService::getTranslation($airTicketsBanner, 'name');
$airportsBannerName = HelperService::getTranslation($airportsBanner, 'name');

$breadcrumbs = [
    ['label' => $airTicketsName, 'href' => '/travel/air-tickets'],
    ['label' => $airportsBannerName, 'href' => '/travel/air-tickets/airports'],
    ['label' => $countryName],
];

foreach ($airports as &$airport) {
    $airport['entity_type'] = 'airport';

    if (!empty($airport['website_url'])) {
        $airport['website_url'] = HelperService::formatUrl($airport['website_url']);
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
        'items'       => $airports,
        'card_name'   => 'item-card',
        'link_key'    => 'website_url',
        'is_external' => true
    ]); ?>
</main>