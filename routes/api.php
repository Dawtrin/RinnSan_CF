<?php

use Rinnsan\RinnSanWeb\Controllers\Api\StatusController;
use Rinnsan\RinnSanWeb\Controllers\Api\ProductController;
use Rinnsan\RinnSanWeb\Controllers\Api\CategoryController;
use Rinnsan\RinnSanWeb\Controllers\Api\OrderController;
use Rinnsan\RinnSanWeb\Controllers\Api\PaymentController;
use Rinnsan\RinnSanWeb\Controllers\Api\CouponController;
use Rinnsan\RinnSanWeb\Controllers\Api\AuthController;
use Rinnsan\RinnSanWeb\Controllers\Api\CartController;
use Rinnsan\RinnSanWeb\Controllers\Api\UploadController;
use Rinnsan\RinnSanWeb\Controllers\Api\SettingsController;
use Rinnsan\RinnSanWeb\Controllers\Api\AdminController;
use Rinnsan\RinnSanWeb\Controllers\Api\InventoryController;
use Rinnsan\RinnSanWeb\Controllers\Api\UserController;
use Rinnsan\RinnSanWeb\Controllers\Api\Admin\AdminUserController;
use Rinnsan\RinnSanWeb\Controllers\Api\Admin\AdminCouponController;
use Rinnsan\RinnSanWeb\Controllers\Api\Admin\AdminSupplierController;
use Rinnsan\RinnSanWeb\Controllers\Api\Admin\AdminRoleController;
use Rinnsan\RinnSanWeb\Controllers\Api\Admin\AdminActivityLogController;
use Rinnsan\RinnSanWeb\Controllers\Api\Admin\AdminBulkController;

// Status & Health
$router->get('/api/status', [StatusController::class, 'status']);
$router->get('/api/health', [StatusController::class, 'health']);

// Auth Routes
$router->post('/api/auth/login', [AuthController::class, 'login']);
$router->post('/api/auth/register', [AuthController::class, 'register']);
$router->get('/api/auth/me', [AuthController::class, 'me']);
$router->put('/api/auth/profile', [AuthController::class, 'updateProfile']);
$router->post('/api/auth/logout', [AuthController::class, 'logout']);

// Product Routes
$router->get('/api/products', [ProductController::class, 'index']);
$router->get('/api/products/{id}', [ProductController::class, 'show']);
$router->get('/api/products/{id}/variants', [ProductController::class, 'variants']);
$router->get('/api/products/{id}/options', [ProductController::class, 'options']);
$router->post('/api/products', [ProductController::class, 'store']);
$router->put('/api/products/{id}', [ProductController::class, 'update']);
$router->delete('/api/products/{id}', [ProductController::class, 'destroy']);

// Category Routes
$router->get('/api/categories', [CategoryController::class, 'index']);
$router->get('/api/categories/{id}', [CategoryController::class, 'show']);
$router->get('/api/categories/slug/{slug}', [CategoryController::class, 'showBySlug']);
$router->post('/api/categories', [CategoryController::class, 'store']);
$router->put('/api/categories/{id}', [CategoryController::class, 'update']);

// Order Routes
$router->get('/api/orders', [OrderController::class, 'index']);
$router->get('/api/orders/{id}', [OrderController::class, 'show']);
$router->post('/api/orders', [OrderController::class, 'store']);
$router->put('/api/orders/{id}/status', [OrderController::class, 'updateStatus']);
$router->get('/api/orders/statistics', [OrderController::class, 'statistics']);

// Admin Order Management
$router->put('/api/admin/orders/{id}', [OrderController::class, 'adminUpdate']);
$router->delete('/api/admin/orders/{id}', [OrderController::class, 'adminDestroy']);

// Payment Routes
$router->post('/api/payments', [PaymentController::class, 'store']);
$router->put('/api/payments/{id}/status', [PaymentController::class, 'updateStatus']);
$router->get('/api/payments/order/{orderId}', [PaymentController::class, 'getByOrder']);

// Coupon Routes
$router->get('/api/coupons', [CouponController::class, 'index']);
$router->get('/api/coupons/{id}', [CouponController::class, 'show']);
$router->post('/api/coupons/validate', [CouponController::class, 'validate']);

