<?php

namespace Rinnsan\RinnSanWeb\Middleware;

use Rinnsan\RinnSanWeb\Helpers\RequestHelper;

class RateLimitMiddleware extends Middleware
{
    private $maxRequests;
    private $windowSeconds;
    private $storagePath;

    public function __construct($maxRequests = 60, $windowSeconds = 60)
    {
        $this->maxRequests = $maxRequests;
        $this->windowSeconds = $windowSeconds;
        $this->storagePath = __DIR__ . '/../../storage/rate_limit/';
        
        // Tạo thư mục nếu chưa có
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    /**
     * Kiểm tra rate limit
     */
    public function handle($request)
    {
        $ip = RequestHelper::ip();
        $key = md5($ip . $_SERVER['REQUEST_URI']);
        $file = $this->storagePath . $key . '.json';
        
        $data = $this->getRateLimitData($file);
        $now = time();
        
        // Xóa data cũ nếu hết window
        if ($data && ($now - $data['start_time']) > $this->windowSeconds) {
            $data = null;
        }
        
        if (!$data) {
            // Tạo mới
            $data = [
                'count' => 1,
                'start_time' => $now
            ];
        } else {
            // Tăng count
            $data['count']++;
            
            // Kiểm tra vượt quá limit
            if ($data['count'] > $this->maxRequests) {
                $remaining = $this->windowSeconds - ($now - $data['start_time']);
                
                http_response_code(429);
                header('Content-Type: application/json');
                header("Retry-After: {$remaining}");
                echo json_encode([
                    'success' => false,
                    'message' => 'Quá nhiều requests. Vui lòng thử lại sau ' . $remaining . ' giây.',
                    'data' => [
                        'retry_after' => $remaining
                    ]
                ]);
                exit;
            }
        }
        
        // Lưu data
        file_put_contents($file, json_encode($data));
        
        return true;
    }

    /**
     * Lấy rate limit data
     */
    private function getRateLimitData($file)
    {
        if (!file_exists($file)) {
            return null;
        }
        
        $content = file_get_contents($file);
        return json_decode($content, true);
    }
}

