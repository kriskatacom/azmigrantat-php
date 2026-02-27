<?php

namespace App\Services;

use App\Models\User;
use App\Core\Database;

class SeedService
{
    private User $userModel;
    private \PDO $db;

    public function __construct()
    {
        $this->userModel = new User();
        $this->db = Database::getConnection();
    }

    public function seedUsers(int $count = 100): void
    {
        $firstNames = ['Иван', 'Петър', 'Мария', 'Елена', 'Георги', 'Николай', 'Димитър', 'Александър', 'Стефан', 'Радослав'];
        $lastNames = ['Иванов', 'Петров', 'Георгиев', 'Димитров', 'Стоянов', 'Колев', 'Маринов', 'Тодоров', 'Ангелов', 'Янев'];

        echo "🚀 Започване на сийдване на $count потребителя...\n";

        try {
            $this->db->beginTransaction();

            for ($i = 1; $i <= $count; $i++) {
                $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
                $email = "user" . bin2hex(random_bytes(3)) . "@azmigrantat.com"; // По-уникални имейли

                $this->userModel->create([
                    'id' => $this->userModel->generateUuid(),
                    'name' => $name,
                    'email' => $email,
                    'password_hash' => password_hash('password123', PASSWORD_BCRYPT),
                    'role' => 'user',
                    'is_active' => rand(0, 1),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                if ($i % 10 === 0) {
                    echo "✅ Добавени $i...\n";
                }
            }

            $this->db->commit();
            echo "✨ Успешно приключване!\n";
        } catch (\Exception $e) {
            $this->db->rollBack();
            echo "❌ Грешка при сийдване: " . $e->getMessage() . "\n";
        }
    }
}
