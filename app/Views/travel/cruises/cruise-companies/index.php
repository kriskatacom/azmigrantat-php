<?php

use App\Core\View;
use App\Services\HelperService;

$cruisesName = HelperService::getTranslation($cruisesBanner, 'name');
$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $cruisesName, 'href' => '/travel/cruises'],
    ['label' => $bannerName]
];

if (!empty($cruises)) {
    foreach ($cruises as &$cruise) {
        $cruise['entity_type'] = 'cruise';
        
        if (!empty($cruise['website_url'])) {
            $cruise['website_url'] = HelperService::formatUrl($cruise['website_url']);
        }
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
        'items'       => $cruises,
        'card_name'   => 'item-card',
        'link_key'    => 'website_url',
        'show_search' => false,
        'is_external' => true
    ]); ?>
</main>