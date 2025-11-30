<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $fillable = [
        'user_id', 'address_line1', 'address_line2', 'city',
        'district', 'ward', 'is_default'
    ];

    /**
     * Lấy địa chỉ theo user_id
     */
    public static function getByUserId($userId)
    {
        $sql = "SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC";
        return Database::fetchAll($sql, [$userId]);
    }

    /**
     * Lấy địa chỉ mặc định của user
     */
    public static function getDefault($userId)
    {
        $sql = "SELECT * FROM user_addresses WHERE user_id = ? AND is_default = 1";
        return Database::fetch($sql, [$userId]);
    }

    /**
     * Đặt địa chỉ làm mặc định
     */
    public static function setDefault($id, $userId)
    {
        // Bỏ mặc định tất cả địa chỉ của user
        $sql = "UPDATE user_addresses SET is_default = 0 WHERE user_id = ?";
        Database::query($sql, [$userId]);
        
        // Đặt địa chỉ này làm mặc định
        return self::update($id, ['is_default' => 1]);
    }

    /**
     * Tạo địa chỉ mới
     */
    public static function createAddress($data)
    {
        // Nếu đây là địa chỉ đầu tiên hoặc được đặt làm mặc định
        if (!isset($data['is_default']) || $data['is_default'] == 1) {
            // Bỏ mặc định các địa chỉ khác
            if (isset($data['user_id'])) {
                $sql = "UPDATE user_addresses SET is_default = 0 WHERE user_id = ?";
                Database::query($sql, [$data['user_id']]);
            }
            $data['is_default'] = 1;
        }
        
        return self::create($data);
    }
}

