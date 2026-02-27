<?php

namespace App\Services;

class FileService
{
    protected static string $basePath = '/uploads';
    protected static string $publicRoot = __DIR__ . '/../../public';

    /**
     * Качва файл и го организира в папки по дата
     */
    public static function upload(array $file, string $subFolder = 'images'): ?string
    {
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // 1. Генериране на път: /uploads/2024/05/22/
        $datePath = '/' . date('Y/m/d');
        $relativeDir = self::$basePath . $datePath;
        $absoluteDir = self::$publicRoot . $relativeDir;

        // 2. Създаване на папките, ако не съществуват
        if (!is_dir($absoluteDir)) {
            mkdir($absoluteDir, 0777, true);
        }

        // 3. Уникално име на файла
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = bin2hex(random_bytes(8)) . '.' . $extension;
        $relativeFilePath = $relativeDir . '/' . $fileName;
        $absoluteFilePath = $absoluteDir . '/' . $fileName;

        // 4. Преместване на файла
        if (move_uploaded_file($file['tmp_name'], $absoluteFilePath)) {
            return $relativeFilePath;
        }

        return null;
    }

    /**
     * Премахва файл от сървъра
     */
    public static function delete(?string $filePath): bool
    {
        if (!$filePath) return false;

        $absolutePath = self::$publicRoot . $filePath;

        if (file_exists($absolutePath) && is_file($absolutePath)) {
            return unlink($absolutePath);
        }

        return false;
    }
}
