<?php

namespace Rinnsan\RinnSanWeb\Helpers;

class CacheHelper
{
    private static $cachePath = __DIR__ . '/../../storage/cache/';
    private static $defaultTTL = 3600; // 1 hour

    /**
     * Lấy cache
     */
    public static function get($key)
    {
        $file = self::getCacheFile($key);
        
        if (!file_exists($file)) {
            return null;
        }
        
        $data = json_decode(file_get_contents($file), true);
        
        // Kiểm tra expired
        if ($data['expires_at'] < time()) {
            unlink($file);
            return null;
        }
        
        return $data['value'];
    }

    /**
     * Set cache
     */
    public static function set($key, $value, $ttl = null)
    {
        $ttl = $ttl ?? self::$defaultTTL;
        $file = self::getCacheFile($key);
        
        // Tạo thư mục nếu chưa có
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl,
            'created_at' => time()
        ];
        
        file_put_contents($file, json_encode($data));
    }

    /**
     * Xóa cache
     */
    public static function forget($key)
    {
        $file = self::getCacheFile($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Xóa tất cả cache
     */
    public static function flush()
    {
        $files = glob(self::$cachePath . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Lấy hoặc set cache
     */
    public static function remember($key, $callback, $ttl = null)
    {
        $value = self::get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        self::set($key, $value, $ttl);
        
        return $value;
    }

    /**
     * Lấy cache file path
     */
    private static function getCacheFile($key)
    {
        $hash = md5($key);
        return self::$cachePath . $hash . '.json';
    }
}

