<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Coupon;

class CouponController extends ApiController
{
    /**
     * Lấy danh sách coupon
     * GET /api/coupons
     */
    public function index()
    {
        try {
            $coupons = Coupon::getAllActive();
            
            return $this->success($coupons, 'Lấy danh sách coupon thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Validate coupon
     * POST /api/coupons/validate
     */
    public function validate()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['code'])) {
                return $this->error('Thiếu mã coupon', 400);
            }
            
            $orderAmount = $data['order_amount'] ?? 0;
            $validation = Coupon::validateCoupon($data['code'], $orderAmount);
            
            if (!$validation['valid']) {
                return $this->error($validation['message'], 400);
            }
            
            $discount = Coupon::calculateDiscount($validation['coupon'], $orderAmount);
            
            return $this->success([
                'coupon' => $validation['coupon'],
                'discount_amount' => $discount
            ], 'Coupon hợp lệ');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy chi tiết coupon
     * GET /api/coupons/{id}
     */
    public function show($id)
    {
        try {
            $coupon = Coupon::find($id);
            
            if (!$coupon) {
                return $this->error('Coupon không tồn tại', 404);
            }
            
            return $this->success($coupon, 'Lấy chi tiết coupon thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

