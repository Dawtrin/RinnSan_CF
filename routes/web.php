<?php

// Import Controllers mà bạn muốn dùng
use Rinnsan\RinnSanWeb\Controllers\Web\HomeController;
use Rinnsan\RinnSanWeb\Controllers\Api\StatusController;

/**
 * Biến $router được tạo ra từ file public/index.php
 * và được sử dụng ở đây.
 */

// ============================================
// WEB ROUTES - Trả về HTML (cho PHP template)
// ============================================

$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);


// ============================================
// API ROUTES - Trả về JSON (cho React frontend)
// ============================================

$router->get('/api/status', [StatusController::class, 'status']);
$router->get('/api/health', [StatusController::class, 'health']);