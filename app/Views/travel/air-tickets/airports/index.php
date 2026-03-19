<?php

use App\Core\View;
use App\Services\HelperService;

$airTicketsName = isset($airTicketsBanner) ? HelperService::getTranslation($airTicketsBanner, 'name', 'banner') : HelperService::trans('air_tickets');
$currentBannerName = isset($banner) ? HelperService::getTranslation($banner, 'name', 'banner') : '';

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $airTicketsName, 'href' => '/travel/air-tickets'],
    ['label' => $currentBannerName],
];
?>

<?php View::component('search-hero', 'partials', [
    'backgroundImage' => $banner['image_url'] ?? '',
    'title'           => HelperService::getTranslation($banner, 'name', 'banner'),
    'breadcrumbs'   => $breadcrumbs,
    'searchValue'     => $searchTerm ?? '',
]); ?>

<main>
    <?php 
    $translatedCountries = array_map(function($country) {
        $country['name'] = HelperService::getTranslation($country, 'name', 'country');
        return $country;
    }, $countries);

    View::component('load-more-grid', 'partials', [
        'items'       => $translatedCountries,
        'card_name'   => 'item-card',
        'base_url'    => '/travel/air-tickets/airports',
    ]); 
    ?>
</main>
