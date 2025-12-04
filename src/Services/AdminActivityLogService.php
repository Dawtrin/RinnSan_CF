<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminActivityLogService extends Service
{
    public function paginate($page, $perPage, $filters, $sort)
    {
        $conditions = [];
        if (isset($filters['user_id'])) {
            $conditions['user_id'] = $filters['user_id'];
        }
        if (isset($filters['action'])) {
            $conditions['action'] = ['LIKE', '%' . $filters['action'] . '%'];
        }
        $orderBy = $sort['sort'] . ' ' . $sort['order'];
        $result = ActivityLog::paginate($page, $perPage, $conditions, $orderBy);
        foreach ($result['data'] as &$log) {
            if ($log['user_id']) {
                $user = User::find($log['user_id']);
                $log['user_name'] = $user['full_name'] ?? null;
                $log['user_email'] = $user['email'] ?? null;
            }
        }
        return $result;
    }

    public function get($id)
    {
        $log = ActivityLog::find($id);
        if (!$log) {
            return null;
        }
        if ($log['user_id']) {
            $user = User::find($log['user_id']);
            $log['user'] = $user ? [
                'id' => $user['id'],
                'name' => $user['full_name'],
                'email' => $user['email']
            ] : null;
        }
        return $log;
    }

    public function deleteOlderThanDays($days)
    {
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        $sql = "DELETE FROM activity_logs WHERE created_at < ?";
        Database::query($sql, [$cutoffDate]);
        return true;
    }
}

