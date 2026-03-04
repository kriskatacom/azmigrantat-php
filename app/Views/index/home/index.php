<?php

use App\Core\View;

?>

<?php View::component('hero', "index/home/components", ['heroImage' => '/assets/images/azmigrantat-hero-background.webp']); ?>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'         => $countries,
        'card_path'     => 'partials',
        'card_name'     => 'country-card',
        'limit'         => 8
    ]); ?>
</main>