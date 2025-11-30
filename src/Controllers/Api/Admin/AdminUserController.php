<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\UserAddress;
use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminUserController extends ApiController
{
    /**
     * Tạo user mới (Admin)
     * POST /api/admin/users
     */
    public function store()
    {
        try {
            $data = RequestHelper::inputSanitized();
            
            $required = ['username', 'email', 'password', 'full_name'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    return $this->error("Thiếu trường bắt buộc: $field", 400);
                }
            }
            
            // Kiểm tra email đã tồn tại
            $existing = User::findByEmail($data['email']);
            if ($existing) {
                return $this->error('Email đã được sử dụng', 400);
            }
            
            // Tạo user
            $userData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'full_name' => $data['full_name'],
                'phone' => $data['phone'] ?? null,
                'role_id' => $data['role_id'] ?? 3, // Default customer
                'is_active' => $data['is_active'] ?? 1
            ];
            
            User::create($userData);
            $userId = Database::lastInsertId();
            
            // Log
            ActivityLog::log('admin.user.create', "Admin tạo user: {$data['email']}", $userId);
            
            $user = User::find($userId);
            unset($user['password']);
            
            return $this->success($user, 'Tạo user thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa user (Admin)
     * DELETE /api/admin/users/{id}
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
            
            // Không cho xóa chính mình
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if ($_SESSION['user_id'] == $id) {
                return $this->error('Không thể xóa chính mình', 400);
            }
            
            // Log
            ActivityLog::log('admin.user.delete', "Admin xóa user: {$user['email']}", $id);
            
            User::delete($id);
            
            return $this->success([], 'Xóa user thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Kích hoạt/khóa user
     * PUT /api/admin/users/{id}/activate
     */
    public function activate($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
            
            $data = RequestHelper::input();
            $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;
            
            User::update($id, ['is_active' => $isActive]);
            
            // Log
            $action = $isActive ? 'activate' : 'deactivate';
            ActivityLog::log("admin.user.$action", "Admin {$action} user: {$user['email']}", $id);
            
            $user = User::find($id);
            unset($user['password']);
            
            return $this->success($user, $isActive ? 'Kích hoạt user thành công' : 'Khóa user thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Thay đổi role
     * PUT /api/admin/users/{id}/role
     */
    public function changeRole($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
            
            $data = RequestHelper::input();
            if (!isset($data['role_id'])) {
                return $this->error('Thiếu role_id', 400);
            }
            
            User::update($id, ['role_id' => (int)$data['role_id']]);
            
            // Log
            ActivityLog::log('admin.user.change_role', "Admin thay đổi role user: {$user['email']}", $id);
            
            $user = User::find($id);
            unset($user['password']);
            
            return $this->success($user, 'Thay đổi role thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

