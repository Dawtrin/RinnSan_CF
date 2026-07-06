<?php

namespace Rinnsan\RinnSanWeb\Core;

class Application
{
    private static $instance = null;
    private $database;
    private $router;

    public function __construct()
    {
        self::$instance = $this;
        $this->initialize();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function initialize(): void
    {
        // Load environment variables
        $this->loadEnvironment();

        // Initialize database connection
        $this->database = Database::getInstance();

        // Initialize router
        $this->router = new Router();

        // Set timezone
        date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Ho_Chi_Minh');

        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function loadEnvironment(): void
    {
        $envFile = __DIR__ . '/../../.env';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue;
                }
                
                if (strpos($line, '=') !== false) {
                    list($name, $value) = explode('=', $line, 2);
                    $name = trim($name);
                    $value = trim($value);
                    
                    if (!array_key_exists($name, $_ENV)) {
                        $_ENV[$name] = $value;
                    }
                    if (!array_key_exists($name, $_SERVER)) {
                        $_SERVER[$name] = $value;
                    }
                    putenv("{$name}={$value}");
                }
            }
        }
    }

    public function run(): void
    {
        // --- [FIX QUAN TRỌNG: XỬ LÝ CORS CHO PHÉP ĐĂNG NHẬP & LOAD API] ---
        // 1. Tự động phát hiện và cho phép React (localhost:5173) kết nối
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        // 2. Xử lý request OPTIONS (Preflight)
        // Đây là bước quan trọng nhất để trình duyệt không chặn API Login/Menu
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0); // Dừng ngay, trả về OK để trình duyệt đi tiếp
        }
        // --- [HẾT PHẦN FIX] ---

        try {
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    private function handleException(\Exception $e): void
    {
        $debug = $_ENV['APP_DEBUG'] ?? false;
        $isApiRequest = strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false;
        
        // Set CORS headers for API (Backup)
        if ($isApiRequest && class_exists('\Rinnsan\RinnSanWeb\Helpers\ResponseHelper')) {
            \Rinnsan\RinnSanWeb\Helpers\ResponseHelper::cors();
        }
        
        if ($isApiRequest || $this->isAjaxRequest()) {
            // API error response
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            
            $response = [
                'success' => false,
                'message' => $debug ? $e->getMessage() : 'Internal Server Error',
                'data' => []
            ];
            
            if ($debug) {
                $response['debug'] = [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => explode("\n", $e->getTraceAsString())
                ];
            }
            
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        // Web error response
        if ($debug === 'true' || $debug === true) {
            echo "<pre>";
            echo "Exception: " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
            echo "Trace:\n" . $e->getTraceAsString();
            echo "</pre>";
        } else {
            error_log("Application Error: " . $e->getMessage());
            http_response_code(500);
            echo "An error occurred. Please try again later.";
        }
    }

    private function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function json($data, $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}