<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\UserAddress;
use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Services\AuthService;

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
            
            $auth = new AuthService();
            $result = $auth->login($data['email'], $data['password']);
            if (!$result) {
                return $this->error('Email hoặc mật khẩu không đúng', 401);
            }
            if (isset($result['requires_2fa']) && $result['requires_2fa']) {
                ActivityLog::log('user.login.2fa', 'Yêu cầu xác minh OTP', $result['user_id']);
                return $this->success($result, 'Yêu cầu xác minh OTP');
            }
            if (isset($result['user']['id'])) {
                User::update($result['user']['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
                ActivityLog::log('user.login', "User {$result['user']['email']} đăng nhập", $result['user']['id']);
            }
            return $this->success($result, 'Đăng nhập thành công');
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
            
            return $this->success(['user' => $user], 'Lấy thông tin user thành công');
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
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $m)) {
            $auth = new AuthService();
            $payload = $auth->verifyToken($m[1]);
            if ($payload && isset($payload['sub'])) {
                return $payload['sub'];
            }
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user_id'] ?? null;
    }
}

