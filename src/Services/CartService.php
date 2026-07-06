<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Product;

class CartService extends Service
{
    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getCart()
    {
        try {
            $this->startSession();
            $cart = $_SESSION['cart'] ?? [];
            
            error_log("CartService::getCart - Session ID: " . session_id());
            error_log("CartService::getCart - Raw cart count: " . count($cart));
            
            // Lọc items không hợp lệ
            $validCart = [];
            foreach ($cart as $key => $item) {
                if (isset($item['product_id']) && isset($item['quantity']) && $item['quantity'] > 0) {
                    $validCart[$key] = $item;
                } else {
                    error_log("CartService: Removing invalid item - " . json_encode($item));
                }
            }
            
            // Refresh thông tin sản phẩm (KHÔNG XÓA nếu không tìm thấy)
            $refreshedCart = $this->refreshCartItems($validCart);
            
            // Cập nhật session
            $_SESSION['cart'] = $refreshedCart;
            
            return $this->formatCart($refreshedCart);
        } catch (\Exception $e) {
            error_log("CartService::getCart error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Trả về cart hiện tại thay vì empty
            return $this->formatCart($_SESSION['cart'] ?? []);
        }
    }

    /**
     * Làm mới thông tin sản phẩm
     * CHỈ CẬP NHẬT, KHÔNG XÓA items
     */
    private function refreshCartItems($cart)
    {
        $refreshedCart = [];
        
        foreach ($cart as $key => $item) {
            try {
                if (!isset($item['product_id'])) {
                    error_log("CartService: Item missing product_id, keeping old data");
                    $refreshedCart[$key] = $item;
                    continue;
                }

                // Thử lấy thông tin product
                $product = null;
                try {
                    $product = Product::find($item['product_id']);
                    error_log("CartService: Product::find({$item['product_id']}) result: " . ($product ? 'FOUND' : 'NULL'));
                } catch (\Exception $e) {
                    error_log("CartService: Error loading product {$item['product_id']}: " . $e->getMessage());
                }
                
                // Nếu KHÔNG TÌM THẤY product -> GIỮ NGUYÊN dữ liệu cũ
                if (!$product) {
                    error_log("CartService: Product {$item['product_id']} not found, KEEPING cached data");
                    $refreshedCart[$key] = $item;
                    continue;
                }

                // CẬP NHẬT thông tin mới từ database
                $item['name'] = $this->getProductName($product);
                $item['image'] = $this->getProductImage($product);
                $item['price'] = $this->getProductPrice($product);
                $item['key'] = $key;
                
                error_log("CartService: Updated product {$item['product_id']} - name: {$item['name']}, price: {$item['price']}");
                
                $refreshedCart[$key] = $item;
                
            } catch (\Exception $e) {
                error_log("CartService::refreshCartItems error for key {$key}: " . $e->getMessage());
                // Giữ nguyên item cũ nếu có lỗi
                $refreshedCart[$key] = $item;
            }
        }
        
        return $refreshedCart;
    }

    /**
     * Lấy tên sản phẩm - FIXED: Product là ARRAY
     */
    private function getProductName($product)
    {
        if (is_array($product)) {
            return $product['name'] ?? $product['ProductName'] ?? 'Sản phẩm';
        }
        // Fallback cho object (tuy không dùng)
        return $product->name ?? $product->ProductName ?? 'Sản phẩm';
    }

    /**
     * Lấy ảnh sản phẩm - FIXED: Product là ARRAY
     */
    private function getProductImage($product)
    {
        $imagesData = null;
        
        if (is_array($product)) {
            $imagesData = $product['images'] ?? $product['MainImage'] ?? null;
        } else {
            $imagesData = $product->images ?? $product->MainImage ?? null;
        }
        
        if (!$imagesData) {
            return null;
        }
        
        // Nếu là JSON string
        if (is_string($imagesData)) {
            $imagesArray = json_decode($imagesData, true);
            
            if (is_array($imagesArray) && count($imagesArray) > 0) {
                return $imagesArray[0];
            }
            
            return $imagesData;
        }
        
        // Nếu đã là array
        if (is_array($imagesData) && count($imagesData) > 0) {
            return $imagesData[0];
        }
        
        return null;
    }

    /**
     * Lấy giá sản phẩm - FIXED: Product là ARRAY
     */
    private function getProductPrice($product)
    {
        if (is_array($product)) {
            return (float)($product['price'] ?? $product['Price'] ?? 0);
        }
        return (float)($product->price ?? $product->Price ?? 0);
    }

    /**
     * Tạo key unique cho cart item
     */
    private function generateKey($productId, $options)
    {
        ksort($options);
        return $productId . '_' . md5(json_encode($options));
    }

    public function addItem($productId, $quantity = 1, $options = [])
    {
        try {
            $this->startSession();
            
            error_log("========================================");
            error_log("CartService::addItem START");
            error_log("Session ID: " . session_id());
            error_log("Product ID: " . $productId);
            error_log("Quantity: " . $quantity);
            
            $product = Product::find($productId);
            
            if (!$product) {
                error_log("CartService::addItem - Product not found: " . $productId);
                error_log("========================================");
                return false;
            }

            error_log("Product found: " . json_encode($product));

            $cart = $_SESSION['cart'] ?? [];
            error_log("Cart before add: " . json_encode($cart));
            
            $key = $this->generateKey($productId, $options);
            error_log("Generated key: " . $key);

            if (!isset($cart[$key])) {
                $cart[$key] = [
                    'key' => $key,
                    'product_id' => $productId,
                    'name' => $this->getProductName($product),
                    'image' => $this->getProductImage($product),
                    'price' => $this->getProductPrice($product),
                    'quantity' => 0,
                    'options' => $options,
                ];
                error_log("Created new cart item");
            } else {
                error_log("Item already exists, updating quantity");
            }

            $cart[$key]['quantity'] += (int)$quantity;
            $_SESSION['cart'] = $cart;

            error_log("Cart after add: " . json_encode($cart));
            error_log("Cart count: " . count($cart));
            error_log("========================================");

            return $this->formatCart($cart);
            
        } catch (\Exception $e) {
            error_log("CartService::addItem ERROR: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function updateItem($key, $quantity)
    {
        try {
            $this->startSession();
            $cart = $_SESSION['cart'] ?? [];

            if (!isset($cart[$key])) {
                error_log("CartService::updateItem - Key not found: {$key}");
                return false;
            }

            $cart[$key]['quantity'] = max(0, (int)$quantity);

            if ($cart[$key]['quantity'] === 0) {
                unset($cart[$key]);
                error_log("CartService::updateItem - Removed item with key: {$key}");
            }

            $_SESSION['cart'] = $cart;
            
            return $this->formatCart($cart);
            
        } catch (\Exception $e) {
            error_log("CartService::updateItem error: " . $e->getMessage());
            throw $e;
        }
    }

    public function removeItem($key)
    {
        try {
            $this->startSession();
            $cart = $_SESSION['cart'] ?? [];

            if (isset($cart[$key])) {
                unset($cart[$key]);
                error_log("CartService::removeItem - Removed key: {$key}");
            }

            $_SESSION['cart'] = $cart;
            return $this->formatCart($cart);
            
        } catch (\Exception $e) {
            error_log("CartService::removeItem error: " . $e->getMessage());
            throw $e;
        }
    }

    public function clear()
    {
        try {
            $this->startSession();
            $_SESSION['cart'] = [];
            error_log("CartService::clear - Cart cleared");
            return $this->formatCart([]);
            
        } catch (\Exception $e) {
            error_log("CartService::clear error: " . $e->getMessage());
            throw $e;
        }
    }

    private function formatCart($cart)
    {
        $items = array_values($cart);
        $subtotal = 0;
        $quantityTotal = 0;

        foreach ($items as $item) {
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 0;
            
            $subtotal += $price * $quantity;
            $quantityTotal += $quantity;
        }

        return [
            'items' => $items,
            'summary' => [
                'item_count' => count($items),
                'quantity_total' => $quantityTotal,
                'subtotal' => $subtotal
            ]
        ];
    }
}