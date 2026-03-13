<?php

use App\Core\View;
?>

<?php View::component('hero', "index/home/components", ['heroImage' => '/assets/images/azmigrantat-hero-background.webp']); ?>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'         => $countries,
        'card_path'     => 'partials',
        'card_name'     => 'item-card',
        'limit'         => 8
    ]); ?>

    <?php View::component('typewriter-items', 'index/home/components', [
        'items' => TYPEWRITER_ITEMS,
        'speed' => 80
    ]); ?>

    <?php View::component('show-banners', 'index/home/components', [
        'banners' => $banners,
    ]); ?>

    <?php View::component('advertisement', 'index/home/components'); ?>
    <?php View::component('contacts', 'index/home/components'); ?>
    <?php View::component('contact-grid', 'index/home/components'); ?>
    <?php View::component('facebook-page', 'index/home/components'); ?>
</main>