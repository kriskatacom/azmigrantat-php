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

<section>
    <?php $banner['name'] = $bannerName;
    echo View::component('show-banner', 'partials', ['banner' => $banner]); ?>
    
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                <?= htmlspecialchars($bannerName) ?>
            </h1>
            <?php View::component('breadcrumbs', 'partials', [
                'items' => $breadcrumbs,
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'       => $airlines,
        'card_name'   => 'item-card',
        'show_search' => false,
        'base_url'    => '/travel/air-tickets/airlines',
        'link_key'    => 'website_url',
        'is_external' => true
    ]); ?>
</main>
