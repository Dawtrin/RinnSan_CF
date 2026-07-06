<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Inventory;
use Rinnsan\RinnSanWeb\Models\InventoryTransaction;
use Rinnsan\RinnSanWeb\Core\Database;

class InventoryController extends ApiController
{
    /**
     * Lấy danh sách inventory
     * GET /api/inventory
     */
    public function index()
    {
        try {
            $inventory = Inventory::getAllActive();
            return $this->success($inventory, 'Lấy danh sách kho thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy chi tiết inventory
     * GET /api/inventory/{id}
     */
    public function show($id)
    {
        try {
            $inventory = Inventory::find($id);
            
            if (!$inventory) {
                return $this->error('Nguyên liệu không tồn tại', 404);
            }
            
            // Lấy transactions
            $inventory['transactions'] = InventoryTransaction::getByInventoryId($id, 20);
            
            return $this->success($inventory, 'Lấy chi tiết kho thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo inventory transaction
     * POST /api/inventory/transactions
     */
    public function createTransaction()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['inventory_id']) || !isset($data['type']) || !isset($data['quantity'])) {
                return $this->error('Thiếu inventory_id, type hoặc quantity', 400);
            }
            
            // Lấy user từ session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $userId = $_SESSION['user_id'] ?? 1; // Default to 1 if not logged in
            
            $data['created_by'] = $userId;
            
            $transaction = InventoryTransaction::createTransaction($data);
            
            return $this->success([
                'transaction_id' => Database::lastInsertId(),
                'message' => 'Tạo transaction thành công'
            ], 'Tạo transaction thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy low stock items
     * GET /api/inventory/low-stock
     */
    public function lowStock()
    {
        try {
            $items = Inventory::getLowStock();
            return $this->success($items, 'Lấy danh sách sắp hết hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo inventory item (Admin)
     * POST /api/admin/inventory
     */
    public function store()
    {
        try {
            $data = \Rinnsan\RinnSanWeb\Helpers\RequestHelper::inputSanitized();
            
            if (!isset($data['name'])) {
                return $this->error('Thiếu trường name', 400);
            }
            
            Inventory::create($data);
            $inventory = Inventory::find(Database::lastInsertId());
            
            return $this->success($inventory, 'Tạo inventory thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật inventory (Admin)
     * PUT /api/admin/inventory/{id}
     */
    public function update($id)
    {
        try {
            $inventory = Inventory::find($id);
            if (!$inventory) {
                return $this->error('Inventory không tồn tại', 404);
            }
            
            $data = \Rinnsan\RinnSanWeb\Helpers\RequestHelper::inputSanitized();
            Inventory::update($id, $data);
            $inventory = Inventory::find($id);
            
            return $this->success($inventory, 'Cập nhật inventory thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa inventory (Admin)
     * DELETE /api/admin/inventory/{id}
     */
    public function destroy($id)
    {
        try {
            $inventory = Inventory::find($id);
            if (!$inventory) {
                return $this->error('Inventory không tồn tại', 404);
            }
            
            Inventory::delete($id);
            
            return $this->success([], 'Xóa inventory thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

