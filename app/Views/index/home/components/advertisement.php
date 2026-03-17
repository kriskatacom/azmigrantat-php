<?php
$title = $title ?? 'Рекламирайте своя бизнес!';
$description = $description ?? 'Искате повече видимост за своя продукт или услуга? Нашият сайт ви позволява лесно да създавате и управлявате рекламни кампании, които достигат точно до вашата целева аудитория. Независимо дали сте малък локален бизнес или развивате онлайн търговия – тук можете да представите своята марка пред реални хора, които търсят това, което предлагате.';
$image = $image ?? '/assets/images/advertisement.webp';
$buttons = $buttons ?? [
    ['text' => 'Показване на рекламите', 'href' => '/ads'],
    ['text' => 'Рекламиране', 'href' => '/ads']
];
?>

<style>
    @keyframes float {
        0% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(1deg);
        }

        100% {
            transform: translateY(0px) rotate(0deg);
        }
    }

    @keyframes pulse-glow {

        0%,
        100% {
            opacity: 0.4;
            transform: scale(1);
        }

        50% {
            opacity: 0.7;
            transform: scale(1.1);
        }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    .animate-pulse-glow {
        animation: pulse-glow 8s ease-in-out infinite;
    }

    /* Ефект при посочване на стъклената карта */
    .glass-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .glass-card:hover {
        border-color: rgba(255, 255, 255, 0.3);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
</style>

<div class="relative w-full overflow-hidden bg-primary-dark py-10">
    <div class="absolute inset-0 z-0">
        <img src="<?= $image ?>"
            class="w-full h-full object-cover opacity-20 grayscale-20 scale-105"
            alt="Background">
        <div class="absolute inset-0 bg-linear-to-r from-primary-dark via-primary-dark/90 to-transparent"></div>

        <div class="absolute top-1/4 -right-20 w-96 h-96 bg-primary-blue/20 blur-[120px] rounded-full animate-pulse-glow"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center px-6 py-16 lg:py-24">

        <div class="glass-card p-8 lg:p-12 rounded-3xl text-white space-y-6 backdrop-blur-xl bg-white/5 border border-white/10"
            data-aos="fade-right"
            data-aos-duration="1000">

            <h2 class="text-4xl lg:text-6xl font-extrabold tracking-tight leading-tight bg-clip-text text-transparent bg-linear-to-br from-white to-gray-400">
                <?= htmlspecialchars($title) ?>
            </h2>

            <p class="text-lg lg:text-xl leading-relaxed text-gray-300 font-light">
                <?= htmlspecialchars($description) ?>
            </p>

            <div class="flex flex-wrap gap-4 pt-6">
                <?php foreach ($buttons as $index => $btn): ?>
                    <a href="<?= htmlspecialchars($btn['href']) ?>"
                        class="btn-primary relative overflow-hidden group px-8 py-4 rounded-xl shadow-xl transition-all duration-300 hover:-translate-y-1 active:scale-95 bg-blue-600 hover:bg-blue-500"
                        data-aos="fade-up"
                        data-aos-delay="<?= 400 + ($index * 100) ?>">
                        <span class="relative z-10"><?= htmlspecialchars($btn['text']) ?></span>
                        <div class="absolute inset-0 w-1/2 h-full bg-white/20 skew-x-[-25deg] -translate-x-full group-hover:translate-x-[250%] transition-transform duration-700"></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="hidden lg:flex justify-end" data-aos="zoom-in-left" data-aos-duration="1200">
            <div class="relative animate-float">
                <div class="absolute -inset-6 bg-blue-500/30 blur-3xl rounded-full animate-pulse"></div>

                <div class="relative group">
                    <img src="<?= $image ?>"
                        alt="<?= htmlspecialchars($title) ?>"
                        loading="lazy"
                        class="rounded-2xl shadow-2xl transform transition-all duration-700 
                                group-hover:scale-[1.02] group-hover:rotate-1
                                object-cover w-125 h-150 border border-white/20">

                    <div class="absolute inset-0 rounded-2xl bg-linear-to-t from-primary-dark/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
            </div>
        </div>

    </div>
</div>