<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\Supplier;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Services\AdminSupplierService;

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
            $sort = RequestHelper::getSortParams('name', 'ASC');
            $service = new AdminSupplierService();
            $result = $service->paginate($pagination['page'], $pagination['per_page'], $filters, $sort);
            
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
            $service = new AdminSupplierService();
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $supplier = $service->create($data, $_SESSION['user_id'] ?? null);
            
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
            $data = RequestHelper::inputSanitized();
            $service = new AdminSupplierService();
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $supplier = $service->update($id, $data, $_SESSION['user_id'] ?? null);
            if (!$supplier) {
                return $this->error('Supplier không tồn tại', 404);
            }
            
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
            $service = new AdminSupplierService();
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $deleted = $service->delete($id, $_SESSION['user_id'] ?? null);
            if ($deleted === null) {
                return $this->error('Supplier không tồn tại', 404);
            }
            return $this->success([], 'Xóa supplier thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

