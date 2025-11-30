<?php
// ==================== ERROR HANDLING ====================
error_reporting(E_ALL);
ini_set('display_errors', '1');

// ==================== MANUAL REQUIRE WITH ERROR HANDLING ====================
$coreFiles = [
    '/src/Core/Database.php',
    '/src/Core/Router.php', 
    '/src/Core/Application.php'
];

foreach ($coreFiles as $file) {
    $fullPath = __DIR__ . $file;
    if (file_exists($fullPath)) {
        require_once $fullPath;
    } else {
        die("❌ Missing file: $file");
    }
}

// ==================== LOAD ENVIRONMENT ====================
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            putenv("{$name}={$value}");
        }
    }
}

echo "<!-- Bootstrap loaded successfully -->";