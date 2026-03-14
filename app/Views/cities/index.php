<?php

use App\Core\View;
use App\Services\HelperService;

 ?>

<section>
    <div class="aspect-video max-h-100 w-full">
        <img src="<?= $cityElement['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($country['name']) ?>">
    </div>
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-semibold tracking-wide">
                <?= HelperService::trans('cities_in') ?> <?= HelperService::getTranslation($country, 'name') ?>
            </h1>
            <?php View::component('breadcrumbs', 'partials', [
                'items' => [
                    ['label' => $country['name'], 'href' => '/' . $country['slug']],
                    ['label' => HelperService::trans('cities'), 'href' => '']
                ]
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $cities,
        'style'     => 'list',
        'base_url'  => $country['slug'] . '/cities/',
        'limit'     => 9
    ]); ?>
</main>