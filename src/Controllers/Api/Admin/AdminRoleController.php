<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\Role;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Services\AdminRoleService;

class AdminRoleController extends ApiController
{
    /**
     * Lấy danh sách roles
     * GET /api/admin/roles
     */
    public function index()
    {
        try {
            $service = new AdminRoleService();
            $roles = $service->list();
            return $this->success($roles, 'Lấy danh sách roles thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo role mới
     * POST /api/admin/roles
     */
    public function store()
    {
        try {
            $data = RequestHelper::inputSanitized();
            
            if (!isset($data['name'])) {
                return $this->error('Thiếu trường name', 400);
            }
            $service = new AdminRoleService();
            $role = $service->create($data);
            return $this->success($role, 'Tạo role thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật role
     * PUT /api/admin/roles/{id}
     */
    public function update($id)
    {
        try {
            $data = RequestHelper::inputSanitized();
            $service = new AdminRoleService();
            $role = $service->update($id, $data);
            if (!$role) {
                return $this->error('Role không tồn tại', 404);
            }
            return $this->success($role, 'Cập nhật role thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa role
     * DELETE /api/admin/roles/{id}
     */
    public function destroy($id)
    {
        try {
            $service = new AdminRoleService();
            $result = $service->delete($id);
            if ($result === null) {
                return $this->error('Role không tồn tại', 404);
            }
            if ($result === false) {
                return $this->error('Không thể xóa role đang được sử dụng', 400);
            }
            return $this->success([], 'Xóa role thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

