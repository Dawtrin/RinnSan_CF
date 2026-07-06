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


// Load helpers required by Application
$helperFiles = [
    '/src/Helpers/ResponseHelper.php'
];

foreach ($helperFiles as $file) {
    $fullPath = __DIR__ . $file;
    if (file_exists($fullPath)) {
        require_once $fullPath;
    }
}

spl_autoload_register(function ($class) {
    $prefix = 'Rinnsan\\RinnSanWeb\\';
    $baseDir = __DIR__ . '/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
