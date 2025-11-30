<?php

namespace Rinnsan\RinnSanWeb\Middleware;

use Rinnsan\RinnSanWeb\Models\User;

class AuthMiddleware extends Middleware
{
    /**
     * Kiểm tra user đã đăng nhập chưa
     */
    public function handle($request)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $userId = $_SESSION['user_id'] ?? null;
        
        if (!$userId) {
            // Kiểm tra token trong header (nếu dùng JWT sau này)
            $token = $this->getBearerToken();
            if ($token) {
                // Có thể verify JWT token ở đây
                // Tạm thời return false
            }
            
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Chưa đăng nhập. Vui lòng đăng nhập để tiếp tục.',
                'data' => []
            ]);
            exit;
        }
        
        // Lưu user vào request để dùng sau
        $request['user'] = User::find($userId);
        
        if (!$request['user'] || !$request['user']['is_active']) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Tài khoản đã bị khóa',
                'data' => []
            ]);
            exit;
        }
        
        return true;
    }
    
    /**
     * Lấy Bearer token từ header
     */
    private function getBearerToken()
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
}

