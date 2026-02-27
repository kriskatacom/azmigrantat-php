<?php
$current = $pagination['current'];
$total = $pagination['total'];
$perPage = $pagination['per_page'] ?? 10;
$range = 2;

$perPageQuery = "&per_page=" . $perPage;

$baseBase = "inline-flex items-center justify-center border rounded-lg transition shadow-sm font-bold text-sm";
$notActiveStyles = " bg-white border-gray-200 text-gray-600 hover:border-primary-dark hover:text-primary-dark";
$activeStyles = " bg-primary-dark border-primary-dark text-white shadow-md";
?>

<div class="p-5 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between bg-white gap-4">
    <div class="flex items-center gap-2">
        <span class="text-sm text-gray-500 font-medium">Покажи по:</span>
        <select onchange="window.location.href='?page=1&per_page=' + this.value"
            class="bg-white border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-primary-light focus:border-primary-light p-1.5 shadow-sm outline-none transition cursor-pointer">
            <?php foreach ([5, 10, 20, 50, 100] as $limit): ?>
                <option value="<?= $limit ?>" <?= $perPage == $limit ? 'selected' : '' ?>>
                    <?= $limit ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <nav class="flex items-center gap-1">
        <?php if ($current > 1): 
            $prevClasses = $baseBase . " py-3 px-5" . $notActiveStyles;
        ?>
            <a href="?page=<?= $current - 1 ?><?= $perPageQuery ?>" class="<?= $prevClasses ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        <?php endif; ?>

        <?php if ($current > $range + 1): 
            $firstPageClasses = $baseBase . " py-3 px-5" . $notActiveStyles;
        ?>
            <a href="?page=1<?= $perPageQuery ?>" class="<?= $firstPageClasses ?>">1</a>
            <?php if ($current > $range + 2): ?>
                <span class="px-2 text-gray-400 font-medium">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php
        for ($i = max(1, $current - $range); $i <= min($total, $current + $range); $i++):
            $isActive = ($i === $current);
            $numClasses = $baseBase . " py-3 px-5";
            $numClasses .= $isActive ? $activeStyles : $notActiveStyles;
        ?>
            <a href="?page=<?= $i ?><?= $perPageQuery ?>" class="<?= $numClasses ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($current < $total - $range): ?>
            <?php if ($current < $total - $range - 1): ?>
                <span class="px-2 text-gray-400 font-medium">...</span>
            <?php endif; 
            $lastPageClasses = $baseBase . " py-3 px-5" . $notActiveStyles;
            ?>
            <a href="?page=<?= $total ?><?= $perPageQuery ?>" class="<?= $lastPageClasses ?>"><?= $total ?></a>
        <?php endif; ?>

        <?php if ($current < $total): 
            $nextClasses = $baseBase . " py-3 px-5" . $notActiveStyles;
        ?>
            <a href="?page=<?= $current + 1 ?><?= $perPageQuery ?>" class="<?= $nextClasses ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        <?php endif; ?>
    </nav>
    
    <div class="text-sm text-gray-500">
        Страница <span class="font-bold text-[#1e293b]"><?= $current ?></span> от <span class="font-bold text-[#1e293b]"><?= $total ?></span>
    </div>
</div>
