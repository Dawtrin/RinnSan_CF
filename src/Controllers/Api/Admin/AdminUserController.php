<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\UserAddress;
use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Services\AdminUserService;

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
            
            $service = new AdminUserService();
            $user = $service->create($data);
            if (!$user) {
                return $this->error('Email đã được sử dụng', 400);
            }
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
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $service = new AdminUserService();
            $result = $service->delete($id, $_SESSION['user_id'] ?? 0);
            if ($result === null) {
                return $this->error('User không tồn tại', 404);
            }
            if ($result === false) {
                return $this->error('Không thể xóa chính mình', 400);
            }
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
            $data = RequestHelper::input();
            $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;
            $service = new AdminUserService();
            $user = $service->activate($id, $isActive);
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
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
            $data = RequestHelper::input();
            if (!isset($data['role_id'])) {
                return $this->error('Thiếu role_id', 400);
            }
            $service = new AdminUserService();
            $user = $service->changeRole($id, (int)$data['role_id']);
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
            return $this->success($user, 'Thay đổi role thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

