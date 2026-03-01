<?php

use App\Core\View; ?>

<section>
    <div class="aspect-video max-h-100 w-full">
        <img src="<?= $cityElement['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($country['name']) ?>">
    </div>
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-semibold tracking-wide">
                Градове в <?= htmlspecialchars($country['name']) ?>
            </h1>
            <?php View::component('breadcrumbs', 'partials', [
                'items' => [
                    ['label' => 'Начало', 'url' => '/'],
                    ['label' => $country['name'], 'url' => '/' . $country['slug']],
                    ['label' => 'Градове']
                ]
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $cities,
        'card_name' => 'country-card',
        'style'     => 'list',
        'base_url'  => 'cities/',
        'limit'     => 9
    ]); ?>
</main>
