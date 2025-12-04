<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Services\AdminActivityLogService;

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
            $service = new AdminActivityLogService();
            $result = $service->paginate($pagination['page'], $pagination['per_page'], $filters, $sort);
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
            $service = new AdminActivityLogService();
            $log = $service->get($id);
            if (!$log) {
                return $this->error('Log không tồn tại', 404);
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
            $days = (int)($data['days'] ?? 90);
            $service = new AdminActivityLogService();
            $service->deleteOlderThanDays($days);
            return $this->success([], "Đã xóa logs cũ hơn {$days} ngày");
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

