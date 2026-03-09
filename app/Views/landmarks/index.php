<?php

use App\Core\View; ?>

<section>
    <div class="aspect-video max-h-100 w-full">
        <img src="<?= $landmarkElement['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($country['name']) ?>">
    </div>
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                Забележителности в <?= htmlspecialchars($country['name']) ?>
            </h1>
            <?php View::component('breadcrumbs', 'partials', [
                'items' => [
                    ['label' => $country['name'], 'href' => '/' . $country['slug']],
                    ['label' => 'Забележителности', 'href' => '/']
                ]
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $landmarks,
        'card_name' => 'country-card',
        'base_url'  => '/' . $country['slug'] . '/landmarks/',
        'limit'     => 8
    ]); ?>
</main>
