<?php

use App\Core\View;
use App\Services\HelperService;

$translatedCountryName = HelperService::getTranslation($country, 'name', 'country');
?>

<?php View::component('search-hero', 'partials', [
    'country'         => $country,
    'backgroundImage' => $embassyElement['image_url'] ?? '',
    'title'           => HelperService::trans('embassies_in') . ' ' . htmlspecialchars($translatedCountryName),
    'formAction'      => '/' . $country['slug'] . '/embassies',
    'placeholderKey'  => 'search_embassies',
    'breadcrumbs'   => $breadcrumbs,
    'searchValue'     => $searchTerm ?? ''
]); ?>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $embassies,
        'card_name' => 'item-card',
        'base_url'  => '/' . $country['slug'] . '/embassies/',
        'limit'     => 8,
        'show_search'     => false,
    ]); ?>
</main>