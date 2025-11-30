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
        'cancelled_reason', 'completed_at'
    ];

    /**
     * Tạo mã đơn hàng tự động
     */
    public static function generateOrderCode()
    {
        $prefix = 'CAFE';
        $date = date('Ymd');
        $sql = "SELECT COUNT(*) as count FROM orders WHERE order_code LIKE ?";
        $result = Database::fetch($sql, ["{$prefix}-{$date}-%"]);
        $count = ($result['count'] ?? 0) + 1;
        
        return "{$prefix}-{$date}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Tạo đơn hàng mới
     */
    public static function createOrder($data)
    {
        if (!isset($data['order_code'])) {
            $data['order_code'] = self::generateOrderCode();
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return self::create($data);
    }

    /**
     * Lấy đơn hàng theo user
     */
    public static function getByUserId($userId, $limit = 20)
    {
        $sql = "SELECT * FROM orders WHERE user_id = ? 
                ORDER BY created_at DESC
                OFFSET 0 ROWS FETCH NEXT $limit ROWS ONLY";
        
        return Database::fetchAll($sql, [$userId]);
    }

    /**
     * Lấy đơn hàng theo mã đơn
     */
    public static function findByCode($orderCode)
    {
        $sql = "SELECT * FROM orders WHERE order_code = ?";
        return Database::fetch($sql, [$orderCode]);
    }

    /**
     * Lấy đơn hàng kèm chi tiết
     */
    public static function findWithItems($id)
    {
        $order = self::find($id);
        if ($order) {
            $order['items'] = OrderItem::getByOrderId($id);
        }
        return $order;
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public static function updateStatus($id, $status, $reason = null)
    {
        $data = [
            'order_status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($status === 'completed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        
        if ($status === 'cancelled' && $reason) {
            $data['cancelled_reason'] = $reason;
        }
        
        return self::update($id, $data);
    }

    /**
     * Lấy đơn hàng theo trạng thái
     */
    public static function getByStatus($status, $limit = 50)
    {
        $sql = "SELECT * FROM orders WHERE order_status = ? 
                ORDER BY created_at DESC
                OFFSET 0 ROWS FETCH NEXT $limit ROWS ONLY";
        
        return Database::fetchAll($sql, [$status]);
    }

    /**
     * Lấy thống kê đơn hàng
     */
    public static function getStatistics($startDate = null, $endDate = null)
    {
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
                WHERE $where";
        
        return Database::fetch($sql, $params);
    }
}

