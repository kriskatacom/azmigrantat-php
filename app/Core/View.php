<?php

namespace App\Core;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);

        ob_start();
        include __DIR__ . '/../Views/' . $view . '.php';
        $content = ob_get_clean();

        include __DIR__ . '/../Views/layout.php';
    }
}