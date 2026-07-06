<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminUserController extends ApiController
{
    // 1. LẤY DANH SÁCH (FULL)
    public function index()
    {
        try {
            $sql = "SELECT id, username, full_name, email, phone, role_id, is_active, created_at 
                    FROM users WHERE role_id IN (1, 2) ORDER BY id DESC";
            return $this->success(Database::fetchAll($sql));
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    // 2. THÊM NHÂN VIÊN MỚI
    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['username']) || empty($data['password'])) {
                return $this->error("Thiếu tên đăng nhập hoặc mật khẩu", 400);
            }

            // Check trùng
            $check = Database::fetch("SELECT id FROM users WHERE username = ?", [$data['username']]);
            if ($check) return $this->error('Tên đăng nhập đã tồn tại', 400);

            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (username, password, full_name, email, phone, role_id, is_active, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, 1, GETDATE())";
            
            Database::query($sql, [
                $data['username'],
                $passwordHash,
                $data['full_name'] ?? '',
                $data['email'] ?? '',
                $data['phone'] ?? '',
                $data['role_id'] ?? 2
            ]);

            return $this->success([], 'Tạo tài khoản thành công', 201);
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    // 3. CẬP NHẬT THÔNG TIN
    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "UPDATE users SET full_name = ?, email = ?, phone = ?, role_id = ? WHERE id = ?";
            Database::query($sql, [
                $data['full_name'], $data['email'], $data['phone'], $data['role_id'], $id
            ]);

            return $this->success([], 'Cập nhật thành công');
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    // 4. ĐỔI MẬT KHẨU
    public function resetPassword($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['password'])) return $this->error('Thiếu mật khẩu mới', 400);

            $newPass = password_hash($data['password'], PASSWORD_BCRYPT);
            Database::query("UPDATE users SET password = ? WHERE id = ?", [$newPass, $id]);

            return $this->success([], 'Đổi mật khẩu thành công');
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    // 5. XÓA
    public function destroy($id)
    {
        try {
            Database::query("DELETE FROM users WHERE id = ?", [$id]);
            return $this->success([], 'Đã xóa tài khoản');
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }
}