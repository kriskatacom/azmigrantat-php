<?php
$src = $src ?? '';
$alt = $alt ?? 'Image';
$size = $size ?? 'w-32 h-20';
?>

<div class="<?= $size ?> rounded-md overflow-hidden border border-gray-100 shadow-sm bg-gray-50 shrink-0 group-hover:ring-2 group-hover:ring-indigo-100 transition-all">
    <img src="<?= \App\Services\HelperService::getImage($src) ?>"
         alt="<?= htmlspecialchars($alt) ?>"
         class="w-full h-full object-cover">
</div>
