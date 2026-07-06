<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

class ApiController
{
    /**
     * Trả về JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        // KHÔNG GỬI HEADERS Ở ĐÂY
        // Headers đã được gửi trong index.php rồi
        
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Trả về success response
     */
    protected function success($data = [], $message = 'Success', $statusCode = 200)
    {
        return $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Trả về error response
     */
    protected function error($message = 'Error', $statusCode = 400, $data = [])
    {
        return $this->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}