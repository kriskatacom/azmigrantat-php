<?php

use App\Core\View;
use App\Services\HelperService;

$embassyName    = HelperService::getTranslation($embassy, 'name', 'embassy');
$embassyContent = HelperService::getTranslation($embassy, 'content', 'embassy');
$embassyAddress = HelperService::getTranslation($embassy, 'address', 'embassy');
$embassyWorking = HelperService::getTranslation($embassy, 'working_time', 'embassy');

$breadcrumbs = [
    ['label' => HelperService::getTranslation($country, 'name', 'country'), 'href' => '/' . $country['slug']],
    ['label' => HelperService::trans('embassies'), 'href' => '/' . $country['slug'] . '/embassies'],
    ['label' => $embassyName, 'href' => '']
];
?>
<div class="aspect-video max-h-100 w-full">
    <img src="<?= HelperService::getImage($embassy['image_url']) ?>" class="w-full h-full object-cover">
</div>

<div class="flex items-center justify-center gap-5 p-5">
    <img src="<?= HelperService::getImage($embassy['logo']) ?>" class="h-20">
    <h1 class="text-xl md:text-2xl lg:text-3xl font-semibold text-primary-dark text-center">
        <?= HelperService::trans('embassy_of') ?> <?= $embassyName ?>
    </h1>
    <img src="<?= HelperService::getImage($embassy['right_heading_image']) ?>" class="h-20">
</div>

<div class="text-white bg-primary-dark py-3 md:py-5">
    <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
</div>

<div x-data="{ isOpen: false }" class="container mx-auto px-2 md:px-5 mt-2 md:mt-5">
    <div class="grid grid-cols-2 gap-2 md:gap-5">

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('bank', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('about_embassy') ?>
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                <a href="<?= $embassy['website_link'] ?>" target="__blank" title="<?= HelperService::trans('more_info') ?> - <?= $embassyName ?>" class="block text-xs md:text-base w-full md:w-fit px-2 md:px-5 bg-primary-darken hover:bg-primary-dark text-white py-2 rounded md:rounded-lg mb-2 md:mb-5 text-center">
                    <?= HelperService::trans('more_info') ?>
                </a>
                <div class="text-gray-700 leading-relaxed text-xs md:text-base line-clamp-5">
                    <?= $embassyContent ?>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('location', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('contact_info') ?>
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                <?php if (!empty($embassyAddress)): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('address', 'w-5 h-5 md:w-10 md:h-10 text-gray-500'); ?>
                        <span class="break-all"><?= $embassyAddress ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($embassy['phone'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('phone', 'w-5 h-5 md:w-10 md:h-10 text-gray-500'); ?>
                        <a class="break-all" href="tel:<?= $embassy['phone'] ?>"><?= $embassy['phone'] ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($embassy['email'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('mail', 'w-5 h-5 md:w-10 md:h-10 text-gray-500'); ?>
                        <a class="break-all" href="mailto:<?= $embassy['email'] ?>"><?= $embassy['email'] ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($embassy['website_link'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('globe-icon', 'w-5 h-5 md:w-10 md:h-10'); ?>
                        <a class="break-all" href="<?= $embassy['website_link'] ?>" target="_blank"><?= $embassy['website_link'] ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div x-data="{ open: false }">
            <div class="border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-50 md:h-80 xl:h-auto">
                <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl font-bold bg-gray-50">
                    <?php HelperService::icon('clock', 'w-5 h-5 md:w-6 md:h-6'); ?>
                    <?= HelperService::trans('work_time') ?>
                </div>

                <div class="p-2 md:p-5 space-y-3 md:space-y-5 flex-1 flex flex-col justify-between">
                    <div class="text-gray-700 leading-relaxed text-xs md:text-base line-clamp-5">
                        <?= $embassyWorking ?>
                    </div>

                    <button @click="$dispatch('open-modal-show-working-time')" class="flex items-center justify-center gap-2 text-xs md:text-sm bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-black transition-all w-full md:w-fit mt-auto">
                        <?= HelperService::trans('show_info') ?>
                    </button>

                    <?php View::component("modal", "partials", [
                        'id' => 'show-working-time',
                        'title' => HelperService::trans('embassy_work_time'),
                        'content' => $embassyWorking,
                    ]); ?>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm h-50 md:h-80 xl:h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('telephone', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('emergency_cases') ?>
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                <div class="flex items-center gap-3">
                    <?php HelperService::icon('phone', 'w-5 h-5 md:w-10 md:h-10'); ?>
                    <a class="break-all" href="tel:<?= $embassy['phone'] ?>"><?= $embassy['phone'] ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="w-fit my-2 md:my-5">
        <?php View::component("directions-button", "partials", [
            'mapsLink' => $embassy['your_location'],
            'label' => HelperService::trans('how_to_get_there') . ' ?',
            'variant' => 'primary'
        ]); ?>
    </div>

    <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm h-100">
        <iframe
            src="<?= $embassy['google_map'] ?>"
            class="w-full h-full cursor-pointer"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>
</div>