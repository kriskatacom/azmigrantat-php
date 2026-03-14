<?php

use App\Core\View;
use App\Services\HelperService;

// Взимаме преведеното име на държавата за заглавието и алт таговете
$translatedCountryName = HelperService::getTranslation($country, 'name', 'country');
?>

<section>
    <div class="aspect-video max-h-100 w-full">
        <img src="<?= $embassyElement['image_url'] ?>"
            class="w-full h-full object-cover"
            alt="<?= htmlspecialchars($translatedCountryName) ?>">
    </div>

    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                <?= HelperService::trans('embassies_in') ?> <?= htmlspecialchars($translatedCountryName) ?>
            </h1>

            <?php View::component('breadcrumbs', 'partials', [
                'items' => [
                    [
                        'label' => $translatedCountryName,
                        'href' => '/' . $country['slug']
                    ],
                    [
                        'label' => HelperService::trans('embassies'),
                        'href' => ''
                    ]
                ]
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $embassies,
        'card_name' => 'item-card',
        'base_url'  => '/' . $country['slug'] . '/embassies/',
        'limit'     => 8
    ]); ?>
</main>
