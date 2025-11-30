<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\Coupon;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminCouponController extends ApiController
{
    /**
     * Tạo coupon mới
     * POST /api/admin/coupons
     */
    public function store()
    {
        try {
            $data = RequestHelper::inputSanitized();
            
            $required = ['code', 'discount_type', 'discount_value'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    return $this->error("Thiếu trường bắt buộc: $field", 400);
                }
            }
            
            // Kiểm tra code đã tồn tại
            $existing = Coupon::findByCode($data['code']);
            if ($existing) {
                return $this->error('Mã coupon đã tồn tại', 400);
            }
            
            Coupon::create($data);
            $coupon = Coupon::find(Database::lastInsertId());
            
            return $this->success($coupon, 'Tạo coupon thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật coupon
     * PUT /api/admin/coupons/{id}
     */
    public function update($id)
    {
        try {
            $coupon = Coupon::find($id);
            if (!$coupon) {
                return $this->error('Coupon không tồn tại', 404);
            }
            
            $data = RequestHelper::inputSanitized();
            
            Coupon::update($id, $data);
            $coupon = Coupon::find($id);
            
            return $this->success($coupon, 'Cập nhật coupon thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa coupon
     * DELETE /api/admin/coupons/{id}
     */
    public function destroy($id)
    {
        try {
            $coupon = Coupon::find($id);
            if (!$coupon) {
                return $this->error('Coupon không tồn tại', 404);
            }
            
            Coupon::delete($id);
            
            return $this->success([], 'Xóa coupon thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

