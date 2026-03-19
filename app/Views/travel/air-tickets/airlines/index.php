<?php

use App\Core\View;
use App\Services\HelperService;

$airTicketsName = HelperService::getTranslation($airTicketsBanner, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $airTicketsName, 'href' => '/travel/air-tickets'],
    ['label' => $bannerName],
];

foreach ($airlines as &$airline) {
    $airline['entity_type'] = 'airline';
    
    if (!empty($airline['website_url'])) {
        $airline['website_url'] = HelperService::formatUrl($airline['website_url']);
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
        'items'       => $airlines,
        'card_name'   => 'item-card',
        'base_url'    => '/travel/air-tickets/airlines',
        'link_key'    => 'website_url',
        'is_external' => true,
    ]); ?>
</main>
