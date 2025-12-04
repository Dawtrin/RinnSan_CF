<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Models\Category;
use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Services\AdminBulkService;

class AdminBulkController extends ApiController
{
    /**
     * Bulk delete
     * POST /api/admin/bulk/delete
     */
    public function delete()
    {
        try {
            $data = RequestHelper::input();
            
            if (!isset($data['type']) || !isset($data['ids']) || !is_array($data['ids'])) {
                return $this->error('Thiếu type hoặc ids', 400);
            }
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $currentUserId = $_SESSION['user_id'] ?? null;
            $service = new AdminBulkService();
            $result = $service->delete($data['type'], $data['ids'], $currentUserId);
            if ($result === null) {
                return $this->error('Type không hợp lệ', 400);
            }
            return $this->success($result, "Đã xóa {$result['deleted_count']} items");
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Bulk update
     * POST /api/admin/bulk/update
     */
    public function update()
    {
        try {
            $data = RequestHelper::input();
            
            if (!isset($data['type']) || !isset($data['ids']) || !isset($data['updates'])) {
                return $this->error('Thiếu type, ids hoặc updates', 400);
            }
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $currentUserId = $_SESSION['user_id'] ?? null;
            $service = new AdminBulkService();
            $result = $service->update($data['type'], $data['ids'], $data['updates'], $currentUserId);
            if ($result === null) {
                return $this->error('Type không hợp lệ', 400);
            }
            return $this->success($result, "Đã cập nhật {$result['updated_count']} items");
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Bulk activate/deactivate
     * POST /api/admin/bulk/activate
     */
    public function activate()
    {
        try {
            $data = RequestHelper::input();
            
            if (!isset($data['type']) || !isset($data['ids']) || !isset($data['is_active'])) {
                return $this->error('Thiếu type, ids hoặc is_active', 400);
            }
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $currentUserId = $_SESSION['user_id'] ?? null;
            $service = new AdminBulkService();
            $result = $service->activate($data['type'], $data['ids'], (int)$data['is_active'], $currentUserId);
            if ($result === null) {
                return $this->error('Type không hợp lệ', 400);
            }
            return $this->success($result, "Đã " . ((int)$data['is_active'] ? 'kích hoạt' : 'khóa') . " {$result['updated_count']} items");
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

