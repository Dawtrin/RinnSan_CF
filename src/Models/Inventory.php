<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $fillable = [
        'name', 'sku', 'unit', 'current_quantity', 'min_quantity',
        'cost_price', 'supplier_id', 'is_active'
    ];

    /**
     * Lấy tất cả nguyên liệu active
     */
    public static function getAllActive()
    {
        $sql = "SELECT i.*, s.name as supplier_name 
                FROM inventory i
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                WHERE i.is_active = 1
                ORDER BY i.name ASC";
        
        return Database::fetchAll($sql);
    }

    /**
     * Kiểm tra số lượng tồn kho
     */
    public static function checkStock($id, $requiredQuantity)
    {
        $inventory = self::find($id);
        if (!$inventory) {
            return ['available' => false, 'message' => 'Nguyên liệu không tồn tại'];
        }
        
        if ($inventory['current_quantity'] < $requiredQuantity) {
            return [
                'available' => false,
                'message' => 'Không đủ số lượng. Hiện có: ' . $inventory['current_quantity'] . ' ' . $inventory['unit']
            ];
        }
        
        return ['available' => true];
    }

    /**
     * Cập nhật số lượng tồn kho
     */
    public static function updateQuantity($id, $quantity)
    {
        $sql = "UPDATE inventory SET current_quantity = ?, updated_at = GETDATE() WHERE id = ?";
        return Database::query($sql, [$quantity, $id]);
    }

    /**
     * Lấy nguyên liệu sắp hết (dưới min_quantity)
     */
    public static function getLowStock()
    {
        $sql = "SELECT * FROM inventory 
                WHERE is_active = 1 
                AND current_quantity <= min_quantity
                ORDER BY (current_quantity - min_quantity) ASC";
        
        return Database::fetchAll($sql);
    }
}

