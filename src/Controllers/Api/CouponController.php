<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Coupon;
use Rinnsan\RinnSanWeb\Services\CouponService;

class CouponController extends ApiController
{
    /**
     * Lấy danh sách coupon
     * GET /api/coupons
     */
    public function index()
    {
        try {
            $service = new CouponService();
            $coupons = $service->list();
            
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
            $service = new CouponService();
            $result = $service->validate($data['code'], $orderAmount);
            if (!$result['valid']) {
                return $this->error($result['message'], 400);
            }
            return $this->success($result, 'Coupon hợp lệ');
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
            $service = new CouponService();
            $coupon = $service->get($id);
            
            if (!$coupon) {
                return $this->error('Coupon không tồn tại', 404);
            }
            
            return $this->success($coupon, 'Lấy chi tiết coupon thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

