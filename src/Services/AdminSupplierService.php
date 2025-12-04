<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Supplier;
use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminSupplierService extends Service
{
    public function paginate($page, $perPage, $filters, $sort)
    {
        $conditions = [];
        if (isset($filters['is_active'])) {
            $conditions['is_active'] = (int)$filters['is_active'];
        }
        $orderBy = $sort['sort'] . ' ' . $sort['order'];
        $result = Supplier::paginate($page, $perPage, $conditions, $orderBy);
        return $result;
    }

    public function create($data, $actorId = null)
    {
        Supplier::create($data);
        $supplierId = Database::lastInsertId();
        ActivityLog::log('admin.supplier.create', 'Admin tạo supplier: ' . ($data['name'] ?? ''), $actorId, 'supplier', $supplierId);
        return Supplier::find($supplierId);
    }

    public function update($id, $data, $actorId = null)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return null;
        }
        Supplier::update($id, $data);
        ActivityLog::log('admin.supplier.update', 'Admin cập nhật supplier: ' . ($supplier['name'] ?? '') . ' (ID: ' . $id . ')', $actorId, 'supplier', $id);
        return Supplier::find($id);
    }

    public function delete($id, $actorId = null)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return null;
        }
        Supplier::delete($id);
        ActivityLog::log('admin.supplier.delete', 'Admin xóa supplier: ' . ($supplier['name'] ?? '') . ' (ID: ' . $id . ')', $actorId, 'supplier', $id);
        return true;
    }
}
