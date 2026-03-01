<?php

use App\Services\HelperService;

$style = $style ?? 'grid';
$slug = $country['slug'] ?? '';
$name = $country['name'] ?? '';
$image = $country['image_url'] ?? '/assets/images/default-country.jpg';
$excerpt = $country['excerpt'] ?? '';
$url = '/' . ($base_url ?? '') . $slug;
?>

<?php if ($style === 'grid'): ?>
    <div class="relative group overflow-hidden rounded-xl shadow-md aspect-4/2.5">
        <a href="<?= $url ?>" class="absolute inset-0 z-10" aria-label="<?= htmlspecialchars($name) ?>"></a>

        <img src="<?= $image ?>"
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
            alt="<?= htmlspecialchars($name) ?>">

        <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>

        <div class="absolute bottom-0 left-0 p-6 w-full z-20 pointer-events-none">
            <h3 class="text-white text-2xl font-bold mb-4 drop-shadow-lg">
                <?= htmlspecialchars($name) ?>
            </h3>

            <span class="inline-block border-2 border-white text-white font-semibold px-6 py-2 rounded-lg group-hover:bg-white group-hover:text-black transition-colors duration-300 pointer-events-auto">
                <?= HelperService::trans('information') ?? 'Информация' ?>
            </span>
        </div>
    </div>

<?php else: ?>
    <div class="relative flex flex-row bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
        <a href="<?= $url ?>" class="absolute inset-0 z-10" aria-label="<?= htmlspecialchars($name) ?>"></a>

        <div class="w-5/12 xl:w-4/12 relative overflow-hidden">
            <img src="<?= $image ?>"
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                alt="<?= htmlspecialchars($name) ?>">
        </div>

        <div class="w-7/12 xl:w-8/12 p-5 h-auto flex flex-col justify-between">
            <div>
                <h3 class="text-gray-900 text-xl font-bold mb-2">
                    <?= htmlspecialchars($name) ?>
                </h3>
                <div class="text-gray-500 text-sm line-clamp-2 mb-4 leading-relaxed">
                    <?= strip_tags($excerpt) ?>
                </div>
            </div>

            <div>
                <span class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <?= HelperService::trans('view_more') ?? 'Вижте повече' ?>
                </span>
            </div>
        </div>
    </div>
<?php endif; ?>
