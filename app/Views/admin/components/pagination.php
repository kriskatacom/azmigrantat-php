<?php
$current = $pagination['current'];
$total = $pagination['total'];
// Тук е важната промяна: използваме 'limit', защото така идва от BaseController
$perPage = $pagination['limit'] ?? 15; 
$range = 2;

$queryParams = $_GET;

$buildUrl = function($page) use ($queryParams) {
    $params = $queryParams;
    $params['page'] = $page;
    return '?' . http_build_query($params);
};

$baseBase = "inline-flex items-center justify-center border rounded-lg transition shadow-sm font-bold text-sm";
$notActiveStyles = " bg-white border-gray-200 text-gray-600 hover:border-primary-dark hover:text-primary-dark";
$activeStyles = " bg-primary-dark border-primary-dark text-white shadow-md";
?>

<div class="p-5 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between bg-white gap-4">
    <div class="flex items-center gap-2">
        <span class="text-sm text-gray-500 font-medium">Покажи по:</span>
        <select onchange="updatePerPage(this.value)"
            class="bg-white border border-gray-200 text-gray-700 text-sm rounded-lg p-1.5 outline-none cursor-pointer">
            <?php foreach ([5, 10, 15, 20, 50, 100] as $limit): ?>
                <option value="<?= $limit ?>" <?= $perPage == $limit ? 'selected' : '' ?>>
                    <?= $limit ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <nav class="flex items-center gap-1">
        <?php if ($current > 1): ?>
            <a href="<?= $buildUrl($current - 1) ?>" class="<?= $baseBase ?> py-3 px-5 <?= $notActiveStyles ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        <?php endif; ?>

        <?php if ($current > $range + 1): ?>
            <a href="<?= $buildUrl(1) ?>" class="<?= $baseBase ?> py-3 px-5 <?= $notActiveStyles ?>">1</a>
            <?php if ($current > $range + 2): ?>
                <span class="px-2 text-gray-400 font-medium">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php
        for ($i = max(1, $current - $range); $i <= min($total, $current + $range); $i++):
            $isActive = ($i === $current);
            $numClasses = $baseBase . " py-3 px-5 " . ($isActive ? $activeStyles : $notActiveStyles);
        ?>
            <a href="<?= $buildUrl($i) ?>" class="<?= $numClasses ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($current < $total - $range): ?>
            <?php if ($current < $total - $range - 1): ?>
                <span class="px-2 text-gray-400 font-medium">...</span>
            <?php endif; ?>
            <a href="<?= $buildUrl($total) ?>" class="<?= $baseBase ?> py-3 px-5 <?= $notActiveStyles ?>"><?= $total ?></a>
        <?php endif; ?>

        <?php if ($current < $total): ?>
            <a href="<?= $buildUrl($current + 1) ?>" class="<?= $baseBase ?> py-3 px-5 <?= $notActiveStyles ?>">
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

<script>
function updatePerPage(limit) {
    let url = new URL(window.location.href);
    
    url.searchParams.set('per_page', limit);
    url.searchParams.set('page', 1);
    
    window.location.href = url.toString();
}
</script>