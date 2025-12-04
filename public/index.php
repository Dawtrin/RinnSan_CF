<?php
// Use manual bootstrap
require_once __DIR__ . '/../bootstrap.php';

// Simple test without classes first
if (!class_exists('Rinnsan\RinnSanWeb\Core\Application')) {
    die("❌ Application class not found. Check bootstrap.php");
}

try {
    $app = Rinnsan\RinnSanWeb\Core\Application::getInstance();
    $router = $app->getRouter();

    // Load routes
    require_once __DIR__ . '/../routes/web.php';
    require_once __DIR__ . '/../routes/api.php';

    $app->run();

} catch (Exception $e) {
    echo "<h1>❌ Server Error</h1>";
    echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    
    if ($_ENV['APP_DEBUG'] ?? false) {
        echo "<pre>";
        echo "Stack Trace:\n";
        echo $e->getTraceAsString();
        echo "</pre>";
    }
}
