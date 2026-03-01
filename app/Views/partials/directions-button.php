<?php

$getCleanDestination = function($input) {
    // 1. Специфично за Embed/Iframe линкове (Търсим !2d и !3d координати)
    // Важно: в Google Maps Embed !2d е Longitude, а !3d е Latitude
    if (preg_match('/!2d([-0-9.]+)!3d([-0-9.]+)/', $input, $matches)) {
        return $matches[2] . ',' . $matches[1]; // Връщаме във формат Lat,Lng
    }

    // 2. Алтернативен Embed формат (понякога се среща в pb= параметъра)
    if (preg_match('/pb=.*!1m.*!1m.*!3d([-0-9.]+)!2m.*!4d([-0-9.]+)/', $input, $matches)) {
        return $matches[1] . ',' . $matches[2];
    }

    // 3. Стандартни точни координати на обекта от браузър линк (!3d...!4d)
    if (preg_match('/!3d([-0-9.]+)!4d([-0-9.]+)/', $input, $matches)) {
        return $matches[1] . ',' . $matches[2];
    }

    // 4. Координати след символа @ (център на екрана)
    if (preg_match('/@([-0-9.]+),([-0-9.]+)/', $input, $matches)) {
        return $matches[1] . ',' . $matches[2];
    }

    // 5. Резервен вариант (директни координати или целия линк)
    return trim($input);
};

$label = $label ?? 'Упътване';
$variant = $variant ?? 'primary';

$cleanDestination = $getCleanDestination($mapsLink);

// Генерираме официален линк за навигация (Direction API)
$directionsUrl = "https://www.google.com/maps/dir/?api=1&destination=" . urlencode($cleanDestination);

$baseClasses = "inline-flex items-center justify-center cursor-pointer px-5 py-2 border border-transparent text-base font-medium rounded-md shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2";
$variants = [
    'primary' => "text-white bg-primary-darken hover:bg-primary-dark",
    'secondary' => "text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500",
    'outline' => "text-gray-700 bg-white border-gray-300 hover:bg-gray-50 focus:ring-blue-500",
];
$currentClass = $variants[$variant] ?? $variants['primary'];
?>

<div 
    x-data="{ 
        isLoading: false,
        destinationUrl: '<?= $directionsUrl ?>',
        
        getDirections() {
            this.isLoading = true;
            setTimeout(() => {
                window.open(this.destinationUrl, '_blank');
                this.isLoading = false;
            }, 800);
        }
    }"
    class="flex flex-col space-y-2"
>
    <button 
        @click="getDirections()"
        :disabled="isLoading"
        class="<?= $baseClasses ?> <?= $currentClass ?> disabled:opacity-50 disabled:cursor-wait"
    >
        <svg x-show="isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <svg x-show="!isLoading" class="-ml-1 mr-2 h-5 w-5" fill="#ff0000" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
        </svg>

        <span x-text="isLoading ? 'Намиране на маршрут...' : '<?= htmlspecialchars($label) ?>'"></span>
    </button>
</div>