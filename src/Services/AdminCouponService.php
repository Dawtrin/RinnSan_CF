<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Coupon;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminCouponService extends Service
{
    public function create($data)
    {
        $existing = Coupon::findByCode($data['code']);
        if ($existing) {
            return null;
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

