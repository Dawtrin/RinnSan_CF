<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\UserAddress;
use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Core\Database;

class AuthController extends ApiController
{
    /**
     * Đăng nhập
     * POST /api/auth/login
     */
    public function login()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['email']) || !isset($data['password'])) {
                return $this->error('Thiếu email hoặc password', 400);
            }
            
            $user = User::findByEmail($data['email']);
            
            if (!$user) {
                return $this->error('Email hoặc mật khẩu không đúng', 401);
            }
            
            if (!password_verify($data['password'], $user['password'])) {
                return $this->error('Email hoặc mật khẩu không đúng', 401);
            }
            
            if (!$user['is_active']) {
                return $this->error('Tài khoản đã bị khóa', 403);
            }
            
            // Cập nhật last_login_at
            User::update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
            
            // Ghi log
            ActivityLog::log('user.login', "User {$user['email']} đăng nhập", $user['id']);
            
            // Loại bỏ password khỏi response
            unset($user['password']);
            
            return $this->success([
                'user' => $user,
                'token' => $this->generateToken($user['id']) // Có thể implement JWT sau
            ], 'Đăng nhập thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Đăng ký
     * POST /api/auth/register
     */
    public function register()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $required = ['username', 'email', 'password', 'full_name'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    return $this->error("Thiếu trường bắt buộc: $field", 400);
                }
            }
            
            // Kiểm tra email đã tồn tại
            $existingUser = User::findByEmail($data['email']);
            if ($existingUser) {
                return $this->error('Email đã được sử dụng', 400);
            }
            
            // Tạo user
            $userData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'], // Model sẽ hash
                'full_name' => $data['full_name'],
                'phone' => $data['phone'] ?? null,
                'role_id' => 3 // Customer role
            ];
            
            User::create($userData);
            $userId = Database::lastInsertId();
            
            // Ghi log
            ActivityLog::log('user.register', "User mới đăng ký: {$data['email']}", $userId);
            
            $user = User::find($userId);
            unset($user['password']);
            
            return $this->success(['user' => $user], 'Đăng ký thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy thông tin user hiện tại
     * GET /api/auth/me
     */
    public function me()
    {
        try {
            $userId = $this->getCurrentUserId();
            
            if (!$userId) {
                return $this->error('Chưa đăng nhập', 401);
            }
            
            $user = User::find($userId);
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }
            
            // Lấy địa chỉ
            $user['addresses'] = UserAddress::getByUserId($userId);
            
            unset($user['password']);
            
            return $this->success(['user' => $user], 'Lấy thông tin user thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật thông tin user
     * PUT /api/auth/profile
     */
    public function updateProfile()
    {
        try {
            $userId = $this->getCurrentUserId();
            
            if (!$userId) {
                return $this->error('Chưa đăng nhập', 401);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                return $this->error('Dữ liệu không hợp lệ', 400);
            }
            
            // Không cho phép thay đổi password ở đây
            unset($data['password']);
            unset($data['role_id']);
            
            User::update($userId, $data);
            $user = User::find($userId);
            unset($user['password']);
            
            return $this->success(['user' => $user], 'Cập nhật thông tin thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Đăng xuất
     * POST /api/auth/logout
     */
    public function logout()
    {
        try {
            $userId = $this->getCurrentUserId();
            if ($userId) {
                ActivityLog::log('user.logout', "User đăng xuất", $userId);
            }
            
            return $this->success([], 'Đăng xuất thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy user ID từ session hoặc token
     */
    private function getCurrentUserId()
    {
        // Có thể implement JWT token hoặc session
        // Tạm thời dùng session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Generate token (có thể implement JWT sau)
     */
    private function generateToken($userId)
    {
        // Tạm thời trả về session ID
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = $userId;
        return session_id();
    }
}

