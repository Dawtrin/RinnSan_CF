<?php

namespace Rinnsan\RinnSanWeb\Middleware;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Services\AuthService;

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
            $token = $this->getBearerToken();
            if ($token) {
                $auth = new AuthService();
                $payload = $auth->verifyToken($token);
                if ($payload && isset($payload['sub'])) {
                    $userId = $payload['sub'];
                }
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

