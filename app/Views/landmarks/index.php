<?php

use App\Core\View;
use App\Services\HelperService;

$translatedCountryName = HelperService::getTranslation($country, 'name', 'country');
?>

<?php View::component('search-hero', 'partials', [
    'country'         => $country,
    'backgroundImage' => $landmarkElement['image_url'] ?? '',
    'title'           => HelperService::trans('landmarks_in') . ' ' . htmlspecialchars($translatedCountryName),
    'formAction'      => '/' . $country['slug'] . '/landmarks',
    'placeholderKey'  => 'search_landmarks',
    'breadcrumbs'   => $breadcrumbs,
    'searchValue'     => $searchTerm ?? ''
]); ?>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $landmarks,
        'card_name' => 'item-card',
        'base_url'  => '/' . $country['slug'] . '/landmarks/',
        'limit'     => 8,
        'show_search' => false,
    ]); ?>
</main>