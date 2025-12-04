<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\UserAddress;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Services\UserService;

class UserController extends ApiController
{
    /**
     * Lấy danh sách users (Admin only)
     * GET /api/users
     */
    public function index()
    {
        try {
            $page = (int)($_GET['page'] ?? 1);
            $perPage = (int)($_GET['per_page'] ?? 20);
            $roleId = $_GET['role_id'] ?? null;
            $service = new UserService();
            $result = $service->list($page, $perPage, $roleId);
            return $this->success($result, 'Lấy danh sách users thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy chi tiết user
     * GET /api/users/{id}
     */
    public function show($id)
    {
        try {
            $service = new UserService();
            $user = $service->get($id);
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
            return $this->success($user, 'Lấy chi tiết user thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật user
     * PUT /api/users/{id}
     */
    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                return $this->error('Dữ liệu không hợp lệ', 400);
            }
            $service = new UserService();
            $user = $service->update($id, $data);
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
            return $this->success($user, 'Cập nhật user thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

