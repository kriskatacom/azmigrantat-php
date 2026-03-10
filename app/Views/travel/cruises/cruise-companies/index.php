<?php

use App\Core\View;

$breadcrumbs = [
    ['label' => 'Пътуване', 'href' => '/travel'],
    ['label' => $cruisesBanner['name'], 'href' => '/travel/cruises'],
    ['label' => $banner['name']]
];
?>

<section>
    <?= View::component('show-banner', 'partials', ['banner' => $banner]) ?>

    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-bold uppercase tracking-wide">
                <?= $banner['name'] ?>
            </h1>
            <?php View::component('breadcrumbs', 'partials', [
                'items' => $breadcrumbs,
            ]); ?>
        </div>
    </div>
</section>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $cruises,
        'card_name' => 'item-card',
        'link_key' => 'website_url',
        'show_search' => false,
    ]); ?>
</main>