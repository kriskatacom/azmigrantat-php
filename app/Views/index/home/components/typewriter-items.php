<?php

use App\Core\View;
use App\Services\HelperService;

$translatedItems = array_map(function($key) {
    return HelperService::trans($key);
}, TYPEWRITER_ITEMS);
?>

<div class="text-white bg-primary-dark py-5 text-center text-xl md:text-2xl">
    <span class="text-primary-blue"><?= HelperService::trans('i_the_migrant') ?></span>

    <span>|</span>

    <?php View::component('typewriter-items', 'partials', [
        'items' => $translatedItems,
        'speed' => 80,
        'delay' => 2000
    ]); ?>
</div>
