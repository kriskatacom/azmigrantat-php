<?php

use App\Core\View;
use App\Services\HelperService;

$activeScope = $_GET['scope'] ?? '';
$countryName = HelperService::getTranslation($country, 'name', 'country');
$cityName    = HelperService::getTranslation($city, 'name', 'city');
$catName     = !empty($category) ? HelperService::getTranslation($category, 'name', 'category') : '';

$mainTitle = !empty($category) ? "{$catName} " . HelperService::trans('in') . " {$cityName} - {$countryName}" : HelperService::trans('info_guide_of') . " {$cityName}";
$imageUrl = !empty($category) ? $category['image_url'] : $city['image_url'];

$isMainCityPage = HelperService::isCityMainPage($city['slug']);
$isShowMainCityItems = $isMainCityPage && empty($_GET['show-categories']);

$mainCityItems = [
    [
        'name' => HelperService::trans('info_guide_of') . ' ' . $city['name'],
        'image_url' => $city['image_url'],
        'slug' => '/?show-categories=1'
    ],
    [
        'name' => HelperService::trans('municipal_cities'),
        'image_url' => $city['image_url'],
        'slug' => '/municipalities'
    ]
];

View::component('search-hero', 'partials', [
    'country'         => $country,
    'backgroundImage' => $imageUrl,
    'title'           => $isShowMainCityItems ? HelperService::getTranslation($city, 'name', 'city') : $mainTitle,
    'formAction'      => '/' . $country['slug'] . '/cities/' . $city['slug'],
    'placeholderKey'  => HelperService::trans('search_in_the_city'),
    'searchValue'     => $_GET['search'] ?? '',
    'breadcrumbKey'   => !empty($category) ? 'category' : 'city',
    'breadcrumbs'     => $breadcrumbs,
    'showSearch'      => !$isShowMainCityItems,
]);

if ($category) {
    View::component('category-offers', 'categories/partials', [
        'offers'      => $offers,
        'category'    => $category,
        'activeScope' => $activeScope,
        'base_url'    => $base_url
    ]);

    View::component('category-filters', 'categories/partials', [
        'activeScope' => $activeScope,
        'cityName'    => $cityName,
        'countryName' => $countryName
    ]);
}
?>

<main>
    <?php if (!empty($items)): ?>
        <?php View::component('load-more-grid', 'partials', [
            'items'        => $isShowMainCityItems ? $mainCityItems : $items,
            'card_name'    => 'item-card',
            'base_url'     => $base_url,
            'limit'        => 8,
            'show_excerpt' => true,
            'show_search'  => false,
        ]); ?>
    <?php else: ?>
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="bg-gray-100 rounded-full p-6 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2"><?= HelperService::trans('no_content_yet') ?></h2>
            <p class="text-gray-500 max-w-sm mb-8">
                <?= HelperService::trans('category_empty_notice') ?> <?= htmlspecialchars($city['name']) ?>.
            </p>
            <a href="/<?= $country['slug'] ?>/cities/<?= $city['slug'] ?>" class="inline-flex items-center gap-2 bg-primary-dark text-white px-6 py-3 rounded-lg font-medium hover:bg-opacity-90 transition-all shadow-md">
                <?php HelperService::icon('left-arrow-icon'); ?>
                <?= HelperService::trans('back_to_city') ?>
            </a>
        </div>
    <?php endif; ?>
</main>
