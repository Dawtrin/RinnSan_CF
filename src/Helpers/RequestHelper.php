<?php

namespace Rinnsan\RinnSanWeb\Helpers;

class RequestHelper
{
    /**
     * Lấy input từ request (POST, GET, JSON)
     */
    public static function input($key = null, $default = null)
    {
        // Lấy từ JSON body trước
        $jsonData = json_decode(file_get_contents('php://input'), true);
        if ($jsonData !== null) {
            if ($key === null) {
                return $jsonData;
            }
            return $jsonData[$key] ?? $default;
        }
        
        // Lấy từ POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($key === null) {
                return $_POST;
            }
            return $_POST[$key] ?? $default;
        }
        
        // Lấy từ GET
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Lấy input và sanitize
     */
    public static function inputSanitized($key = null, $default = null)
    {
        $value = self::input($key, $default);
        
        if (is_array($value)) {
            return array_map([self::class, 'sanitize'], $value);
        }
        
        return self::sanitize($value);
    }

    /**
     * Sanitize string
     */
    public static function sanitize($value)
    {
        if (!is_string($value)) {
            return $value;
        }
        
        // Remove HTML tags
        $value = strip_tags($value);
        
        // Escape special characters
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        
        // Trim whitespace
        $value = trim($value);
        
        return $value;
    }

    /**
     * Lấy file upload
     */
    public static function file($key)
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Kiểm tra request method
     */
    public static function isMethod($method)
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === strtoupper($method);
    }

    /**
     * Kiểm tra AJAX request
     */
    public static function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Lấy IP address
     */
    public static function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
    }

    /**
     * Lấy User Agent
     */
    public static function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Lấy query parameters với pagination
     */
    public static function getPaginationParams()
    {
        return [
            'page' => max(1, (int)($_GET['page'] ?? 1)),
            'per_page' => min(100, max(1, (int)($_GET['per_page'] ?? 20))),
        ];
    }

    /**
     * Lấy filter parameters
     */
    public static function getFilters($allowedFilters = [])
    {
        $filters = [];
        
        foreach ($allowedFilters as $filter) {
            if (isset($_GET[$filter])) {
                $filters[$filter] = self::sanitize($_GET[$filter]);
            }
        }
        
        return $filters;
    }

    /**
     * Lấy sort parameters
     */
    public static function getSortParams($defaultSort = 'id', $defaultOrder = 'DESC')
    {
        $sort = self::sanitize($_GET['sort'] ?? $defaultSort);
        $order = strtoupper(self::sanitize($_GET['order'] ?? $defaultOrder));
        
        // Validate order
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = $defaultOrder;
        }
        
        return [
            'sort' => $sort,
            'order' => $order
        ];
    }
}

