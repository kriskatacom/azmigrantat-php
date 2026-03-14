<?php

use App\Services\HelperService;

// Дефинираме контактите с преводи
$contacts = [
    [
        'icon' => 'fa-building',
        'label' => HelperService::trans('contact_office_label'),
        'value' => '+359 96593 333',
        'href' => 'tel:+35996593333'
    ],
    [
        'icon' => 'fa-headset',
        'label' => HelperService::trans('contact_services_label'),
        'value' => '+359 884 833 352',
        'href' => 'tel:+359884833352'
    ],
    [
        'icon' => 'fa-plane',
        'label' => HelperService::trans('contact_flights_label'),
        'value' => '+359 88 840 3353',
        'href' => 'tel:+359888403353'
    ],
    [
        'icon' => 'fa-shield-halved',
        'label' => HelperService::trans('contact_insurance_label'),
        'value' => '+359 884 833 351',
        'href' => 'tel:+359884833351'
    ],
    [
        'icon' => 'fa-briefcase',
        'label' => HelperService::trans('contact_jobs_nl_label'),
        'value' => '+31 6 87 333 432',
        'href' => 'tel:+31687333432'
    ],
    [
        'icon' => 'fa-envelope',
        'label' => '',
        'value' => 'i.the.migrant@gmail.com',
        'href' => 'mailto:i.the.migrant@gmail.com'
    ],
    [
        'icon' => 'fa-location-dot',
        'label' => '',
        'value' => HelperService::trans('contact_address_value'),
        'href' => 'https://www.google.com/maps/search/?api=1&query=бул.+Христо+Ботев+69,+Монтана',
        'target' => '_blank'
    ],
];

$socials = [
    [
        'name' => 'Facebook',
        'url' => 'https://www.facebook.com/Ithemigrant',
        'bg' => 'bg-blue-600',
        'hover' => 'hover:bg-blue-700',
        'icon_path' => 'M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z'
    ],
    [
        'name' => 'Tiktok',
        'url' => 'https://www.tiktok.com/@i.the.migrantbulg',
        'bg' => 'bg-black',
        'hover' => 'hover:bg-gray-900',
        'icon_path' => 'M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z'
    ]
];
?>

<section class="group/section relative overflow-hidden" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">
    <div class="bg-primary-dark">
        <div class="container mx-auto grid xl:grid-cols-2 lg:gap-10">

            <div class="grid gap-4 p-5 md:py-10 transition-all duration-1000 transform"
                :class="loaded ? 'translate-x-0 opacity-100' : '-translate-x-10 opacity-0'">

                <?php foreach ($contacts as $item): ?>
                    <a href="<?= $item['href'] ?>"
                        <?= isset($item['target']) ? 'target="' . $item['target'] . '"' : '' ?>
                        class="flex max-sm:flex-col items-center gap-4 bg-primary-darken text-white text-lg rounded-xl p-5 border border-white/5 transition-all duration-300 hover:bg-primary-darken/50 hover:border-primary-light/30 hover:-translate-y-1 shadow-md group">

                        <div class="w-12 h-12 flex items-center justify-center bg-white/10 rounded-full transition-colors group-hover:bg-primary-light/20">
                            <i class="fa-solid <?= $item['icon'] ?> text-2xl text-primary-light transition-transform group-hover:scale-110"></i>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:gap-2">
                            <?php if (!empty($item['label'])): ?>
                                <span class="font-semibold text-primary-blue opacity-90"><?= $item['label'] ?></span>
                            <?php endif; ?>
                            <span class="font-medium"><?= $item['value'] ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="flex flex-col justify-center items-center gap-6 p-5 transition-all duration-1000 transform"
                :class="loaded ? 'translate-x-0 opacity-100' : 'translate-x-10 opacity-0'">

                <h2 class="text-white text-3xl text-center font-black uppercase tracking-tight mb-4 transition-transform group-hover/section:-translate-y-1">
                    <?= HelperService::trans('social_media_title') ?>
                </h2>

                <?php foreach ($socials as $social): ?>
                    <a href="<?= $social['url'] ?>"
                        target="_blank"
                        class="flex items-center gap-4 w-full text-2xl text-white <?= $social['bg'] ?> <?= $social['hover'] ?> rounded-xl p-5 transition-all duration-300 transform hover:scale-[1.03] active:scale-95 shadow-xl hover:shadow-2xl">

                        <svg fill="currentColor" viewBox="0 0 512 512" class="w-10 h-10 shrink-0 transition-transform duration-500 hover:rotate-360">
                            <path d="<?= $social['icon_path'] ?>"></path>
                        </svg>

                        <span class="font-bold uppercase tracking-wide"><?= $social['name'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>
