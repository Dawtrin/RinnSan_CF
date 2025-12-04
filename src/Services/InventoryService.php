<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Inventory;
use Rinnsan\RinnSanWeb\Models\InventoryTransaction;
use Rinnsan\RinnSanWeb\Core\Database;

class InventoryService extends Service
{
    public function list()
    {
        return Inventory::getAllActive();
    }

    public function get($id)
    {
        $inventory = Inventory::find($id);
        if (!$inventory) {
            return null;
        }
        $inventory['transactions'] = InventoryTransaction::getByInventoryId($id, 20);
        return $inventory;
    }

    public function createTransaction($data, $userId)
    {
        $data['created_by'] = $userId;
        $transaction = InventoryTransaction::createTransaction($data);
        return [
            'transaction_id' => Database::lastInsertId()
        ];
    }

    public function lowStock()
    {
        return Inventory::getLowStock();
    }

    public function create($data)
    {
        Inventory::create($data);
        return Inventory::find(Database::lastInsertId());
    }

    public function update($id, $data)
    {
        Inventory::update($id, $data);
        return Inventory::find($id);
    }

    public function delete($id)
    {
        Inventory::delete($id);
        return true;
    }
}

