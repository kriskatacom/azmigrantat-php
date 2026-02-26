<div class="relative group overflow-hidden rounded-xl shadow-md aspect-4/2.5">
    <a href="/<?= $country['slug'] ?>" class="absolute inset-0 z-10" aria-label="<?= htmlspecialchars($country['name']) ?>"></a>

    <img src="<?= $country['image_url'] ?? '/assets/images/default-country.jpg' ?>"
         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
         alt="<?= htmlspecialchars($country['name']) ?>">

    <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>

    <div class="absolute bottom-0 left-0 p-6 w-full z-20 pointer-events-none">
        <h3 class="text-white text-2xl font-bold mb-4 drop-shadow-lg">
            <?= htmlspecialchars($country['name']) ?>
        </h3>

        <span class="inline-block border-2 border-white text-white font-semibold px-6 py-2 rounded-lg group-hover:bg-white group-hover:text-black transition-colors duration-300">
            <?= \App\Services\HelperService::trans('information') ?? 'Информация' ?>
        </span>
    </div>
</div>
