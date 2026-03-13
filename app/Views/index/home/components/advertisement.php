<?php
$title = $title ?? 'Рекламирайте своя бизнес!';
$description = $description ?? 'Искате повече видимост за своя продукт или услуга? Нашият сайт ви позволява лесно да създавате и управлявате рекламни кампании, които достигат точно до вашата целева аудитория. Независимо дали сте малък локален бизнес или развивате онлайн търговия – тук можете да представите своята марка пред реални хора, които търсят това, което предлагате.';
$image = $image ?? '/assets/images/advertisement.webp';
$buttons = $buttons ?? [
    ['text' => 'Показване на рекламите', 'href' => '/ads'],
    ['text' => 'Рекламиране', 'href' => '/ads']
];
?>

<div class="relative w-full overflow-hidden bg-primary-dark">
    <div class="absolute inset-0 z-0">
        <img src="<?= $image ?>"
            class="w-full h-full object-cover opacity-30 grayscale-20"
            alt="Background">
        <div class="absolute inset-0 bg-linear-to-r from-primary-dark via-primary-dark/80 to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto grid lg:grid-cols-2 gap-10 items-center px-5 py-16 lg:py-24">

        <div class="p-8 lg:p-12 rounded-2xl shadow-2xl text-white space-y-6 backdrop-blur-md bg-primary-dark/60 border border-white/10"
            data-aos="fade-right"
            data-aos-delay="200">

            <h2 class="text-3xl lg:text-5xl font-bold tracking-tight leading-tight">
                <?= htmlspecialchars($title) ?>
            </h2>

            <p class="text-lg lg:text-xl leading-relaxed text-gray-200 font-light">
                <?= htmlspecialchars($description) ?>
            </p>

            <div class="flex flex-wrap gap-4 pt-6">
                <?php foreach ($buttons as $btn): ?>
                    <a href="<?= htmlspecialchars($btn['href']) ?>"
                        class="btn-primary shadow-lg hover:scale-105 active:scale-95 transition-transform">
                        <?= htmlspecialchars($btn['text']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="hidden lg:flex justify-end" data-aos="fade-left" data-aos-delay="400">
            <div class="relative">
                <div class="absolute -inset-4 bg-primary-blue/20 blur-3xl rounded-full"></div>

                <img src="<?= $image ?>"
                    alt="<?= htmlspecialchars($title) ?>"
                    loading="lazy"
                    class="relative rounded-2xl shadow-2xl transform transition duration-500 hover:rotate-2 hover:scale-105 object-cover w-110 h-auto border border-white/10">
            </div>
        </div>

    </div>
</div>
