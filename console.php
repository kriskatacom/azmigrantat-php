<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\DatabaseService;

$command = $argv[1] ?? null;

if ($command === 'db:setup') {
    echo "🚀 Стартиране на миграции...\n";
    try {
        $service = new DatabaseService();
        $logs = $service->runMigrations();
        
        foreach ($logs as $log) {
            echo "  [OK] $log\n";
        }
        
        echo $service->runSeeds() . "\n";
        echo "✨ Всичко приключи успешно!\n";
    } catch (Exception $e) {
        echo "🔥 ГРЕШКА: " . $e->getMessage() . "\n";
    }
}