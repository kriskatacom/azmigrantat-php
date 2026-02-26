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

    /**
     * Зарежда частичен изглед (partial)
     * @param string $path Пътят до файла в папка Views/partials/
     * @param array $data Данни, които да се използват в partial-а
     */
    public static function loadPartial($path, $data = [])
    {
        extract($data);

        // Тук предполагаме, че държиш малките части в папка Views/partials/
        $filePath = __DIR__ . '/../Views/' . $path . '.php';

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            echo "";
        }
    }
}
