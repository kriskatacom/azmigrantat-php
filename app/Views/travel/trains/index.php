<?php

use App\Core\View;
use App\Services\HelperService;

$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $bannerName]
];

if (!empty($banners)) {
    foreach ($banners as &$item) {
        $item['entity_type'] = 'banner';
        
        if (!empty($item['href'])) {
            $item['href'] = HelperService::formatUrl($item['href']);
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
        'items'       => $banners,
        'card_name'   => 'item-card',
        'show_search' => false,
    ]); ?>
</main>
