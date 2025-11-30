<?php

namespace Rinnsan\RinnSanWeb\Helpers;

class FileHelper
{
    /**
     * Lưu file upload
     */
    public static function store($file, $path = 'uploads', $name = null)
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }
        
        $uploadPath = __DIR__ . '/../../public/' . trim($path, '/') . '/';
        
        // Tạo thư mục nếu chưa có
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Tạo tên file
        if (!$name) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $name = uniqid() . '_' . time() . '.' . $extension;
        }
        
        $fullPath = $uploadPath . $name;
        
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return [
                'path' => '/' . trim($path, '/') . '/' . $name,
                'name' => $name,
                'size' => $file['size'],
                'type' => $file['type']
            ];
        }
        
        return false;
    }

    /**
     * Xóa file
     */
    public static function delete($path)
    {
        $fullPath = __DIR__ . '/../../public/' . ltrim($path, '/');
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }

    /**
     * Lấy URL của file
     */
    public static function url($path)
    {
        $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost:8000';
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Kiểm tra file có tồn tại không
     */
    public static function exists($path)
    {
        $fullPath = __DIR__ . '/../../public/' . ltrim($path, '/');
        return file_exists($fullPath);
    }

    /**
     * Lấy kích thước file
     */
    public static function size($path)
    {
        $fullPath = __DIR__ . '/../../public/' . ltrim($path, '/');
        return file_exists($fullPath) ? filesize($fullPath) : 0;
    }
}

