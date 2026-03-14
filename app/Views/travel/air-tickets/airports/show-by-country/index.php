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

<section>
    <?php $banner['name'] = $bannerName;
    View::component('show-banner', 'partials', ['banner' => $banner]); ?>
    
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
        'items'       => $airports,
        'card_name'   => 'item-card',
        'show_search' => false,
        'link_key'    => 'website_url',
        'is_external' => true
    ]); ?>
</main>
