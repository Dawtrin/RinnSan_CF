<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;

class AdminActivityLogController extends ApiController
{
    /**
     * Lấy danh sách activity logs
     * GET /api/admin/activity-logs
     */
    public function index()
    {
        try {
            $pagination = RequestHelper::getPaginationParams();
            $filters = RequestHelper::getFilters(['user_id', 'action']);
            $sort = RequestHelper::getSortParams('created_at', 'DESC');
            
            $conditions = [];
            if (isset($filters['user_id'])) {
                $conditions['user_id'] = $filters['user_id'];
            }
            if (isset($filters['action'])) {
                $conditions['action'] = ['LIKE', '%' . $filters['action'] . '%'];
            }
            
            $result = ActivityLog::paginate(
                $pagination['page'], 
                $pagination['per_page'], 
                $conditions, 
                $sort['sort'] . ' ' . $sort['order']
            );
            
            // Lấy thông tin user cho mỗi log
            foreach ($result['data'] as &$log) {
                if ($log['user_id']) {
                    $user = \Rinnsan\RinnSanWeb\Models\User::find($log['user_id']);
                    $log['user_name'] = $user['full_name'] ?? null;
                    $log['user_email'] = $user['email'] ?? null;
                }
            }
            
            return $this->success($result['data'], 'Lấy danh sách logs thành công', 200, [
                'pagination' => $result['pagination']
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy chi tiết log
     * GET /api/admin/activity-logs/{id}
     */
    public function show($id)
    {
        try {
            $log = ActivityLog::find($id);
            
            if (!$log) {
                return $this->error('Log không tồn tại', 404);
            }
            
            // Lấy thông tin user
            if ($log['user_id']) {
                $user = \Rinnsan\RinnSanWeb\Models\User::find($log['user_id']);
                $log['user'] = $user ? [
                    'id' => $user['id'],
                    'name' => $user['full_name'],
                    'email' => $user['email']
                ] : null;
            }
            
            return $this->success($log, 'Lấy chi tiết log thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa logs cũ
     * DELETE /api/admin/activity-logs
     */
    public function destroy()
    {
        try {
            $data = RequestHelper::input();
            $days = (int)($data['days'] ?? 90); // Mặc định xóa logs > 90 ngày
            
            $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));
            
            $sql = "DELETE FROM activity_logs WHERE created_at < ?";
            \Rinnsan\RinnSanWeb\Core\Database::query($sql, [$cutoffDate]);
            
            return $this->success([], "Đã xóa logs cũ hơn {$days} ngày");
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

