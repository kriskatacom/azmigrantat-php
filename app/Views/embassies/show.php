<?php

use App\Core\View;
use App\Services\HelperService;

// Локализация на данните
$embassyName = HelperService::getTranslation($embassy, 'name');
$embassyContent = HelperService::getTranslation($embassy, 'content');
$embassyAddress = HelperService::getTranslation($embassy, 'address');
$embassyWorkingTime = HelperService::getTranslation($embassy, 'working_time');
$countryName = HelperService::getTranslation($country, 'name');

$breadcrumbs = [
    ['label' => $countryName, 'href' => '/' . $country['slug']],
    ['label' => HelperService::trans('embassies'), 'href' => '/' . $country['slug'] . '/embassies'],
    ['label' => $embassyName, 'href' => '']
];

// Обработка на линка
$rawUrl = $embassy['website_link'] ?? '';
$cleanUrl = HelperService::formatUrl($rawUrl);
$isExternal = HelperService::isExternalLink($rawUrl);
?>

<div class="aspect-video max-h-100 w-full">
    <img src="<?= HelperService::getImage($embassy['image_url']) ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($embassyName) ?>">
</div>

<div class="flex items-center justify-center gap-5 p-5">
    <?php if (!empty($embassy['logo'])): ?>
        <img src="<?= HelperService::getImage($embassy['logo']) ?>" class="h-12 md:h-20">
    <?php endif; ?>

    <h1 class="text-xl md:text-2xl lg:text-3xl font-semibold text-primary-dark text-center">
        <?= HelperService::trans('embassy_of') ?> <?= htmlspecialchars($embassyName) ?>
    </h1>

    <?php if (!empty($embassy['right_heading_image'])): ?>
        <img src="<?= HelperService::getImage($embassy['right_heading_image']) ?>" class="h-12 md:h-20">
    <?php endif; ?>
</div>

<div class="text-white bg-primary-dark py-3 md:py-5">
    <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
</div>

<div x-data="{ isOpen: false }" class="container mx-auto px-2 md:px-5 mt-2 md:mt-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-5">

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto flex flex-col">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl font-bold">
                <?php HelperService::icon('bank', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('about_embassy') ?>
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 flex-1 flex flex-col">
                <?php if (!empty($cleanUrl)): ?>
                    <a href="<?= $cleanUrl ?>"
                        <?= $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
                        class="block text-xs md:text-base w-full md:w-fit px-5 bg-primary-darken hover:bg-primary-dark text-white py-2.5 rounded md:rounded-lg mb-2 text-center transition-all font-semibold">
                        <?= HelperService::trans('more_info') ?>
                        <?php if ($isExternal): ?>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <div class="text-gray-700 leading-relaxed text-xs md:text-base line-clamp-10">
                    <?= $embassyContent ?>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl font-bold">
                <?php HelperService::icon('location', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('contact_info') ?>
            </div>
            <div class="p-2 md:p-5 space-y-4 text-xs md:text-base">
                <?php if (!empty($embassyAddress)): ?>
                    <div class="flex items-start gap-3">
                        <?php HelperService::icon('address', 'w-5 h-5 md:w-8 md:h-8 text-gray-400'); ?>
                        <span class="break-all"><?= htmlspecialchars($embassyAddress) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($embassy['phone'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('phone', 'w-5 h-5 md:w-8 md:h-8 text-gray-400'); ?>
                        <a class="hover:text-primary-dark transition-colors" href="tel:<?= $embassy['phone'] ?>"><?= $embassy['phone'] ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($embassy['email'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('mail', 'w-5 h-5 md:w-8 md:h-8 text-gray-400'); ?>
                        <a class="break-all hover:text-primary-dark transition-colors" href="mailto:<?= $embassy['email'] ?>"><?= $embassy['email'] ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($cleanUrl)): ?>
                    <div class="flex items-center gap-3 pt-2 border-t border-gray-50">
                        <?php HelperService::icon('globe-icon', 'w-5 h-5 md:w-8 md:h-8'); ?>
                        <a class="break-all font-bold text-primary-dark hover:underline" href="<?= $cleanUrl ?>" <?= $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                            <?= $rawUrl ?>
                            <?php if ($isExternal): ?>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div x-data="{ open: false }" class="flex flex-col">
            <div class="border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden flex-1 flex flex-col">
                <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl font-bold bg-gray-50">
                    <?php HelperService::icon('clock', 'w-5 h-5 md:w-6 md:h-6'); ?>
                    <?= HelperService::trans('work_time') ?>
                </div>

                <div class="p-2 md:p-5 space-y-3 flex-1 flex flex-col justify-between">
                    <div class="text-gray-700 leading-relaxed text-xs md:text-base">
                        <?= $embassyWorkingTime ?>
                    </div>

                    <?php if (!empty($embassyWorkingTime)): ?>
                        <button @click="$dispatch('open-modal-show-working-time')" class="flex items-center justify-center gap-2 text-xs md:text-sm bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-black transition-all w-full md:w-fit mt-4">
                            <?= HelperService::trans('show_info') ?>
                        </button>

                        <?php View::component("modal", "partials", [
                            'id' => 'show-working-time',
                            'title' => HelperService::trans('embassy_work_time'),
                            'content' => $embassyWorkingTime,
                        ]); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl font-bold text-red-600">
                <?php HelperService::icon('telephone', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('emergency_cases') ?>
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base flex-1">
                <?php if (!empty($embassy['phone'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('phone', 'w-5 h-5 md:w-10 md:h-10 text-red-500'); ?>
                        <a class="text-lg md:text-xl font-black text-red-600 hover:underline" href="tel:<?= $embassy['phone'] ?>"><?= $embassy['phone'] ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="w-fit my-4 md:my-8">
        <?php View::component("directions-button", "partials", [
            'mapsLink' => $embassy['your_location'],
            'label' => HelperService::trans('how_to_get_there') . ' ?',
            'variant' => 'primary'
        ]); ?>
    </div>

    <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm h-75 md:h-125 mb-10 overflow-hidden">
        <iframe
            src="<?= $embassy['google_map'] ?>"
            class="w-full h-full"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>
</div>
