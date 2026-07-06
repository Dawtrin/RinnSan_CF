<?php

namespace Rinnsan\RinnSanWeb\Helpers;

class ResponseHelper
{
    /**
     * Trả về JSON response
     */
    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Trả về success response chuẩn
     */
    public static function success($data = [], $message = 'Success', $statusCode = 200, $meta = [])
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
        
        if (!empty($meta)) {
            $response['meta'] = $meta;
        }
        
        return self::json($response, $statusCode);
    }

    /**
     * Trả về error response chuẩn
     */
    public static function error($message = 'Error', $statusCode = 400, $errors = [])
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        return self::json($response, $statusCode);
    }

    /**
     * Trả về paginated response
     */
    public static function paginated($data, $pagination, $message = 'Success')
    {
        return self::success($data, $message, 200, [
            'pagination' => $pagination
        ]);
    }

    /**
     * Redirect
     */
    public static function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }

    /**
     * Set CORS headers
     */
    public static function cors($allowedOrigins = null)
    {
        // List các domain frontend được phép
        if ($allowedOrigins === null) {
            $allowedOrigins = [
                'http://localhost:5173',
                'http://localhost:5174',
                'http://localhost:5175',
                'http://localhost:5176'
            ];
        }
        
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        
        // Logic check chính xác origin thay vì dùng *
        if (in_array($origin, $allowedOrigins)) {
            header("Access-Control-Allow-Origin: {$origin}");
            header('Access-Control-Allow-Credentials: true');
        }
        
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin');
        header('Access-Control-Max-Age: 86400');
        
        // Handle preflight request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }
}

