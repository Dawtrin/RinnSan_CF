<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\Role;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminRoleController extends ApiController
{
    /**
     * Lấy danh sách roles
     * GET /api/admin/roles
     */
    public function index()
    {
        try {
            $roles = Role::all();
            
            // Parse permissions
            foreach ($roles as &$role) {
                $role = Role::parsePermissions($role);
            }
            
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
            
            // Convert permissions array to JSON
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $data['permissions'] = json_encode($data['permissions']);
            }
            
            Role::create($data);
            $role = Role::find(Database::lastInsertId());
            $role = Role::parsePermissions($role);
            
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
            $role = Role::find($id);
            if (!$role) {
                return $this->error('Role không tồn tại', 404);
            }
            
            $data = RequestHelper::inputSanitized();
            
            // Convert permissions array to JSON
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $data['permissions'] = json_encode($data['permissions']);
            }
            
            Role::update($id, $data);
            $role = Role::find($id);
            $role = Role::parsePermissions($role);
            
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
            $role = Role::find($id);
            if (!$role) {
                return $this->error('Role không tồn tại', 404);
            }
            
            // Không cho xóa role đang được sử dụng
            $users = \Rinnsan\RinnSanWeb\Models\User::where('role_id', '=', $id);
            if (!empty($users)) {
                return $this->error('Không thể xóa role đang được sử dụng', 400);
            }
            
            Role::delete($id);
            
            return $this->success([], 'Xóa role thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

