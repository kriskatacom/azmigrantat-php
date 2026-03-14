<?php

use App\Core\View;
use App\Services\HelperService;

// Подготвяме преведените данни с посочване на таблицата 'landmark'
$landmarkName = HelperService::getTranslation($landmark, 'name', 'landmark');
$landmarkHeading = HelperService::getTranslation($landmark, 'heading', 'landmark');
$landmarkContent = HelperService::getTranslation($landmark, 'content', 'landmark');
$landmarkWorkingTime = HelperService::getTranslation($landmark, 'working_time', 'landmark');
$landmarkAddress = HelperService::getTranslation($landmark, 'address', 'landmark');
$countryName = HelperService::getTranslation($country, 'name', 'country');

$breadcrumbs = [
    ['label' => $countryName, 'href' => '/' . $country['slug']],
    ['label' => HelperService::trans('landmarks'), 'href' => '/' . $country['slug'] . '/landmarks'],
    ['label' => $landmarkName, 'href' => '']
];
?>

<div class="aspect-video max-h-100 w-full">
    <img src="<?= HelperService::getImage($landmark['image_url']) ?>" 
         class="w-full h-full object-cover" 
         alt="<?= htmlspecialchars($landmarkName) ?>">
</div>

<div class="text-white bg-primary-dark py-3 md:py-5">
    <h1 class="text-xl md:text-2xl lg:text-3xl font-semibold text-center">
        <?= htmlspecialchars($landmarkName) ?>
    </h1>
    <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
</div>

<div class="container mx-auto px-2 md:px-5 mt-2 md:mt-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-5">

        <div x-data="{ isOpen: false }" x-cloak class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto flex flex-col">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl font-bold">
                <?php HelperService::icon('bank', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('about_landmark') ?>
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base flex-1 flex flex-col">
                <div class="text-gray-700 leading-relaxed line-clamp-6 md:line-clamp-12">
                    <?= $landmarkContent ?>
                </div>
                <button @click="$dispatch('open-modal-show-description')" class="flex items-center justify-center gap-2 text-xs md:text-sm bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-black transition-all w-full md:w-fit mt-4">
                    <?= HelperService::trans('more_info') ?>
                </button>
            </div>

            <?php View::component("modal", "partials", [
                'id' => 'show-description',
                'title' => $landmarkHeading ?: $landmarkName,
                'content' => $landmarkContent,
            ]); ?>
        </div>

        <?php View::component('gallery', 'partials', [
            'images' => $landmark['additional_images'],
            'title'  => HelperService::trans('gallery'),
            'icon'   => 'images'
        ]); ?>

        <div x-data="{ isOpen: false }" x-cloak class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto flex flex-col">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl font-bold">
                <?php HelperService::icon('clock', 'w-5 h-5 md:w-10 md:h-10'); ?>
                <?= HelperService::trans('work_time') ?>
            </div>

            <div class="p-2 md:p-5 space-y-3 md:space-y-5 flex-1 flex flex-col justify-between">
                <div class="text-gray-700 leading-relaxed text-xs md:text-base">
                    <?= $landmarkWorkingTime ?>
                </div>

                <?php if (!empty($landmarkWorkingTime)): ?>
                    <button @click="$dispatch('open-modal-show-working-time')" class="flex items-center justify-center gap-2 text-xs md:text-sm bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-black transition-all w-full md:w-fit mt-auto">
                        <?= HelperService::trans('show_more_info') ?>
                    </button>

                    <?php View::component("modal", "partials", [
                        'id' => 'show-working-time',
                        'title' => HelperService::trans('work_time'),
                        'content' => $landmarkWorkingTime,
                    ]); ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl font-bold">
                <?php HelperService::icon('location', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('contact_info') ?>
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                <?php if (!empty($landmarkAddress)): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('address', 'w-5 h-5 md:w-8 md:h-8'); ?>
                        <span class="break-all"><?= htmlspecialchars($landmarkAddress) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($landmark['phone'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('phone', 'w-5 h-5 md:w-8 md:h-8'); ?>
                        <a class="break-all hover:text-primary-dark transition-colors" href="tel:<?= $landmark['phone'] ?>"><?= $landmark['phone'] ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($landmark['email'])): ?>
                    <div class="flex items-center gap-3">
                        <?php HelperService::icon('mail', 'w-5 h-5 md:w-8 md:h-8'); ?>
                        <a class="break-all hover:text-primary-dark transition-colors" href="mailto:<?= $landmark['email'] ?>"><?= $landmark['email'] ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($landmark['website_link'])):
                    $rawUrl = $landmark['website_link'];
                    $cleanUrl = HelperService::formatUrl($rawUrl);
                    $isExternal = HelperService::isExternalLink($rawUrl);
                ?>
                    <div class="flex items-center gap-3 border-t border-gray-50 pt-2">
                        <?php HelperService::icon('globe-icon', 'w-5 h-5 md:w-8 md:h-8'); ?>
                        <a class="break-all hover:text-primary-dark font-semibold transition-colors"
                            href="<?= $cleanUrl ?>"
                            <?= $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                            <?= htmlspecialchars($rawUrl) ?>
                            <?php if ($isExternal): ?>
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-1"></i>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="w-fit my-4 md:my-8">
        <?php View::component("directions-button", "partials", [
            'mapsLink' => $landmark['your_location'],
            'label' => HelperService::trans('how_to_get_there'),
            'variant' => 'primary'
        ]); ?>
    </div>

    <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm h-75 md:h-125 mb-10 overflow-hidden">
        <iframe
            src="<?= $landmark['google_map'] ?>"
            class="w-full h-full"
            style="border:0;"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>
</div>