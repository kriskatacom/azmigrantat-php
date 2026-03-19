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

<?php View::component('search-hero', 'partials', [
    'backgroundImage' => $banner['image_url'] ?? '',
    'title'           => HelperService::getTranslation($banner, 'name', 'banner'),
    'breadcrumbs'   => $breadcrumbs,
    'searchValue'     => $searchTerm ?? '',
]); ?>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'       => $cruises,
        'card_name'   => 'item-card',
        'link_key'    => 'website_url',
        'is_external' => true
    ]); ?>
</main>