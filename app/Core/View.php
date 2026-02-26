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

    public static function loadPartial($path, $data = [])
    {
        extract($data);

        $filePath = __DIR__ . '/../Views/' . $path . '.php';

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            echo "";
        }
    }

    public static function component(string $name, string $folder = "components", array $data = [])
    {
        extract($data);

        $folderPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $folder);
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $folderPath . DIRECTORY_SEPARATOR . $name . '.php';

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            var_dump($filePath);
        }
    }
}