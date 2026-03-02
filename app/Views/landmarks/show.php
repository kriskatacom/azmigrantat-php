<?php

use App\Core\View;
use App\Services\HelperService;

$breadcrumbs = [
    ['label' => $country['name'], 'url' => '/' . $country['slug']],
    ['label' => 'Забележителности', 'url' => '/' . $country['slug'] . '/landmarks'],
    ['label' => $landmark['name'], 'url' => '']
];
?>
<div class="aspect-video max-h-100 w-full">
    <img src="<?= HelperService::getImage($landmark['image_url']) ?>" class="w-full h-full object-cover">
</div>

<div class="flex items-center justify-center gap-5 p-5">
    <h1 class="text-xl md:text-2xl lg:text-3xl font-semibold text-primary-dark text-center">
        <?= $landmark['name'] ?>
    </h1>
</div>

<div class="text-white bg-primary-dark py-3 md:py-5">
    <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
</div>

<div class="container mx-auto px-2 md:px-5 mt-2 md:mt-5">
    <div class="grid grid-cols-2 gap-2 md:gap-5">

        <div x-data="{ isOpen: false }" x-cloak class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('bank', 'w-5 h-5 md:w-10 md:h-10'); ?> За забележителността
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                <button @click="$dispatch('open-modal-show-description')" class="flex items-center justify-center gap-2 text-xs md:text-sm bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-black transition-all w-full md:w-fit mt-auto">
                    Повече информация
                </button>
                <div class="text-gray-700 leading-relaxed text-xs md:text-base line-clamp-3 md:line-clamp-5 xl:line-clamp-none">
                    <?= $landmark['content'] ?>
                </div>
            </div>

            <?php View::component("modal", "partials", [
                'id' => 'show-description',
                'title' => $landmark['heading'],
                'content' => $landmark['content'],
            ]); ?>
        </div>

        <?php View::component('gallery', 'partials', [
            'images' => $landmark['additional_images'],
            'title'  => 'Галерия',
            'icon'   => 'images'
        ]); ?>

        <div x-data="{ isOpen: false }" x-cloak class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('clock', 'w-5 h-5 md:w-10 md:h-10'); ?>
                Работно време
            </div>

            <div class="p-2 md:p-5 space-y-3 md:space-y-5 flex-1 flex flex-col justify-between">
                <div class="text-gray-700 leading-relaxed text-xs md:text-base line-clamp-5">
                    <?= $landmark['working_time'] ?>
                </div>

                <button @click="$dispatch('open-modal-show-working-time')" class="flex items-center justify-center gap-2 text-xs md:text-sm bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-black transition-all w-full md:w-fit mt-auto">
                    Показване
                </button>

                <?php View::component("modal", "partials", [
                    'id' => 'show-working-time',
                    'title' => 'Работно време',
                    'content' => $landmark['working_time'],
                ]); ?>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('location', 'w-5 h-5 md:w-10 md:h-10'); ?> Информация за контакти
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                <?php if (!empty($landmark['address'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('address', 'w-5 h-5 md:w-10 md:h-10'); ?>
                        <span class="break-all"><?= $landmark['address'] ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($landmark['phone'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('phone', 'w-5 h-5 md:w-10 md:h-10'); ?>
                        <a class="break-all" href="tel:<?= $landmark['phone'] ?>"><?= $landmark['phone'] ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($landmark['email'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('mail', 'w-5 h-5 md:w-10 md:h-10'); ?>
                        <a class="break-all" href="mailto:<?= $landmark['email'] ?>"><?= $landmark['email'] ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($landmark['website_link'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('globe-icon', 'w-5 h-5 md:w-10 md:h-10'); ?>
                        <a class="break-all" href="<?= $landmark['website_link'] ?>" target="_blank" title="Уебсайт на <?= $landmark['heading'] ?>"><?= $landmark['website_link'] ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="w-fit my-2 md:my-5">
        <?php View::component("directions-button", "partials", [
            'mapsLink' => $landmark['your_location'],
            'label' => 'Как да стигнете ?',
            'variant' => 'primary'
        ]); ?>
    </div>

    <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm h-100">
        <iframe
            src="<?= $landmark['google_map'] ?>"
            class="w-full h-full cursor-pointer"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>
</div>
