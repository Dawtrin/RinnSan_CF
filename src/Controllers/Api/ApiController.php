<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

class ApiController
{
    /**
     * Trả về JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        http_response_code($statusCode);
        echo json_encode($data);
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
