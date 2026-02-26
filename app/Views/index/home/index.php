<?php

use App\Core\View;
?>

<?php View::component('hero', "index/home/components", ['heroImage' => '/assets/images/azmigrantat-hero-background.webp']); ?>

<main class="mx-auto py-5 md:py-10">
    <h2 class="text-3xl font-bold text-center mb-10">Държави</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-5 px-5">
        <?php foreach ($countries as $country): ?>
            <?php View::component('country-card', "index/home/components", ['country' => $country]); ?>
        <?php endforeach; ?>
    </div>
</main>
