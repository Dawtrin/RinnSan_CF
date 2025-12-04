<?php
// Test SQL Server Connection

echo "=== SQL Server Connection Test ===\n\n";

// 1. Check if PDO SQL Server extension is loaded
echo "1. Checking PDO SQL Server extension...\n";
if (extension_loaded('pdo_sqlsrv')) {
    echo "   ✅ pdo_sqlsrv extension is loaded\n\n";
} else {
    echo "   ❌ pdo_sqlsrv extension is NOT loaded\n";
    echo "   Install it or enable in php.ini\n\n";
    exit(1);
}

// 2. Load environment variables
echo "2. Loading environment variables...\n";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
    echo "   ✅ .env loaded\n\n";
} else {
    echo "   ❌ .env file not found\n\n";
    exit(1);
}

// 3. Print connection details
echo "3. Connection Details:\n";
echo "   Host: " . ($_ENV['DB_HOST'] ?? 'N/A') . "\n";
echo "   Port: " . ($_ENV['DB_PORT'] ?? '1433') . "\n";
echo "   Database: " . ($_ENV['DB_DATABASE'] ?? 'N/A') . "\n";
echo "   Username: " . ($_ENV['DB_USERNAME'] ?? 'sa') . "\n";
echo "   Password: " . (strlen($_ENV['DB_PASSWORD'] ?? '') > 0 ? '****' : 'empty') . "\n\n";

// 4. Test connection
echo "4. Testing connection...\n";
try {
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? '1433';
    $database = $_ENV['DB_DATABASE'] ?? 'RinnSanCF';
    $username = $_ENV['DB_USERNAME'] ?? 'sa';
    $password = $_ENV['DB_PASSWORD'] ?? '';
    $trustedConnection = $_ENV['DB_TRUSTED_CONNECTION'] ?? 'no';

    // Build DSN
    $dsn = "sqlsrv:Server=$host";
    if ($port && $port != 1433) {
        $dsn .= ",$port";
    }
    $dsn .= ";Database=$database;TrustServerCertificate=yes";
    
    echo "   Using: Windows Authentication (current Windows user)\n";
    echo "   DSN: $dsn\n";

    echo "   ✅ Connection successful!\n\n";

    // 5. Test a simple query
    echo "5. Running test query: SELECT 1\n";
    $stmt = $pdo->query("SELECT 1 as test_result");
    $result = $stmt->fetch();
    echo "   ✅ Query result: " . $result['test_result'] . "\n\n";

    // 6. Check tables
    echo "6. Listing tables in database:\n";
    $stmt = $pdo->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE='BASE TABLE'");
    $tables = $stmt->fetchAll();
    
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            echo "   - " . $table['TABLE_NAME'] . "\n";
        }
        echo "\n   ✅ Tables found: " . count($tables) . "\n";
    } else {
        echo "   ⚠️  No tables found in database\n";
    }

    echo "\n✅ ALL TESTS PASSED!\n";

} catch (PDOException $e) {
    echo "   ❌ Connection failed!\n";
    echo "   Error: " . $e->getMessage() . "\n\n";
    exit(1);
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
?>
