<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'order_code', 'user_id', 'customer_name', 'customer_phone', 'customer_email',
        'shipping_address', 'item_count', 'quantity_total', 'subtotal', 
        'discount_amount', 'shipping_fee', 'tax_amount', 'total_amount',
        'order_status', 'payment_status', 'payment_method', 'note', 
        'cancelled_reason', 'completed_at', 'created_at', 'updated_at'
    ];

    /**
     * Tạo mã đơn hàng tự động
     */
    public static function generateOrderCode()
    {
        try {
            $prefix = 'CAFE';
            $date = date('Ymd');
            $sql = "SELECT COUNT(*) as count FROM orders WHERE order_code LIKE ?";
            $param = "{$prefix}-{$date}-%";
            $result = Database::fetch($sql, [$param]);
            $count = ($result['count'] ?? 0) + 1;
            
            return "{$prefix}-{$date}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            error_log("Order::generateOrderCode error: " . $e->getMessage());
            // Fallback: timestamp-based code
            return 'CAFE-' . date('Ymd-His');
        }
    }

    /**
     * Tạo đơn hàng mới - FIXED VERSION
     */
    public static function createOrder($data)
    {
        try {
            if (!isset($data['order_code'])) {
                $data['order_code'] = self::generateOrderCode();
            }

            $sql = "INSERT INTO orders (
                order_code, user_id, customer_name, customer_phone, customer_email,
                shipping_address, note, item_count, quantity_total, subtotal,
                discount_amount, shipping_fee, tax_amount, total_amount,
                order_status, payment_status, payment_method, created_at, updated_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE(), GETDATE()
            )";

            $params = [
                $data['order_code'],
                $data['user_id'] ?? null,
                $data['customer_name'] ?? 'Khách lẻ',
                $data['customer_phone'] ?? '',
                $data['customer_email'] ?? '',
                $data['shipping_address'] ?? '',
                $data['note'] ?? '',
                $data['item_count'] ?? 0,
                $data['quantity_total'] ?? 0,
                $data['subtotal'] ?? 0,
                $data['discount_amount'] ?? 0,
                $data['shipping_fee'] ?? 0,
                $data['tax_amount'] ?? 0,
                $data['total_amount'] ?? 0,
                $data['order_status'] ?? 'pending',
                $data['payment_status'] ?? 'unpaid',
                $data['payment_method'] ?? 'cash'
            ];

            Database::query($sql, $params);
            
            error_log("✅ Order created: " . $data['order_code']);
            return true;
            
        } catch (\Exception $e) {
            error_log("❌ Order::createOrder error: " . $e->getMessage());
            error_log("Data: " . json_encode($data));
            throw $e;
        }
    }

    /**
     * Tìm đơn hàng theo ID
     */
    public static function find($id)
    {
        try {
            $sql = "SELECT * FROM orders WHERE id = ?";
            return Database::fetch($sql, [$id]);
        } catch (\Exception $e) {
            error_log("Order::find error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy đơn hàng theo mã đơn
     */
    public static function findByCode($orderCode)
    {
        try {
            $sql = "SELECT * FROM orders WHERE order_code = ?";
            $order = Database::fetch($sql, [$orderCode]);
            
            if ($order) {
                error_log("✅ Order found: {$orderCode}, ID: {$order['id']}");
            } else {
                error_log("❌ Order NOT found: {$orderCode}");
            }
            
            return $order;
        } catch (\Exception $e) {
            error_log("Order::findByCode error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy đơn hàng kèm items
     */
    public static function findWithItems($id)
    {
        try {
            $order = self::find($id);
            
            if (!$order) {
                error_log("❌ Order not found: ID {$id}");
                return null;
            }

            // Lấy items
            $order['items'] = OrderItem::getByOrderId($id);
            
            error_log("✅ Order loaded with " . count($order['items']) . " items");
            return $order;
            
        } catch (\Exception $e) {
            error_log("Order::findWithItems error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Lấy đơn hàng theo user - FIXED (chỉ 1 lần khai báo)
     */
    public static function getByUserId($userId, $limit = 20)
    {
        try {
            $sql = "SELECT TOP {$limit} * FROM orders 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC";
            
            return Database::fetchAll($sql, [$userId]);
        } catch (\Exception $e) {
            error_log("Order::getByUserId error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Cập nhật trạng thái đơn hàng - FIXED VERSION
     */
    public static function updateStatus($id, $status, $reason = null)
    {
        try {
            $sql = "UPDATE orders SET 
                    order_status = ?, 
                    updated_at = GETDATE()";
            
            $params = [$status];
            
            if ($status === 'completed') {
                $sql .= ", completed_at = GETDATE()";
            }
            
            if ($status === 'cancelled' && $reason) {
                $sql .= ", cancelled_reason = ?";
                $params[] = $reason;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            Database::query($sql, $params);
            
            error_log("✅ Order status updated: ID={$id}, Status={$status}");
            return true;
            
        } catch (\Exception $e) {
            error_log("Order::updateStatus error: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Lấy đơn hàng theo trạng thái
     */
    public static function getByStatus($status, $limit = 50)
    {
        try {
            $sql = "SELECT TOP {$limit} o.*, 
                    COALESCE(u.full_name, o.customer_name, N'Khách vãng lai') as customer_display_name
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.id
                    WHERE o.order_status = ?
                    ORDER BY o.created_at DESC";
            
            return Database::fetchAll($sql, [$status]);
        } catch (\Exception $e) {
            error_log("Order::getByStatus error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy thống kê đơn hàng
     */
    public static function getStatistics($startDate = null, $endDate = null)
    {
        try {
            $where = "1=1";
            $params = [];
            
            if ($startDate) {
                $where .= " AND created_at >= ?";
                $params[] = $startDate;
            }
            
            if ($endDate) {
                $where .= " AND created_at <= ?";
                $params[] = $endDate;
            }
            
            $sql = "SELECT 
                        COUNT(*) as total_orders,
                        SUM(CASE WHEN order_status = 'completed' THEN 1 ELSE 0 END) as completed_orders,
                        SUM(CASE WHEN order_status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                        SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                        SUM(total_amount) as total_revenue,
                        AVG(total_amount) as avg_order_value
                    FROM orders 
                    WHERE {$where}";
            
            return Database::fetch($sql, $params);
            
        } catch (\Exception $e) {
            error_log("Order::getStatistics error: " . $e->getMessage());
            return [
                'total_orders' => 0,
                'completed_orders' => 0,
                'pending_orders' => 0,
                'cancelled_orders' => 0,
                'total_revenue' => 0,
                'avg_order_value' => 0
            ];
        }
    }
}