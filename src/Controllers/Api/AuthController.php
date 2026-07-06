<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Core\Database;

class AuthController extends ApiController
{
    /**
     * Đăng nhập người dùng
     * POST /api/auth/login
     */
    public function login()
    {
        try {
            // 1. Đọc dữ liệu JSON từ React
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Fallback nếu không phải JSON
            if (!$input) {
                $input = $_POST;
            }

            // 2. Lấy thông tin đăng nhập
            // React có thể gửi 'email' hoặc 'username'
            $email = $input['email'] ?? $input['username'] ?? ''; 
            $password = $input['password'] ?? '';

            if (empty($email) || empty($password)) {
                return $this->error('Vui lòng nhập Email/Tên đăng nhập và Mật khẩu', 400);
            }

            // 3. Tìm user trong Database (Hỗ trợ đăng nhập bằng cả Email và Username)
            $user = Database::fetch(
                "SELECT * FROM users WHERE email = ? OR username = ?", 
                [$email, $email]
            );

            if (!$user) {
                return $this->error('Tài khoản không tồn tại', 404);
            }

            // 4. Kiểm tra mật khẩu (Hỗ trợ cả Hash và Plain text cũ)
            if (!password_verify($password, $user['password'])) {
                // Fallback: Kiểm tra plain text (nếu dữ liệu cũ chưa hash)
                if ($password !== $user['password']) {
                    return $this->error('Mật khẩu không chính xác', 401);
                }
            }

            // 5. Kiểm tra trạng thái hoạt động
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                return $this->error('Tài khoản đã bị khóa', 403);
            }

            // 6. Xử lý Session (Quan trọng cho Cart hoạt động)
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['user_email'] = $user['email'];

            // 7. Tạo Token đơn giản (Base64) để React lưu vào localStorage
            $tokenPayload = [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role_id'],
                'time' => time()
            ];
            $token = base64_encode(json_encode($tokenPayload));

            // Xóa password trước khi trả về
            unset($user['password']);

            return $this->success([
                'token' => $token,
                'user' => $user
            ], 'Đăng nhập thành công');

        } catch (\Exception $e) {
            return $this->error("Lỗi hệ thống: " . $e->getMessage(), 500);
        }
    }

    /**
     * Đăng ký người dùng mới
     * POST /api/auth/register
     */
    public function register()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) $input = $_POST;

            $username = $input['username'] ?? '';
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';
            $fullName = $input['full_name'] ?? $username; // Nếu không có full_name lấy tạm username

            // Validate cơ bản
            if (empty($username) || empty($email) || empty($password)) {
                return $this->error('Vui lòng điền đầy đủ thông tin (username, email, password)', 400);
            }

            // Kiểm tra trùng lặp
            $check = Database::fetch("SELECT id FROM users WHERE username = ? OR email = ?", [$username, $email]);
            if ($check) {
                return $this->error('Tên đăng nhập hoặc Email đã tồn tại', 409);
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert vào DB (Role mặc định là 3 - Khách hàng)
            // SQL Server dùng GETDATE(), MySQL dùng NOW()
            $sql = "INSERT INTO users (username, email, password, full_name, role_id, is_active, created_at) 
                    VALUES (?, ?, ?, ?, 3, 1, GETDATE())";
            
            Database::execute($sql, [$username, $email, $hashedPassword, $fullName]);

            return $this->success([], 'Đăng ký thành công', 201);

        } catch (\Exception $e) {
            return $this->error("Lỗi đăng ký: " . $e->getMessage(), 500);
        }
    }

    /**
     * Lấy thông tin user hiện tại (Me)
     * GET /api/auth/me
     */
    public function me()
    {
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $userId = $_SESSION['user_id'] ?? null;

            // Nếu không có session, thử check Header Token
            if (!$userId) {
                $headers = getallheaders();
                $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
                if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                    $data = json_decode(base64_decode($matches[1]), true);
                    $userId = $data['id'] ?? null;
                }
            }

            if (!$userId) {
                return $this->error('Chưa đăng nhập', 401);
            }

            $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
            
            if (!$user) {
                return $this->error('User không tồn tại', 404);
            }

            unset($user['password']);
            
            // Lấy địa chỉ (Nếu có bảng user_addresses)
            try {
                $addresses = Database::fetchAll("SELECT * FROM user_addresses WHERE user_id = ?", [$userId]);
                $user['addresses'] = $addresses;
            } catch (\Exception $ex) {
                $user['addresses'] = [];
            }

            return $this->success(['user' => $user], 'Lấy thông tin thành công');

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
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy(); // Hủy session PHP
        return $this->success([], 'Đăng xuất thành công');
    }
    
    // Placeholder để không lỗi route
    public function updateProfile() { return $this->success([]); }
}