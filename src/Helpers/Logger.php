<?php

namespace Rinnsan\RinnSanWeb\Helpers;

class Logger
{
    private static $logPath = __DIR__ . '/../../storage/logs/';

    /**
     * Log info
     */
    public static function info($message, $context = [])
    {
        self::log('INFO', $message, $context);
    }

    /**
     * Log error
     */
    public static function error($message, $context = [])
    {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log warning
     */
    public static function warning($message, $context = [])
    {
        self::log('WARNING', $message, $context);
    }

    /**
     * Log debug
     */
    public static function debug($message, $context = [])
    {
        if (($_ENV['APP_DEBUG'] ?? false) === 'true') {
            self::log('DEBUG', $message, $context);
        }
    }

    /**
     * Write log
     */
    private static function log($level, $message, $context = [])
    {
        // Tạo thư mục nếu chưa có
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0755, true);
        }
        
        $date = date('Y-m-d');
        $file = self::$logPath . "app-{$date}.log";
        
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        $logMessage = "[{$timestamp}] [{$level}] {$message}{$contextStr}" . PHP_EOL;
        
        file_put_contents($file, $logMessage, FILE_APPEND);
    }
}

