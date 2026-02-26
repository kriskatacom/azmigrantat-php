<?php

namespace App\Services;

use App\Core\Database;
use App\Models\User;
use Exception;

class DatabaseService
{
    public function runMigrations(): array
    {
        $messages = [];
        $migrationsPath = dirname(__DIR__, 2) . '/database/migrations/';
        $files = glob($migrationsPath . "*.sql");

        if (empty($files)) {
            throw new Exception("Не са намерени .sql файлове в $migrationsPath");
        }

        foreach ($files as $file) {
            $sql = file_get_contents($file);
            Database::getConnection()->exec($sql);
            $messages[] = "Изпълнен файл: " . basename($file);
        }

        return $messages;
    }

    public function runSeeds(): string
    {
        $userModel = new User();
        $adminEmail = 'admin@azmigrantat.com';

        $existing = $userModel->where('email', $adminEmail);

        if (!empty($existing)) {
            return "ℹ️ Админът вече съществува. Seeding-ът е прескочен.";
        }

        $adminData = [
            'id'            => $userModel->generateUuid(),
            'email'         => $adminEmail,
            'password_hash' => password_hash('admin', PASSWORD_DEFAULT),
            'name'          => 'Кристиан Костадинов',
            'role'          => 'admin',
            'is_active'     => 1,
            'email_verified' => 1
        ];

        if ($userModel->create($adminData)) {
            return "✅ Тестовите данни (Админ) са налети успешно.";
        }

        throw new Exception("Грешка при създаване на админ потребител.");
    }
}