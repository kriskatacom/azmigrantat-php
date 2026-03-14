<?php
use App\Services\HelperService;

// Локализация на текстовете
$name        = HelperService::getTranslation($banner, 'name');
$description = HelperService::getTranslation($banner, 'description');
$buttonText  = HelperService::getTranslation($banner, 'button_text') ?: HelperService::trans('more_info');

// Обработка на линка
$rawHref    = $banner['href'] ?? '';
$cleanHref  = HelperService::formatUrl($rawHref);
$isExternal = HelperService::isExternalLink($rawHref);

$paddingClass = $padding ?? ($banner['padding'] ?? 'p-6 md:p-16');
$titleSize    = $title_size ?? ($banner['title_size'] ?? 'text-4xl md:text-6xl xl:text-7xl');
$titleWeight  = $title_weight ?? ($banner['title_weight'] ?? 'font-black');

$defaultBtnClass = 'bg-white text-gray-900 px-10 py-4 rounded-xl shadow-2xl hover:bg-primary-dark hover:text-white';
$btnClass = $button_class ?? ($banner['button_class'] ?? $defaultBtnClass);

$places = [
    'top_left'      => 'justify-start items-start text-left',
    'top_center'    => 'justify-start items-center text-center',
    'top_right'     => 'justify-start items-end text-right',
    'center_left'   => 'justify-center items-start text-left',
    'center_center' => 'justify-center items-center text-center',
    'center_right'  => 'justify-center items-end text-right',
    'bottom_left'   => 'justify-end items-start text-left',
    'bottom_center' => 'justify-end items-center text-center',
    'bottom_right'  => 'justify-end items-end text-right',
];

$alignmentClass = $places[$banner['content_place'] ?? 'center_center'] ?? $places['center_center'];
$height = !empty($banner['height']) ? (is_numeric($banner['height']) ? $banner['height'] . 'px' : $banner['height']) : '520px';
$image  = !empty($banner['image_url']) ? HelperService::getImage($banner['image_url']) : '/assets/img/default-banner.jpg';
?>

<section class="relative w-full overflow-hidden" style="height: <?= $height ?>; max-height: 85vh;">
    <img src="<?= $image ?>"
         class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 hover:scale-105"
         alt="<?= htmlspecialchars($name ?: 'Banner') ?>">

    <?php if (($banner['show_overlay'] ?? 1) == 1): ?>
        <div class="absolute inset-0 bg-black/40 backdrop-brightness-90"></div>
    <?php endif; ?>

    <div class="absolute inset-0 flex flex-col z-10 <?= $paddingClass ?> <?= $alignmentClass ?>">
        <div class="max-w-4xl w-full text-white drop-shadow-2xl">
            
            <?php if (($banner['show_name'] ?? 1) == 1 && !empty($name)): ?>
                <h1 class="<?= $titleSize ?> <?= $titleWeight ?> uppercase tracking-tighter mb-4 animate-fade-in-up">
                    <?= htmlspecialchars($name) ?>
                </h1>
            <?php endif; ?>

            <?php if (($banner['show_description'] ?? 1) == 1 && !empty($description)): ?>
                <p class="text-lg md:text-2xl font-light mb-8 leading-relaxed max-w-2xl <?= strpos($alignmentClass, 'center') !== false ? 'mx-auto' : '' ?>">
                    <?= htmlspecialchars($description) ?>
                </p>
            <?php endif; ?>

            <?php if (($banner['show_button'] ?? 0) == 1 && !empty($cleanHref)): ?>
                <div class="mt-2">
                    <a href="<?= $cleanHref ?>"
                       <?= $isExternal ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
                       class="inline-flex items-center gap-2 <?= $btnClass ?> font-bold transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest text-sm">
                        <?= htmlspecialchars($buttonText) ?>
                        <?php if ($isExternal): ?>
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</section>