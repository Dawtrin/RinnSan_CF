<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = [
        'code', 'discount_type', 'discount_value', 'min_order_amount',
        'max_discount_amount', 'usage_limit', 'used_count', 
        'valid_from', 'valid_to', 'is_active'
    ];

    /**
     * Tìm coupon theo mã
     */
    public static function findByCode($code)
    {
        $sql = "SELECT * FROM coupons WHERE code = ? AND is_active = 1";
        return Database::fetch($sql, [$code]);
    }

    /**
     * Kiểm tra coupon có hợp lệ không
     */
    public static function validateCoupon($code, $orderAmount = 0)
    {
        $coupon = self::findByCode($code);
        
        if (!$coupon) {
            return ['valid' => false, 'message' => 'Mã giảm giá không tồn tại'];
        }
        
        // Kiểm tra thời gian hiệu lực
        $now = date('Y-m-d H:i:s');
        if ($coupon['valid_from'] && $now < $coupon['valid_from']) {
            return ['valid' => false, 'message' => 'Mã giảm giá chưa có hiệu lực'];
        }
        
        if ($coupon['valid_to'] && $now > $coupon['valid_to']) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn'];
        }
        
        // Kiểm tra số lần sử dụng
        if ($coupon['usage_limit'] && $coupon['used_count'] >= $coupon['usage_limit']) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng'];
        }
        
        // Kiểm tra đơn tối thiểu
        if ($coupon['min_order_amount'] && $orderAmount < $coupon['min_order_amount']) {
            return [
                'valid' => false, 
                'message' => 'Đơn hàng tối thiểu ' . number_format($coupon['min_order_amount']) . ' VNĐ'
            ];
        }
        
        return ['valid' => true, 'coupon' => $coupon];
    }

    /**
     * Tính giá trị giảm giá
     */
    public static function calculateDiscount($coupon, $orderAmount)
    {
        if ($coupon['discount_type'] === 'percentage') {
            $discount = $orderAmount * ($coupon['discount_value'] / 100);
        } else {
            $discount = $coupon['discount_value'];
        }
        
        // Áp dụng giảm tối đa nếu có
        if ($coupon['max_discount_amount'] && $discount > $coupon['max_discount_amount']) {
            $discount = $coupon['max_discount_amount'];
        }
        
        return min($discount, $orderAmount); // Không giảm quá tổng đơn hàng
    }

    /**
     * Tăng số lần sử dụng
     */
    public static function incrementUsage($id)
    {
        $sql = "UPDATE coupons SET used_count = used_count + 1 WHERE id = ?";
        return Database::query($sql, [$id]);
    }

    /**
     * Lấy tất cả coupon active
     */
    public static function getAllActive()
    {
        $now = date('Y-m-d H:i:s');
        $sql = "SELECT * FROM coupons 
                WHERE is_active = 1 
                AND (valid_from IS NULL OR valid_from <= ?)
                AND (valid_to IS NULL OR valid_to >= ?)
                ORDER BY created_at DESC";
        
        return Database::fetchAll($sql, [$now, $now]);
    }
}

