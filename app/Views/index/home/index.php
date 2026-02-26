<?php

use App\Core\View;
use App\Services\HelperService;

?>

<?php View::component('hero', "index/home/components", ['heroImage' => '/assets/images/azmigrantat-hero-background.webp']); ?>

<main class="mx-auto py-5 md:py-10">
    <h2 class="text-3xl font-bold text-center mb-10">Държави</h2>

    <div id="countries-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
        <?php foreach ($countries as $index => $country): ?>
            <div class="country-item <?= $index >= 8 ? 'hidden' : '' ?>">
                <?php View::component('country-card', "index/home/components", ['country' => $country]); ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($countries) > 8): ?>
        <div class="flex justify-center mt-5 md:mt-10">
            <button id="load-more-btn" class="bg-white border border-gray-200 text-gray-800 font-semibold px-10 py-3 rounded-lg shadow-sm hover:bg-gray-50 transition-all cursor-pointer">
                <?= HelperService::trans('load_more') ?? 'Зареждане на още' ?>
            </button>
        </div>
    <?php endif; ?>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreBtn = document.getElementById('load-more-btn');
        const itemsPerPage = 8;

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const hiddenItems = document.querySelectorAll('.country-item.hidden');

                for (let i = 0; i < itemsPerPage && i < hiddenItems.length; i++) {
                    hiddenItems[i].classList.remove('hidden');
                    hiddenItems[i].classList.add('animate-fade-in');
                }

                if (document.querySelectorAll('.country-item.hidden').length === 0) {
                    loadMoreBtn.parentElement.remove();
                }
            });
        }
    });
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease forwards;
    }
</style>
