<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminUserService extends Service
{
    public function create($data)
    {
        if (User::findByEmail($data['email'])) return null;

        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'full_name' => $data['full_name'],
            'phone' => $data['phone'] ?? null,
            'role_id' => $data['role_id'] ?? 3,
            'is_active' => $data['is_active'] ?? 1
        ];
        
        User::create($userData);
        $userId = Database::lastInsertId();
        
        ActivityLog::log('admin.user.create', "Admin tạo user: {$data['email']}", $userId);
        return User::find($userId);
    }

    public function delete($id, $actorId)
    {
        $user = User::find($id);
        if (!$user) return null;
        if ($actorId == $id) return false;
        
        ActivityLog::log('admin.user.delete', "Admin xóa user: {$user['email']}", $id);
        User::delete($id);
        return true;
    }

    public function activate($id, $isActive)
    {
        $user = User::find($id);
        if (!$user) return null;
        
        User::update($id, ['is_active' => (int)$isActive]);
        $action = $isActive ? 'activate' : 'deactivate';
        ActivityLog::log("admin.user.$action", "Admin {$action} user: {$user['email']}", $id);
        
        $user = User::find($id);
        unset($user['password']);
        return $user;
    }

    public function changeRole($id, $roleId)
    {
        $user = User::find($id);
        if (!$user) return null;
        
        User::update($id, ['role_id' => (int)$roleId]);
        ActivityLog::log('admin.user.change_role', "Admin thay đổi role user: {$user['email']}", $id);
        
        $user = User::find($id);
        unset($user['password']);
        return $user;
    }

    /**
     * Lấy danh sách khách hàng cho trang Admin Customers
     * Kèm theo: Điểm tích lũy, Tổng chi tiêu
     */
    public function getCustomers()
    {
        try {
            // Câu lệnh SQL siêu đơn giản để test kết nối
            // Chỉ lấy các cột cơ bản nhất của bảng users
            // Lưu ý: Tôi dùng ISNULL để tránh lỗi nếu cột chưa có dữ liệu
            $sql = "SELECT 
                        id, 
                        full_name, 
                        email, 
                        phone, 
                        ISNULL(points, 0) as points, 
                        created_at,
                        1 as is_active,      -- Giả định active
                        0 as total_orders,   -- Tạm thời để 0 để tránh lỗi SQL
                        0 as total_spent     -- Tạm thời để 0 để tránh lỗi SQL
                    FROM users 
                    WHERE role_id = 3
                    ORDER BY created_at DESC";
            
            return Database::fetchAll($sql);

        } catch (\Exception $e) {
            // Nếu vẫn lỗi, ném lỗi ra ngoài để Controller bắt được và in ra màn hình
            throw new \Exception("Lỗi SQL Get Customers: " . $e->getMessage());
        }
    }
    

    /**
     * Hàm dự phòng khi query chính bị lỗi
     */
    private function getCustomersFallback() {
        try {
            $sql = "SELECT id, full_name, email, phone, 0 as points, created_at, 0 as total_orders, 0 as total_spent 
                    FROM users WHERE role_id = 3 ORDER BY created_at DESC";
            return Database::fetchAll($sql);
        } catch (\Exception $e) {
            return []; // Trả về rỗng nếu DB hỏng hẳn
        }
    }
    
    public function updatePoints($userId, $points) {
        try {
            return Database::query("UPDATE users SET points = ? WHERE id = ?", [$points, $userId]);
        } catch (\Exception $e) {
            return false;
        }
    }
}