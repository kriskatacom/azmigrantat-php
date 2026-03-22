<div class="flex flex-wrap items-center justify-center gap-5 px-5">
    <?php
    $baseBtn = "relative group flex items-center gap-3 md:gap-5 px-5 py-4 md:px-8 md:py-3 rounded-2xl border-2 transition-all duration-500 no-underline w-full md:w-auto ";
    $cityActive = ($activeScope === 'city');
    $countryActive = ($activeScope === 'country');
    ?>

    <a href="?scope=city" class="<?= $baseBtn ?> <?= $cityActive ? 'bg-primary-dark border-primary shadow-lg text-white' : 'bg-white border-gray-200 text-gray-600 hover:border-primary hover:text-primary hover:bg-white' ?>">
        <div class="flex items-center justify-center w-12 h-12 md:w-10 md:h-10 shrink-0 rounded-full <?= $cityActive ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-primary/10' ?>">
            <i class="fa-solid fa-location-dot <?= $cityActive ? 'text-white' : 'text-gray-500 group-hover:text-primary' ?>"></i>
        </div>
        <div class="flex flex-col text-left">
            <span class="md:text-[9px] uppercase tracking-[0.15em] font-bold opacity-70">Филтър: Месец</span>
            <span class="text-base md:text-sm lg:text-base font-extrabold whitespace-nowrap"><?= htmlspecialchars($cityName) ?></span>
        </div>
    </a>

    <a href="?scope=country" class="<?= $baseBtn ?> <?= $countryActive ? 'bg-primary-dark border-primary shadow-lg text-white' : 'bg-white border-gray-200 text-gray-600 hover:border-primary hover:text-primary hover:bg-white' ?>">
        <div class="flex items-center justify-center w-12 h-12 md:w-10 md:h-10 shrink-0 rounded-full <?= $countryActive ? 'bg-white/10' : 'bg-gray-100 group-hover:bg-primary-dark/10' ?>">
            <i class="fa-solid fa-globe <?= $countryActive ? 'text-white' : 'text-gray-500 group-hover:text-primary-dark' ?>"></i>
        </div>
        <div class="flex flex-col text-left">
            <span class="md:text-[9px] uppercase tracking-[0.15em] font-bold opacity-70">Филтър: Месец</span>
            <span class="text-base md:text-sm lg:text-base font-extrabold whitespace-nowrap"><?= htmlspecialchars($countryName) ?></span>
        </div>
    </a>

    <?php if ($activeScope): ?>
        <div class="w-full flex justify-center">
            <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="btn-primary bg-red-500 hover:bg-red-600 border-none">
                <i class="fa-solid fa-xmark mr-2"></i>Изчисти филтрите
            </a>
        </div>
    <?php endif; ?>
</div>
