<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = [
        'name', 'contact_person', 'phone', 'email', 'address', 'is_active'
    ];

    /**
     * Lấy tất cả nhà cung cấp active
     */
    public static function getAllActive()
    {
        $sql = "SELECT * FROM suppliers WHERE is_active = 1 ORDER BY name ASC";
        return Database::fetchAll($sql);
    }
}

