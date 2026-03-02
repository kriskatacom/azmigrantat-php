<?php

use App\Services\HelperService;

$imagesList = [];
if (!empty($images)) {
    $imagesList = is_string($images) ? json_decode($images, true) : (array)$images;
}

$jsonImages = htmlspecialchars(json_encode($imagesList), ENT_QUOTES, 'UTF-8');
$galleryId = 'gallery_' . uniqid();
?>

<div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden flex flex-col"
    x-data="galleryComponent(<?= $jsonImages ?>)"
    x-init="init()">

    <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
        <?php HelperService::icon($icon ?? 'image', 'w-5 h-5 md:w-10 md:h-10'); ?>
        <?= htmlspecialchars($title ?? 'Галерия') ?>
    </div>

    <div class="p-2 md:p-5 flex-1">

        <template x-if="images.length === 0">
            <div class="text-gray-500 text-center py-10">
                Няма налични снимки.
            </div>
        </template>

        <template x-if="images.length > 0">
            <div class="swiper rounded-lg relative w-full h-full" x-ref="previewSwiper">
                <div class="swiper-wrapper">

                    <template x-for="(img, index) in images" :key="index">
                        <div class="swiper-slide">

                            <a :href="img"
                                data-fancybox="<?= $galleryId ?>"
                                class="block cursor-zoom-in h-full">

                                <img :src="img"
                                    class="w-full h-full object-cover rounded-lg">
                            </a>

                        </div>
                    </template>

                </div>
            </div>
        </template>

    </div>
</div>

<script>
    function galleryComponent(images) {
        return {
            images: images,
            previewSwiper: null,

            init() {
                if (this.images.length > 0) {
                    this.$nextTick(() => {
                        this.previewSwiper = new Swiper(this.$refs.previewSwiper, {
                            loop: false,
                            slidesPerView: 1,
                            spaceBetween: 0,
                            pagination: {
                                clickable: true
                            },
                            observer: true,
                            observeParents: true
                        });
                    });
                }
            }
        }
    }
</script>