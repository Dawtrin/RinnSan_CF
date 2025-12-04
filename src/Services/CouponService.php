<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Coupon;

class CouponService extends Service
{
    public function list()
    {
        return Coupon::getAllActive();
    }

    public function validate($code, $orderAmount = 0)
    {
        $validation = Coupon::validateCoupon($code, $orderAmount);
        if (!$validation['valid']) {
            return ['valid' => false, 'message' => $validation['message']];
        }
        $discount = Coupon::calculateDiscount($validation['coupon'], $orderAmount);
        return ['valid' => true, 'coupon' => $validation['coupon'], 'discount_amount' => $discount];
    }

    public function get($id)
    {
        return Coupon::find($id);
    }
}

