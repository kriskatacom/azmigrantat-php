<?php

use App\Core\View;
use App\Services\HelperService;

$translatedCountryName = HelperService::getTranslation($country, 'name', 'country');
$isBulgaria = $country['slug'] === 'bulgaria';
?>

<?php
$breadcrumbs = [
    [
        'label' => $translatedCountryName,
        'href' => '/' . $country['slug']
    ],
    [
        'label' => HelperService::trans('cities'),
        'href' => ''
    ]
];
?>

<?php View::component('search-hero', 'partials', [
    'country'           => $country,
    'backgroundImage'   => $cityElement['image_url'] ?? '',
    'title'             => $cityElement['name'],
    'formAction'        => '/' . $country['slug'] . '/cities',
    'placeholderKey'    => 'search_cities',
    'breadcrumbs'       => $breadcrumbs,
    'searchValue'       => $searchTerm ?? '',
    'showSearch'        => $country['slug'] === 'bulgaria' ? false : true,
]); ?>

<main>
    <div class="container mx-auto">
        <?php if ($isBulgaria): ?>
            <?php View::component('bulgaria-map', 'cities/components', [
                'cities' => $cities,
                'base_url' => $country['slug'] . '/cities/',
                'show_search' => false,
            ]); ?>
        <?php else: ?>
            <?php View::component('load-more-grid', 'partials', [
                'items'     => $cities,
                'style'     => 'list',
                'base_url'  => $country['slug'] . '/cities/',
                'limit'     => 9,
                'show_search' => false,
            ]); ?>
        <?php endif; ?>
    </div>
</main>