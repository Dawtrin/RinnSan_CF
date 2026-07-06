<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Coupon;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminCouponService extends Service
{
    /**
     * [FIX QUAN TRỌNG] Hàm lấy danh sách Coupon
     * Thiếu hàm này là nguyên nhân bạn không thấy danh sách hiển thị
     */
    public function getAll()
    {
        // Lấy tất cả, sắp xếp ID giảm dần để thấy cái mới nhất
        $sql = "SELECT * FROM coupons ORDER BY id DESC";
        return Database::fetchAll($sql);
    }

    public function create($data)
    {
        // Kiểm tra xem mã đã có trong database chưa (kể cả ẩn/hiện)
        $sql = "SELECT COUNT(*) as count FROM coupons WHERE code = ?";
        $existing = Database::fetch($sql, [$data['code']]);
        
        if ($existing['count'] > 0) {
            return null; // Báo lỗi nếu đã trùng -> Controller sẽ trả về 400
        }

        Coupon::create($data);
        return Coupon::find(Database::lastInsertId());
    }

    public function update($id, $data)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return null;
        }
        
        // Nếu người dùng đổi tên mã, phải check trùng lại
        if (isset($data['code']) && $data['code'] !== $coupon['code']) {
            $sql = "SELECT COUNT(*) as count FROM coupons WHERE code = ?";
            $existing = Database::fetch($sql, [$data['code']]);
            if ($existing['count'] > 0) return null;
        }

        Coupon::update($id, $data);
        return Coupon::find($id);
    }

    public function delete($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return null;
        }
        Coupon::delete($id);
        return true;
    }
}