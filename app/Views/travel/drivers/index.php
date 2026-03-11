<?php

use App\Core\View;
use App\Services\HelperService;

$breadcrumbs = [
    ['label' => HelperService::trans('home'), 'href' => '/travel'],
    ['label' => 'Споделено пътуване', 'href' => '/travel/shared-travel'],
    ['label' => 'Шофьори'],
];

$activeDrivers = array_filter($drivers, function($driver) {
    return !empty($driver['username']) || !empty($driver['name']);
});

$limit = 2;
?>

<main class="min-h-screen bg-gray-100">
    <?= View::component('show-banner', 'partials', ['banner' => $banner]) ?>

    <div class="bg-primary-dark py-5">
        <h2 class="text-white text-center text-2xl lg:text-3xl font-semibold">
            Нашите пътешественици
        </h2>
        <div class="text-white bg-primary-dark">
            <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
        </div>
    </div>

    <section class="py-5 md:py-10 max-w-5xl mx-auto px-5 md:px-0">
        <h2 class="text-center text-xl md:text-2xl lg:text-3xl font-semibold mb-5">Шофьори</h2>
        <div id="drivers-container" class="grid md:grid-cols-2 gap-5">
            <?php $j = 0; foreach ($activeDrivers as $driver): ?>
                <div class="grid-item-driver <?= $j >= $limit ? 'hidden' : '' ?>">
                    <?= View::component('driver-card', 'travel/drivers/components', ['driver' => $driver, 'type' => 'drivers']) ?>
                </div>
            <?php $j++; endforeach; ?>
        </div>
        <?php if (count($activeDrivers) > $limit): ?>
            <div class="flex justify-center mt-5">
                <button onclick="loadMore('driver')" id="btn-load-driver" class="bg-white border border-gray-200 text-gray-800 font-semibold px-10 py-3 rounded-lg shadow-sm hover:bg-gray-50 transition-all cursor-pointer">
                    <?= HelperService::trans('show_more') ?>
                </button>
            </div>
        <?php endif; ?>
    </section>
</main>

<script>
    function loadMore(type) {
        const containerId = type === 'post' ? 'posts-container' : 'drivers-container';
        const itemClass = type === 'post' ? '.grid-item-post.hidden' : '.grid-item-driver.hidden';
        const btnId = type === 'post' ? 'btn-load-post' : 'btn-load-driver';
        
        const container = document.getElementById(containerId);
        const hiddenItems = container.querySelectorAll(itemClass);
        const limit = <?= (int)$limit ?>;

        for (let i = 0; i < limit && i < hiddenItems.length; i++) {
            hiddenItems[i].classList.remove('hidden');
            hiddenItems[i].style.opacity = 0;
            hiddenItems[i].style.transition = 'opacity 0.5s ease-in-out';
            setTimeout(() => { hiddenItems[i].style.opacity = 1; }, 10);
        }

        if (container.querySelectorAll(itemClass).length === 0) {
            document.getElementById(btnId).parentElement.style.display = 'none';
        }
    }
</script>
