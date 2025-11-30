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
        $sql = "SELECT oi.*, p.name as current_product_name, p.images as product_images
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = ?
                ORDER BY oi.id ASC";
        
        return Database::fetchAll($sql, [$orderId]);
    }

    /**
     * Tạo nhiều order items cùng lúc
     */
    public static function createMultiple($orderId, $items)
    {
        $results = [];
        foreach ($items as $item) {
            $item['order_id'] = $orderId;
            if (!isset($item['total_price'])) {
                $item['total_price'] = $item['product_price'] * $item['quantity'];
            }
            $results[] = self::create($item);
        }
        return $results;
    }

    /**
     * Lấy tổng số lượng và giá trị của đơn hàng
     */
    public static function getOrderSummary($orderId)
    {
        $sql = "SELECT 
                    COUNT(*) as item_count,
                    SUM(quantity) as total_quantity,
                    SUM(total_price) as subtotal
                FROM order_items 
                WHERE order_id = ?";
        
        return Database::fetch($sql, [$orderId]);
    }
}

