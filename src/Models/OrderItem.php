<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'product_price',
        'variant_combination', 'quantity', 'total_price', 'note'
    ];

    /**
     * Lấy chi tiết đơn hàng theo order_id
     */
    public static function getByOrderId($orderId)
    {
        try {
            $sql = "SELECT oi.*, 
                    COALESCE(p.name, oi.product_name) as current_product_name, 
                    p.images as product_images
                    FROM order_items oi
                    LEFT JOIN products p ON oi.product_id = p.id
                    WHERE oi.order_id = ?
                    ORDER BY oi.id ASC";
            
            $items = Database::fetchAll($sql, [$orderId]);

            // Xử lý ảnh
            foreach ($items as &$item) {
                if (isset($item['product_images']) && is_string($item['product_images'])) {
                    $imgs = json_decode($item['product_images'], true);
                    $item['image'] = is_array($imgs) && !empty($imgs) ? $imgs[0] : null;
                } else {
                    $item['image'] = null;
                }
            }

            return $items;
            
        } catch (\Exception $e) {
            error_log("OrderItem::getByOrderId error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Tạo nhiều order items cùng lúc - FIXED VERSION
     */
    public static function createMultiple($orderId, $items)
    {
        try {
            if (empty($items)) {
                error_log("⚠️ OrderItem::createMultiple - No items provided");
                return false;
            }

            foreach ($items as $item) {
                $sql = "INSERT INTO order_items (
                    order_id, product_id, product_name, product_price,
                    variant_combination, quantity, total_price, note, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, GETDATE())";

                // Tính total_price nếu chưa có
                if (!isset($item['total_price'])) {
                    $item['total_price'] = $item['product_price'] * $item['quantity'];
                }

                $params = [
                    $orderId,
                    $item['product_id'],
                    $item['product_name'] ?? 'Sản phẩm',
                    $item['product_price'] ?? 0,
                    $item['variant_combination'] ?? null,
                    $item['quantity'] ?? 1,
                    $item['total_price'],
                    $item['note'] ?? ''
                ];

                Database::query($sql, $params);
            }

            error_log("✅ OrderItem::createMultiple - Created " . count($items) . " items for order ID: " . $orderId);
            return true;
            
        } catch (\Exception $e) {
            error_log("❌ OrderItem::createMultiple error: " . $e->getMessage());
            error_log("Order ID: " . $orderId);
            error_log("Items: " . json_encode($items));
            throw $e;
        }
    }

    /**
     * Lấy tổng số lượng và giá trị của đơn hàng
     */
    public static function getOrderSummary($orderId)
    {
        try {
            $sql = "SELECT 
                        COUNT(*) as item_count,
                        SUM(quantity) as total_quantity,
                        SUM(total_price) as subtotal
                    FROM order_items 
                    WHERE order_id = ?";
            
            return Database::fetch($sql, [$orderId]);
            
        } catch (\Exception $e) {
            error_log("OrderItem::getOrderSummary error: " . $e->getMessage());
            return [
                'item_count' => 0,
                'total_quantity' => 0,
                'subtotal' => 0
            ];
        }
    }

    /**
     * Xóa items theo order ID
     */
    public static function deleteByOrderId($orderId)
    {
        try {
            $sql = "DELETE FROM order_items WHERE order_id = ?";
            Database::query($sql, [$orderId]);
            
            error_log("✅ OrderItem::deleteByOrderId - Deleted items for order ID: " . $orderId);
            return true;
            
        } catch (\Exception $e) {
            error_log("OrderItem::deleteByOrderId error: " . $e->getMessage());
            throw $e;
        }
    }
}