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
     * Tìm coupon active (Dùng cho Khách hàng mua sắm)
     */
    public static function findByCode($code)
    {
        $sql = "SELECT * FROM coupons WHERE code = ? AND is_active = 1";
        return Database::fetch($sql, [$code]);
    }

    /**
     * [MỚI] Kiểm tra mã tồn tại (Dùng cho Admin khi tạo mới)
     * Kiểm tra tất cả, kể cả mã đang ẩn hoặc hết hạn
     */
    public static function checkCodeExists($code)
    {
        $sql = "SELECT COUNT(*) as count FROM coupons WHERE code = ?";
        $result = Database::fetch($sql, [$code]);
        return ($result['count'] > 0);
    }

    /**
     * [MỚI] Lấy tất cả coupon (Dùng cho Admin)
     */
    public static function getAll()
    {
        // Lấy tất cả, sắp xếp mới nhất lên đầu
        $sql = "SELECT * FROM coupons ORDER BY id DESC";
        return Database::fetchAll($sql);
    }

    // --- CÁC HÀM CŨ GIỮ NGUYÊN (Validate, Calculate...) ---
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
        
        if ($coupon['usage_limit'] > 0 && $coupon['used_count'] >= $coupon['usage_limit']) {
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
        
        return min($discount, $orderAmount);
    }

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

