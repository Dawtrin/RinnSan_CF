<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Core\Database;

class CartController extends ApiController
{
    public function __construct()
    {
        // 1. CẤU HÌNH SESSION
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 86400,
                'path' => '/',
                'domain' => '', 
                'secure' => false, 
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }

        // 2. Khởi tạo giỏ hàng
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Lấy danh sách giỏ hàng
     * GET /api/cart
     */
    public function index()
    {
        try {
            $cartSession = $_SESSION['cart'];
            $items = [];
            $subtotal = 0;
            $totalQuantity = 0;

            if (!empty($cartSession)) {
                // --- [FIX LỖI SQL CONVERSION] ---
                // Kiểm tra xem session có chứa key rác (string) từ code cũ không
                // Nếu có, reset giỏ hàng ngay lập tức để tránh lỗi 500
                foreach (array_keys($cartSession) as $k) {
                    if (!is_int($k) && !is_numeric($k)) {
                        // Phát hiện key chuỗi (ví dụ: '2_abc...'), Reset cart
                        $_SESSION['cart'] = [];
                        return $this->success([
                            'items' => [],
                            'summary' => ['item_count' => 0, 'quantity_total' => 0, 'subtotal' => 0],
                            'message' => 'Đã làm mới giỏ hàng do cấu trúc cũ không tương thích.'
                        ]);
                    }
                }

                $ids = array_keys($cartSession);
                
                if (count($ids) > 0) {
                    // Query DB
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $sql = "SELECT id, name, price, images, slug FROM products WHERE id IN ($placeholders)";
                    $products = Database::fetchAll($sql, $ids);
                    
                    // Map ID -> Product
                    $productMap = [];
                    foreach ($products as $p) {
                        $productMap[$p['id']] = $p;
                    }

                    // Duyệt Session
                    foreach ($cartSession as $productId => $sessionItem) {
                        if (isset($productMap[$productId])) {
                            $prod = $productMap[$productId];
                            
                            // Xử lý ảnh
                            $image = null;
                            if (!empty($prod['images'])) {
                                $decoded = json_decode($prod['images'], true);
                                $image = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) 
                                    ? $decoded[0] 
                                    : $prod['images'];
                            }

                            $quantity = (int)($sessionItem['quantity'] ?? 1);
                            $price = (float)$prod['price'];
                            $lineTotal = $price * $quantity;
                            
                            // Tạo key cho React
                            $options = $sessionItem['options'] ?? [];
                            $key = $productId . '_' . md5(json_encode($options));

                            $items[] = [
                                'key'        => $key,
                                'product_id' => $prod['id'],
                                'name'       => $prod['name'],
                                'price'      => $price,
                                'image'      => $image,
                                'quantity'   => $quantity,
                                'options'    => $options,
                                'total'      => $lineTotal
                            ];

                            $subtotal += $lineTotal;
                            $totalQuantity += $quantity;
                        }
                    }
                }
            }

            return $this->success([
                'items' => $items,
                'summary' => [
                    'item_count' => count($items),
                    'quantity_total' => $totalQuantity,
                    'subtotal' => $subtotal
                ]
            ]);

        } catch (\Exception $e) {
            return $this->error("Lỗi lấy giỏ hàng: " . $e->getMessage(), 500);
        }
    }

    /**
     * Thêm vào giỏ hàng
     * POST /api/cart/add
     */
    public function add()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data || !isset($data['product_id'])) {
                return $this->error('Dữ liệu không hợp lệ', 400);
            }

            $productId = (int)$data['product_id'];
            $quantity = isset($data['quantity']) ? max(1, (int)$data['quantity']) : 1;
            $options = $data['options'] ?? [];

            // Kiểm tra DB
            $exists = Database::fetch("SELECT id FROM products WHERE id = ?", [$productId]);
            if (!$exists) {
                return $this->error('Sản phẩm không tồn tại', 404);
            }

            // [QUAN TRỌNG] Reset session nếu phát hiện dữ liệu cũ (key không phải số)
            if (!empty($_SESSION['cart'])) {
                $firstKey = array_key_first($_SESSION['cart']);
                if (!is_int($firstKey) && !is_numeric($firstKey)) {
                    $_SESSION['cart'] = [];
                }
            }

            // Thêm vào Session (Key là Product ID - Số nguyên)
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += $quantity;
                $_SESSION['cart'][$productId]['options'] = $options;
            } else {
                $_SESSION['cart'][$productId] = [
                    'quantity' => $quantity,
                    'options'  => $options
                ];
            }

            return $this->index();

        } catch (\Exception $e) {
            return $this->error("Lỗi thêm giỏ hàng: " . $e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật số lượng
     * PUT /api/cart/update
     */
    public function update()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $key = $data['key'] ?? '';
            $quantity = (int)($data['quantity'] ?? 1);
            
            if (!$key) return $this->error('Thiếu key', 400);

            $parts = explode('_', $key);
            $productId = (int)$parts[0];

            if ($productId && isset($_SESSION['cart'][$productId])) {
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$productId]);
                } else {
                    $_SESSION['cart'][$productId]['quantity'] = $quantity;
                }
                return $this->index();
            }

            return $this->error('Không tìm thấy món trong giỏ', 404);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa sản phẩm
     * DELETE /api/cart/remove
     */
    public function remove()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $key = $data['key'] ?? '';
            
            if (!$key) return $this->error('Thiếu key', 400);

            $parts = explode('_', $key);
            $productId = (int)$parts[0];

            if ($productId && isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
                return $this->index();
            }

            return $this->error('Không tìm thấy món cần xóa', 404);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa toàn bộ
     * DELETE /api/cart/clear
     */
    public function clear()
    {
        $_SESSION['cart'] = [];
        return $this->success([], 'Đã làm trống giỏ hàng');
    }
}