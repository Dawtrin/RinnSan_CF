<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\Supplier;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminSupplierController extends ApiController
{
    /**
     * Lấy danh sách suppliers
     * GET /api/admin/suppliers
     */
    public function index()
    {
        try {
            $pagination = RequestHelper::getPaginationParams();
            $filters = RequestHelper::getFilters(['is_active']);
            
            $conditions = [];
            if (isset($filters['is_active'])) {
                $conditions['is_active'] = $filters['is_active'];
            }
            
            $result = Supplier::paginate($pagination['page'], $pagination['per_page'], $conditions, 'name ASC');
            
            return $this->success($result['data'], 'Lấy danh sách suppliers thành công', 200, [
                'pagination' => $result['pagination']
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo supplier mới
     * POST /api/admin/suppliers
     */
    public function store()
    {
        try {
            $data = RequestHelper::inputSanitized();
            
            if (!isset($data['name'])) {
                return $this->error('Thiếu trường name', 400);
            }
            
            Supplier::create($data);
            $supplier = Supplier::find(Database::lastInsertId());
            
            return $this->success($supplier, 'Tạo supplier thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật supplier
     * PUT /api/admin/suppliers/{id}
     */
    public function update($id)
    {
        try {
            $supplier = Supplier::find($id);
            if (!$supplier) {
                return $this->error('Supplier không tồn tại', 404);
            }
            
            $data = RequestHelper::inputSanitized();
            Supplier::update($id, $data);
            $supplier = Supplier::find($id);
            
            return $this->success($supplier, 'Cập nhật supplier thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa supplier
     * DELETE /api/admin/suppliers/{id}
     */
    public function destroy($id)
    {
        try {
            $supplier = Supplier::find($id);
            if (!$supplier) {
                return $this->error('Supplier không tồn tại', 404);
            }
            
            Supplier::delete($id);
            
            return $this->success([], 'Xóa supplier thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

