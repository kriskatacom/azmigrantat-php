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
    ]); ?>
</main>
