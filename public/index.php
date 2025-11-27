<?php
// Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Tải file autoload của Composer
require __DIR__ . '/../vendor/autoload.php';

// 2. Tải file .env
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (Exception $e) {
    die('Lỗi: Không thể tải file .env. ' . $e->getMessage());
}

// 3. KHỞI TẠO ROUTER
// (Namespace phải đúng với file src/Core/Router.php)
$router = new \Rinnsan\RinnSanWeb\Core\Router();

// 4. TẢI FILE "BẢN ĐỒ" ROUTES
// (File này sẽ dùng biến $router ở trên)
require __DIR__ . '/../routes/web.php';


// 5. CHẠY BỘ ĐỊNH TUYẾN
// Router sẽ tự phân tích URL và gọi đúng Controller
$router->dispatch();

?>