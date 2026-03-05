<?php

use App\Core\View;
?>

<main class="min-h-screen bg-gray-100">
    <?= View::component('show-banner', 'partials', ['banner' => $banner]) ?>
    <?= View::component('city-search', 'index/travel/shared-travel/components', ['citiesJson' => $citiesJson]) ?>

    <section class="max-w-5xl mx-auto grid md:grid-cols-2 gap-5 py-5 md:py-10">
        <div class="bg-white p-5 rounded-md shadow-md flex items-start gap-5">
            <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-shate shadow-md shrink-0"><img alt="Мария Иванова" loading="lazy" decoding="async" data-nimg="fill" class="transition-opacity duration-500 opacity-100 object-cover" style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent" src="http://localhost:3000/images/shared-travel/drivers/01.jpg"></div>
            <div class="flex-1 space-y-2">
                <div class="text-2xl font-semibold">Мария Иванова</div>
                <div class="text-gray-700 line-clamp-3">Пътуване от София до Пловдив на 28 март. Свободни 2 места. Тръгване в 17:30 от Централна гара.</div>
                <div class="pt-1">
                    <a href="/" class="btn-primary w-full text-center block">Преглед на обявата</a>
                </div>
            </div>
        </div>
    </section>
</main>