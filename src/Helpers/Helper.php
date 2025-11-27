<?php

namespace Rinnsan\RinnSanWeb\Helpers;

class Helper
{
    /**
     * Hàm lấy biến môi trường
     */
    public static function env($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Hàm redirect
     */
    public static function redirect($path)
    {
        header("Location: {$path}");
        exit;
    }

    /**
     * Hàm dd (dump and die)
     */
    public static function dd($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        exit;
    }

    /**
     * Hàm escape HTML
     */
    public static function escape($data)
    {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Hàm tạo URL
     */
    public static function url($path = '')
    {
        return rtrim($_ENV['APP_URL'] ?? 'http://localhost', '/') . '/' . ltrim($path, '/');
    }
}
