<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\UserAddress;
use Rinnsan\RinnSanWeb\Core\Database;

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
            
            $conditions = [];
            if ($roleId) {
                $conditions['role_id'] = $roleId;
            }
            
            $result = User::paginate($page, $perPage, $conditions, 'created_at DESC');
            
            // Loại bỏ password
            foreach ($result['data'] as &$user) {
                unset($user['password']);
            }
            
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
            $user = User::findWithRole($id);
            
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
            
            // Lấy addresses
            $user['addresses'] = UserAddress::getByUserId($id);
            
            unset($user['password']);
            
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
            $user = User::find($id);
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                return $this->error('Dữ liệu không hợp lệ', 400);
            }
            
            // Không cho phép thay đổi một số fields
            unset($data['id'], $data['created_at']);
            
            User::update($id, $data);
            $user = User::findWithRole($id);
            unset($user['password']);
            
            return $this->success($user, 'Cập nhật user thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

