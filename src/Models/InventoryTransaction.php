<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class InventoryTransaction extends Model
{
    protected $table = 'inventory_transactions';
    protected $fillable = [
        'inventory_id', 'type', 'quantity', 'note', 
        'reference_type', 'reference_id', 'created_by'
    ];

    /**
     * Tạo transaction và cập nhật inventory
     */
    public static function createTransaction($data)
    {
        // Tạo transaction
        $transaction = self::create($data);
        $transactionId = Database::lastInsertId();
        
        // Cập nhật inventory quantity
        $inventory = Inventory::find($data['inventory_id']);
        if ($inventory) {
            $newQuantity = $inventory['current_quantity'];
            
            if ($data['type'] === 'in') {
                $newQuantity += $data['quantity'];
            } elseif ($data['type'] === 'out') {
                $newQuantity -= $data['quantity'];
            } elseif ($data['type'] === 'adjust') {
                $newQuantity = $data['quantity'];
            }
            
            Inventory::updateQuantity($data['inventory_id'], $newQuantity);
        }
        
        return $transaction;
    }

    /**
     * Lấy transactions theo inventory_id
     */
    public static function getByInventoryId($inventoryId, $limit = 50)
    {
        $sql = "SELECT it.*, u.full_name as created_by_name 
                FROM inventory_transactions it
                LEFT JOIN users u ON it.created_by = u.id
                WHERE it.inventory_id = ?
                ORDER BY it.created_at DESC
                OFFSET 0 ROWS FETCH NEXT $limit ROWS ONLY";
        
        return Database::fetchAll($sql, [$inventoryId]);
    }

    /**
     * Lấy transactions theo type
     */
    public static function getByType($type, $limit = 50)
    {
        $sql = "SELECT it.*, i.name as inventory_name, u.full_name as created_by_name 
                FROM inventory_transactions it
                LEFT JOIN inventory i ON it.inventory_id = i.id
                LEFT JOIN users u ON it.created_by = u.id
                WHERE it.type = ?
                ORDER BY it.created_at DESC
                OFFSET 0 ROWS FETCH NEXT $limit ROWS ONLY";
        
        return Database::fetchAll($sql, [$type]);
    }
}

