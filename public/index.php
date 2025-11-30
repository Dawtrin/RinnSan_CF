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

    // Test route
    $router->get('/', function() {
        echo "<h1>🎉 RINNSAN_WEB BACKEND ĐANG CHẠY!</h1>";
        echo "<p>✅ PHP Version: " . PHP_VERSION . "</p>";
        echo "<p>✅ Server Time: " . date('Y-m-d H:i:s') . "</p>";
        
        try {
            $db = Rinnsan\RinnSanWeb\Core\Database::getInstance();
            echo "<p>✅ Database: Connected</p>";
            
            // Test SQL Server query
            $tables = Rinnsan\RinnSanWeb\Core\Database::fetchAll("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES");
            echo "<p>✅ SQL Server: " . count($tables) . " tables found</p>";
        } catch (Exception $e) {
            echo "<p>❌ Database: " . $e->getMessage() . "</p>";
        }
    });

    // Health check
    $router->get('/health', function() {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'service' => 'RINNSAN_WEB Backend'
        ]);
    });

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