<?php

// =================================================================
// 1. SESSION CONFIGURATION (MUST BE FIRST - BEFORE ANY OUTPUT)
// =================================================================
if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $isProduction = ($_ENV['APP_ENV'] ?? 'local') === 'production';

    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.cookie_secure', ($isProduction || $isHttps) ? '1' : '0');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_path', '/');
    ini_set('session.cookie_domain', '');
    
    // Tăng thời gian sống
    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_lifetime', 0);
    
    session_start();
    
    // Debug log
    error_log("========== SESSION CONFIG ==========");
    error_log("Session ID: " . session_id());
    error_log("Session Name: " . session_name());
    error_log("Cookie Params: " . json_encode(session_get_cookie_params()));
    error_log("====================================");
}

// =================================================================
// 2. CORS CONFIGURATION
// =================================================================
$http_origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Whitelist origins
$allowedOrigins = [
    'http://localhost:5173',
    'http://localhost:3000',
    'http://127.0.0.1:5173',
    'http://127.0.0.1:3000',
    'http://localhost:8000',
    'http://127.0.0.1:8000',
];

$appUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
if ($appUrl !== '') {
    $allowedOrigins[] = $appUrl;
}

// Vercel preview & production (*.vercel.app)
if ($http_origin !== '' && preg_match('#^https://[\w.-]+\.vercel\.app$#', $http_origin)) {
    $allowedOrigins[] = $http_origin;
}

// Chỉ set origin nếu trong whitelist
if (in_array($http_origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $http_origin");
    header("Access-Control-Allow-Credentials: true");
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 86400");

// Handle Preflight Request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// =================================================================
// 3. ERROR HANDLING CONFIGURATION
// =================================================================
ini_set('display_errors', 0);
ini_set('log_errors', 1);
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0777, true);
}
ini_set('error_log', $logDir . '/error.log');
error_reporting(E_ALL);

// =================================================================
// 4. VIDEO & ASSET HANDLING
// =================================================================
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('#^/videos/(.+)$#', $uri, $matches)) {
    $videoFile = __DIR__ . '/videos/' . $matches[1];
    if (file_exists($videoFile)) {
        header("Content-Type: video/mp4");
        header("Content-Length: " . filesize($videoFile));
        readfile($videoFile);
        exit;
    }
}

// =================================================================
// 5. BOOTSTRAP FRAMEWORK
// =================================================================
try {
    // Load Composer Autoload
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require_once __DIR__ . '/../vendor/autoload.php';
    }

    // Load Application Bootstrap
    $bootstrapPath = __DIR__ . '/../bootstrap.php';
    if (file_exists($bootstrapPath)) {
        require_once $bootstrapPath;
    }

    // Verify Core Application Class
    if (!class_exists('Rinnsan\RinnSanWeb\Core\Application')) {
        throw new Exception("Core Application class not found. Please run 'composer dump-autoload'");
    }

    // Initialize App
    $app = Rinnsan\RinnSanWeb\Core\Application::getInstance();
    
    // Load Routes
    if (method_exists($app, 'getRouter')) {
        $router = $app->getRouter();
        $webRoutes = __DIR__ . '/../routes/web.php';
        $apiRoutes = __DIR__ . '/../routes/api.php'; 
        
        if (file_exists($webRoutes)) require_once $webRoutes;
        if (file_exists($apiRoutes)) require_once $apiRoutes;
    }

    // Run App
    $app->run();

} catch (Exception $e) {
    // =================================================================
    // 6. GLOBAL ERROR HANDLER
    // =================================================================
    if (!headers_sent()) {
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(500);
    }
    
    // Log lỗi chi tiết
    error_log("ERROR: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    echo json_encode([
        'success' => false,
        'message' => 'Internal Server Error: ' . $e->getMessage(),
        'file' => basename($e->getFile()),
        'line' => $e->getLine(),
        'trace' => explode("\n", $e->getTraceAsString())
    ]);
}