<?php

use App\Core\View;
?>

<section>
    <div class="flex flex-col gap-5 p-5">
        <?php foreach ($banners as $banner): ?>
            <div class="rounded-2xl overflow-hidden">
                <?= View::component('show-banner', 'partials', [
                    'banner' => $banner,
                    'padding' => 'p-5',
                    'title_size' => 'text-2xl md:text-3xl',
                    'title_weight' => 'font-semibold md:font-bold',
                    'button_class' => 'text-white px-5 py-3 rounded-md border-2 border-white hover:text-primary-dark hover:bg-white hover:scale-110 shadow-lg'
                ]) ?>
            </div>
        <?php endforeach; ?>
    </div>
</section>