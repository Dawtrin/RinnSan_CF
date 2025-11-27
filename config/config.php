<?php

return [
    'app_name' => 'RinnSan Web',
    'app_env' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => $_ENV['APP_DEBUG'] ?? false,
    
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'database' => $_ENV['DB_DATABASE'] ?? 'rinnsan_web',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => 'utf8mb4',
    ],
    
    'cache' => [
        'driver' => 'file',
        'path' => __DIR__ . '/../storage/cache',
    ],
];
