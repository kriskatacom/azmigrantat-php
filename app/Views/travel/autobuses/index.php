<?php

use App\Core\View;
use App\Services\HelperService;

$bannerName = HelperService::getTranslation($banner, 'name');

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $bannerName]
];

foreach ($banners as &$item) {
    $item['entity_type'] = 'banner';
    if (!empty($item['href'])) {
        $item['href'] = HelperService::formatUrl($item['href']);
    }
}

if (!empty($airports)) {
    foreach ($airports as &$airport) {
        $airport['entity_type'] = 'airport';
        $airport['name'] = HelperService::getTranslation($airport, 'name');
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
        'items'       => $banners,
        'card_name'   => 'item-card',
        'show_search'     => false,
    ]); ?>

    <?php if (!empty($airports)): ?>
        <div class="mt-10">
            <?php View::component('map', 'travel/autobuses/components', [
                'items'  => $airports,
                'height' => '500px',
                'zoom'   => 4
            ]); ?>
        </div>
    <?php endif; ?>
</main>
