<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Product;

class CartController extends ApiController
{
    /**
     * Lấy giỏ hàng
     * GET /api/cart
     */
    public function index()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $cart = $_SESSION['cart'] ?? [];
            $items = [];
            $total = 0;
            
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if ($product && $product['is_active']) {
                    $itemData = [
                        'product_id' => $product['id'],
                        'product_name' => $product['name'],
                        'product_image' => $product['images'] ? json_decode($product['images'], true)[0] ?? null : null,
                        'price' => $product['price'],
                        'quantity' => $item['quantity'],
                        'variant_combination' => $item['variant_combination'] ?? null,
                        'note' => $item['note'] ?? null,
                        'subtotal' => $product['price'] * $item['quantity']
                    ];
                    $items[] = $itemData;
                    $total += $itemData['subtotal'];
                }
            }
            
            return $this->success([
                'items' => $items,
                'total' => $total,
                'item_count' => count($items)
            ], 'Lấy giỏ hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * POST /api/cart/add
     */
    public function add()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['product_id']) || !isset($data['quantity'])) {
                return $this->error('Thiếu product_id hoặc quantity', 400);
            }
            
            $product = Product::find($data['product_id']);
            if (!$product || !$product['is_active']) {
                return $this->error('Sản phẩm không tồn tại hoặc đã ngừng bán', 404);
            }
            
            // Kiểm tra tồn kho
            if ($product['track_quantity'] && $product['quantity'] < $data['quantity']) {
                return $this->error('Không đủ số lượng trong kho', 400);
            }
            
            $cart = $_SESSION['cart'] ?? [];
            $cartKey = $this->getCartKey($data['product_id'], $data['variant_combination'] ?? null);
            
            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] += $data['quantity'];
            } else {
                $cart[$cartKey] = [
                    'product_id' => $data['product_id'],
                    'quantity' => $data['quantity'],
                    'variant_combination' => $data['variant_combination'] ?? null,
                    'note' => $data['note'] ?? null
                ];
            }
            
            $_SESSION['cart'] = $cart;
            
            return $this->success([
                'cart_count' => count($cart),
                'message' => 'Đã thêm vào giỏ hàng'
            ], 'Thêm vào giỏ hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật số lượng trong giỏ hàng
     * PUT /api/cart/update
     */
    public function update()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['product_id']) || !isset($data['quantity'])) {
                return $this->error('Thiếu product_id hoặc quantity', 400);
            }
            
            $cart = $_SESSION['cart'] ?? [];
            $cartKey = $this->getCartKey($data['product_id'], $data['variant_combination'] ?? null);
            
            if (!isset($cart[$cartKey])) {
                return $this->error('Sản phẩm không có trong giỏ hàng', 404);
            }
            
            if ($data['quantity'] <= 0) {
                unset($cart[$cartKey]);
            } else {
                $cart[$cartKey]['quantity'] = $data['quantity'];
                if (isset($data['note'])) {
                    $cart[$cartKey]['note'] = $data['note'];
                }
            }
            
            $_SESSION['cart'] = $cart;
            
            return $this->success([
                'cart_count' => count($cart),
                'message' => 'Cập nhật giỏ hàng thành công'
            ], 'Cập nhật giỏ hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * DELETE /api/cart/remove
     */
    public function remove()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['product_id'])) {
                return $this->error('Thiếu product_id', 400);
            }
            
            $cart = $_SESSION['cart'] ?? [];
            $cartKey = $this->getCartKey($data['product_id'], $data['variant_combination'] ?? null);
            
            if (isset($cart[$cartKey])) {
                unset($cart[$cartKey]);
                $_SESSION['cart'] = $cart;
            }
            
            return $this->success([
                'cart_count' => count($cart),
                'message' => 'Đã xóa khỏi giỏ hàng'
            ], 'Xóa khỏi giỏ hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa toàn bộ giỏ hàng
     * DELETE /api/cart/clear
     */
    public function clear()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            $_SESSION['cart'] = [];
            
            return $this->success([], 'Đã xóa toàn bộ giỏ hàng');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo key cho giỏ hàng
     */
    private function getCartKey($productId, $variantCombination = null)
    {
        $key = (string)$productId;
        if ($variantCombination) {
            if (is_array($variantCombination)) {
                $key .= '_' . md5(json_encode($variantCombination));
            } else {
                $key .= '_' . md5($variantCombination);
            }
        }
        return $key;
    }
}

