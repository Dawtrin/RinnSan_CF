<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    protected $fillable = [
        'user_id', 'action', 'description', 'ip_address', 
        'user_agent', 'reference_type', 'reference_id'
    ];

    /**
     * Ghi log hoạt động
     */
    public static function log($action, $description = null, $userId = null, $referenceType = null, $referenceId = null)
    {
        $data = [
            'action' => $action,
            'description' => $description,
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId
        ];
        
        return self::create($data);
    }

    /**
     * Lấy log theo user_id
     */
    public static function getByUserId($userId, $limit = 50)
    {
        $sql = "SELECT * FROM activity_logs 
                WHERE user_id = ? 
                ORDER BY created_at DESC
                OFFSET 0 ROWS FETCH NEXT $limit ROWS ONLY";
        
        return Database::fetchAll($sql, [$userId]);
    }

    /**
     * Lấy log theo action
     */
    public static function getByAction($action, $limit = 50)
    {
        $sql = "SELECT al.*, u.full_name as user_name 
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.id
                WHERE al.action = ?
                ORDER BY al.created_at DESC
                OFFSET 0 ROWS FETCH NEXT $limit ROWS ONLY";
        
        return Database::fetchAll($sql, [$action]);
    }

    /**
     * Lấy log gần đây
     */
    public static function getRecent($limit = 100)
    {
        $sql = "SELECT al.*, u.full_name as user_name 
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.created_at DESC
                OFFSET 0 ROWS FETCH NEXT $limit ROWS ONLY";
        
        return Database::fetchAll($sql);
    }
}

