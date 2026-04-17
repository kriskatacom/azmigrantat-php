<?php

use App\Services\HelperService;

// 1. Подготовка на данните - гарантираме чист масив
$imagesList = [];
if (!empty($images)) {
    $decoded = is_string($images) ? json_decode($images, true) : (array)$images;
    $imagesList = array_values(array_filter($decoded ?: []));
}

$jsonImages = htmlspecialchars(json_encode($imagesList), ENT_QUOTES, 'UTF-8');
$galleryId = 'gal_' . bin2hex(random_bytes(4)); // По-сигурен уникален ID
$variant = $variant ?? 'card';

// 2. Класове за бутона
$baseBtnClasses = "btn-primary w-full sm:w-auto flex items-center justify-center gap-2 transition-all active:scale-95";
$buttonClasses = $baseBtnClasses . (!empty($buttonClassesParam) ? " " . $buttonClassesParam : "");
?>

<div class="w-full" x-data="galleryComponent(<?= $jsonImages ?>, '<?= $galleryId ?>')" x-init="init()">

    <?php if ($variant === 'card'): ?>
        <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-white/10 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
            <div class="p-4 border-b border-gray-100 dark:border-white/5 flex items-center gap-3 font-bold text-slate-800 dark:text-white">
                <?php HelperService::icon($icon ?? 'image', 'w-6 h-6 text-indigo-500'); ?>
                <span class="truncate"><?= htmlspecialchars($title ?? HelperService::trans('gallery')) ?></span>
            </div>

            <div class="p-3 flex-1 min-h-[320px] bg-slate-50/50 dark:bg-transparent">
                <template x-if="images.length === 0">
                    <div class="flex flex-col items-center justify-center h-64 text-slate-400 italic">
                        <i class="fa-regular fa-image text-4xl mb-2 opacity-20"></i>
                        <?= HelperService::trans('no_photos_available') ?>
                    </div>
                </template>

                <template x-if="images.length > 0">
                    <div class="swiper rounded-lg overflow-hidden w-full h-full group" x-ref="previewSwiper">
                        <div class="swiper-wrapper">
                            <template x-for="(img, index) in images" :key="index">
                                <div class="swiper-slide">
                                    <a :href="img"
                                        :data-fancybox="galleryId"
                                        @click.prevent
                                        class="relative block cursor-zoom-in w-full h-full group/item">
                                        <img :src="img"
                                            class="w-full h-full object-cover transition duration-700 group-hover/item:scale-105"
                                            loading="lazy">
                                        <div class="absolute inset-0 bg-black/5 group-hover/item:bg-transparent transition-colors"></div>
                                    </a>
                                </div>
                            </template>
                        </div>

                        <div class="swiper-button-next !w-10 !h-10 !bg-white/90 shadow-lg !text-indigo-600 rounded-full after:!text-sm opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="swiper-button-prev !w-10 !h-10 !bg-white/90 shadow-lg !text-indigo-600 rounded-full after:!text-sm opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="swiper-pagination !-bottom-1"></div>
                    </div>
                </template>
            </div>
        </div>

    <?php elseif ($variant === 'button'): ?>
        <template x-if="images.length > 0">
            <button @click="openGallery()" class="<?= $buttonClasses ?>">
                <?php HelperService::icon('image', 'w-5 h-5'); ?>
                <span><?= $title ?? HelperService::trans('view_gallery') ?></span>
                <span class="bg-white/20 px-2 py-0.5 rounded text-xs" x-text="images.length"></span>
            </button>
        </template>

    <?php elseif ($variant === 'thumbnail'): ?>
        <template x-if="images.length > 0">
            <div class="relative group cursor-pointer overflow-hidden rounded-2xl shadow-lg aspect-video w-full" @click="openGallery()">
                <img :src="images[0]" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                <div class="absolute inset-0 bg-slate-900/40 group-hover:bg-slate-900/20 transition-colors flex items-center justify-center">
                    <div class="text-white text-center transform translate-y-4 group-hover:translate-y-0 transition-transform">
                        <?php HelperService::icon('image', 'w-10 h-10 mx-auto mb-3 opacity-80'); ?>
                        <span class="font-bold tracking-widest text-xs uppercase bg-white/10 backdrop-blur-md px-4 py-2 rounded-full">
                            <?= HelperService::trans('view_all_photos') ?>
                        </span>
                    </div>
                </div>
            </div>
        </template>
    <?php endif; ?>

</div>

<script>
    if (typeof galleryComponent !== 'function') {
        function galleryComponent(images, gId) {
            return {
                images: images,
                swiperInstance: null,
                galleryId: gId,

                init() {
                    if (this.images.length === 0) return;

                    this.$nextTick(() => {
                        this.initSwiper();
                        this.initFancybox();
                    });
                },

                initSwiper() {
                    if (!this.$refs.previewSwiper) return;

                    this.swiperInstance = new Swiper(this.$refs.previewSwiper, {
                        loop: this.images.length > 1,
                        slidesPerView: 1,
                        spaceBetween: 12,
                        grabCursor: true,
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        observer: true,
                        observeParents: true
                    });
                },

                initFancybox() {
                    if (typeof Fancybox === 'undefined') return;

                    Fancybox.bind(`[data-fancybox="${this.galleryId}"]`, {
                        dragToClose: false,
                        Toolbar: {
                            display: ["thumbs", "close"]
                        },
                        Image: {
                            zoom: true
                        },
                    });
                },

                openGallery() {
                    if (typeof Fancybox === 'undefined') return;
                    Fancybox.show(this.images.map(img => ({
                        src: img,
                        type: "image"
                    })));
                }
            }
        }
    }
</script>

<style>
    .swiper {
        width: 100%;
        aspect-ratio: 4 / 3;
        background: #f1f5f9;
    }

    @media (min-width: 768px) {
        .swiper {
            aspect-ratio: 16 / 10;
        }
    }

    .dark .swiper {
        background: rgba(255, 255, 255, 0.02);
    }

    .swiper-pagination-bullet-active {
        background: #4f46e5 !important;
        width: 12px;
        border-radius: 4px;
    }
</style>