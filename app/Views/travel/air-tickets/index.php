<?php

use App\Core\View;
use App\Services\HelperService;

$breadcrumbs = [
    ['label' => HelperService::trans('travel'), 'href' => '/travel'],
    ['label' => $banner['name']]
];
?>

<?php View::component('search-hero', 'partials', [
    'backgroundImage' => $banner['image_url'] ?? '',
    'title'           => HelperService::getTranslation($banner, 'name', 'banner'),
    'breadcrumbs'   => $breadcrumbs,
    'searchValue'     => $searchTerm ?? '',
]); ?>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $banners,
        'card_name' => 'item-card',
        'show_search' => false,
    ]); ?>
    <?php if (!empty($airports)): ?>
        <?php View::component('map', 'travel/air-tickets/components', [
            'items'  => $airports,
            'height' => '500px',
            'zoom'   => 4
        ]); ?>
    <?php endif; ?>
</main>