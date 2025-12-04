<?php

return [
    'app_name' => $_ENV['APP_NAME'] ?? 'RinnSan Web',
    'app_env' => $_ENV['APP_ENV'] ?? 'local',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    
    'database' => [
        'type' => $_ENV['DB_TYPE'] ?? 'sqlsrv',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 1433,
        'database' => $_ENV['DB_DATABASE'] ?? 'RinnSanCF',
        'username' => $_ENV['DB_USERNAME'] ?? 'sa',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    ],
    
    'cache' => [
        'driver' => 'file',
        'path' => __DIR__ . '/../storage/cache',
    ],
];
