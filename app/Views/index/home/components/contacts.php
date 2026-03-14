<?php

use App\Core\View;
use App\Services\HelperService;

$title_part_1 = $title_part_1 ?? HelperService::trans('how_to_get_there_to');
$title_part_2 = $title_part_2 ?? '" ' . HelperService::trans('i_the_migrant') . ' " ?';
$button_text  = $button_text ?? HelperService::trans('how_to_get_there') . ' ?';
$button_href  = $button_href ?? 'https://www.google.com/maps/place/%D0%90%D0%B7+%D0%9C%D0%B8%D0%B3%D1%80%D0%B0%D0%BD%D1%82%D1%8A%D1%82/@43.4067945,23.2264338,17z/data=!3m1!4b1!4m6!3m5!1s0x40ab3352afb15871:0x3fda5155980ee2df!8m2!3d43.4067906!4d23.2290087!16s%2Fg%2F11ycyv78l9!5m1!1e1?entry=ttu&g_ep=EgoyMDI2MDMxMS4wIKXMDSoASAFQAw%3D%3D';
?>

<div class="bg-primary-dark py-5 md:py-10 text-2xl md:text-4xl font-semibold text-center shadow-inner">
    <div class="uppercase text-primary-blue tracking-tighter animate-fade-in">
        <?= htmlspecialchars($title_part_1) ?>
    </div>

    <div class="uppercase text-primary-light tracking-tighter mt-1">
        <?= htmlspecialchars($title_part_2) ?>
    </div>

    <div class="flex justify-start p-5">
        <?php View::component("directions-button", "partials", [
            'mapsLink' => $button_href,
            'label' => $button_text,
            'variant' => 'primary'
        ]); ?>
    </div>

    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2898.5758271389627!2d23.226433776520615!3d43.40679446833233!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40ab3352afb15871%3A0x3fda5155980ee2df!2z0JDQtyDQnNC40LPRgNCw0L3RgtGK0YI!5e0!3m2!1sen!2sbg!4v1773373296613!5m2!1sen!2sbg" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>