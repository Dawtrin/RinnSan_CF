<?php

namespace Rinnsan\RinnSanWeb\Middleware;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\Role;

class AdminMiddleware extends Middleware
{
    /**
     * Kiểm tra user có quyền admin không
     */
    public function handle($request)
    {
        // Chạy AuthMiddleware trước
        $auth = new AuthMiddleware();
        if (!$auth->handle($request)) {
            return false;
        }
        
        $user = $request['user'] ?? null;
        
        if (!$user) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Chưa đăng nhập',
                'data' => []
            ]);
            exit;
        }
        
        // Kiểm tra role
        $role = Role::find($user['role_id']);
        
        // Admin role_id thường là 1, Staff là 2, Customer là 3
        if (!$role || !in_array(strtolower($role['name']), ['admin', 'staff'])) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Không có quyền truy cập. Chỉ admin và staff mới được phép.',
                'data' => []
            ]);
            exit;
        }
        
        return true;
    }
}

