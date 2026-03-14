<?php

use App\Core\View;
use App\Services\HelperService;

$airTicketsName = isset($airTicketsBanner) ? HelperService::getTranslation($airTicketsBanner, 'name', 'banner') : HelperService::trans('air_tickets');
$currentBannerName = isset($banner) ? HelperService::getTranslation($banner, 'name', 'banner') : '';

$breadcrumbs = [
    ['label' => $airTicketsName, 'href' => '/travel/air-tickets'],
    ['label' => $currentBannerName],
];
?>

<section>
    <?php if (isset($banner)): ?>
        <?= View::component('show-banner', 'partials', ['banner' => $banner]) ?>
    <?php endif; ?>
    
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                <?= htmlspecialchars($currentBannerName) ?>
            </h1>
            <?php View::component('breadcrumbs', 'partials', [
                'items' => $breadcrumbs,
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php 
    $translatedCountries = array_map(function($country) {
        $country['name'] = HelperService::getTranslation($country, 'name', 'country');
        return $country;
    }, $countries);

    View::component('load-more-grid', 'partials', [
        'items'       => $translatedCountries,
        'card_name'   => 'item-card',
        'show_search' => false,
        'base_url'    => '/travel/air-tickets/airports',
    ]); 
    ?>
</main>