// Cart Routes
$router->get('/api/cart', [CartController::class, 'index']);
$router->post('/api/cart/add', [CartController::class, 'add']);
$router->put('/api/cart/update', [CartController::class, 'update']);
$router->delete('/api/cart/remove', [CartController::class, 'remove']);
$router->delete('/api/cart/clear', [CartController::class, 'clear']);

// Upload Routes
$router->post('/api/upload', [UploadController::class, 'upload']);
$router->post('/api/upload/multiple', [UploadController::class, 'uploadMultiple']);
$router->delete('/api/upload/{filename}', [UploadController::class, 'delete']);

// Settings Routes
$router->get('/api/settings', [SettingsController::class, 'index']);
$router->get('/api/settings/{key}', [SettingsController::class, 'show']);
$router->post('/api/settings', [SettingsController::class, 'store']);
$router->put('/api/settings/batch', [SettingsController::class, 'batchUpdate']);

// Inventory Routes
$router->get('/api/inventory', [InventoryController::class, 'index']);
$router->get('/api/inventory/{id}', [InventoryController::class, 'show']);
$router->get('/api/inventory/low-stock', [InventoryController::class, 'lowStock']);
$router->post('/api/inventory/transactions', [InventoryController::class, 'createTransaction']);

// Admin Inventory Management
$router->post('/api/admin/inventory', [InventoryController::class, 'store']);
$router->put('/api/admin/inventory/{id}', [InventoryController::class, 'update']);
$router->delete('/api/admin/inventory/{id}', [InventoryController::class, 'destroy']);

// User Management Routes (Admin only)
$router->get('/api/users', [UserController::class, 'index']);
$router->get('/api/users/{id}', [UserController::class, 'show']);
$router->put('/api/users/{id}', [UserController::class, 'update']);

// Admin Dashboard & Statistics
$router->get('/api/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/api/admin/statistics', [AdminController::class, 'statistics']);
$router->get('/api/admin/orders/recent', [AdminController::class, 'recentOrders']);
$router->get('/api/admin/products/top-selling', [AdminController::class, 'topSellingProducts']);

// Admin User Management
$router->post('/api/admin/users', [AdminUserController::class, 'store']);
$router->delete('/api/admin/users/{id}', [AdminUserController::class, 'destroy']);
$router->put('/api/admin/users/{id}/activate', [AdminUserController::class, 'activate']);
$router->put('/api/admin/users/{id}/role', [AdminUserController::class, 'changeRole']);

// Admin Coupon Management
$router->post('/api/admin/coupons', [AdminCouponController::class, 'store']);
$router->put('/api/admin/coupons/{id}', [AdminCouponController::class, 'update']);
$router->delete('/api/admin/coupons/{id}', [AdminCouponController::class, 'destroy']);

// Admin Supplier Management
$router->get('/api/admin/suppliers', [AdminSupplierController::class, 'index']);
$router->post('/api/admin/suppliers', [AdminSupplierController::class, 'store']);
$router->put('/api/admin/suppliers/{id}', [AdminSupplierController::class, 'update']);
$router->delete('/api/admin/suppliers/{id}', [AdminSupplierController::class, 'destroy']);

// Admin Role Management
$router->get('/api/admin/roles', [AdminRoleController::class, 'index']);
$router->post('/api/admin/roles', [AdminRoleController::class, 'store']);
$router->put('/api/admin/roles/{id}', [AdminRoleController::class, 'update']);
$router->delete('/api/admin/roles/{id}', [AdminRoleController::class, 'destroy']);

// Admin Activity Logs
$router->get('/api/admin/activity-logs', [AdminActivityLogController::class, 'index']);
$router->get('/api/admin/activity-logs/{id}', [AdminActivityLogController::class, 'show']);
$router->delete('/api/admin/activity-logs', [AdminActivityLogController::class, 'destroy']);

// Admin Bulk Operations
$router->post('/api/admin/bulk/delete', [AdminBulkController::class, 'delete']);
$router->post('/api/admin/bulk/update', [AdminBulkController::class, 'update']);
$router->post('/api/admin/bulk/activate', [AdminBulkController::class, 'activate']);

