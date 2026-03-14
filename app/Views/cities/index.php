<?php

use App\Core\View;
use App\Services\HelperService;

$translatedCountryName = HelperService::getTranslation($country, 'name', 'country');
$isBulgaria = $country['slug'] === 'bulgaria';
?>

<section>
    <div class="aspect-video max-h-100 w-full">
        <img src="<?= $cityElement['image_url'] ?>"
            class="w-full h-full object-cover"
            alt="<?= htmlspecialchars($translatedCountryName) ?>">
    </div>

    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-semibold tracking-wide mb-4">
                <?= HelperService::trans('cities_in') ?> <?= $translatedCountryName ?>
            </h1>

            <?php View::component('breadcrumbs', 'partials', [
                'items' => [
                    [
                        'label' => $translatedCountryName,
                        'href' => '/' . $country['slug']
                    ],
                    [
                        'label' => HelperService::trans('cities'),
                        'href' => ''
                    ]
                ]
            ]); ?>
        </div>
    </div>
</section>

<main class="py-10">
    <div class="container mx-auto px-4">
        <?php if ($isBulgaria): ?>
            <?php View::component('bulgaria-map', 'cities/components', [
                'cities' => $cities,
                'base_url' => $country['slug'] . '/cities/'
            ]); ?>
        <?php else: ?>
            <?php View::component('load-more-grid', 'partials', [
                'items'     => $cities,
                'style'     => 'list',
                'base_url'  => $country['slug'] . '/cities/',
                'limit'     => 9
            ]); ?>
        <?php endif; ?>
    </div>
</main>
