<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Models\Category;
use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Core\Database;

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
            
            $type = $data['type'];
            $ids = $data['ids'];
            $deleted = 0;
            
            switch ($type) {
                case 'products':
                    foreach ($ids as $id) {
                        Product::delete($id);
                        $deleted++;
                    }
                    break;
                    
                case 'categories':
                    foreach ($ids as $id) {
                        Category::delete($id);
                        $deleted++;
                    }
                    break;
                    
                case 'users':
                    // Không cho xóa chính mình
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $currentUserId = $_SESSION['user_id'] ?? null;
                    
                    foreach ($ids as $id) {
                        if ($id != $currentUserId) {
                            User::delete($id);
                            $deleted++;
                        }
                    }
                    break;
                    
                case 'orders':
                    foreach ($ids as $id) {
                        Order::delete($id);
                        $deleted++;
                    }
                    break;
                    
                default:
                    return $this->error('Type không hợp lệ', 400);
            }
            
            return $this->success([
                'deleted_count' => $deleted,
                'total_requested' => count($ids)
            ], "Đã xóa {$deleted} items");
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
            
            $type = $data['type'];
            $ids = $data['ids'];
            $updates = $data['updates'];
            $updated = 0;
            
            switch ($type) {
                case 'products':
                    foreach ($ids as $id) {
                        Product::update($id, $updates);
                        $updated++;
                    }
                    break;
                    
                case 'categories':
                    foreach ($ids as $id) {
                        Category::update($id, $updates);
                        $updated++;
                    }
                    break;
                    
                case 'users':
                    foreach ($ids as $id) {
                        // Không cho update password ở đây
                        unset($updates['password']);
                        User::update($id, $updates);
                        $updated++;
                    }
                    break;
                    
                default:
                    return $this->error('Type không hợp lệ', 400);
            }
            
            return $this->success([
                'updated_count' => $updated,
                'total_requested' => count($ids)
            ], "Đã cập nhật {$updated} items");
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
            
            $type = $data['type'];
            $ids = $data['ids'];
            $isActive = (int)$data['is_active'];
            $updated = 0;
            
            switch ($type) {
                case 'products':
                    foreach ($ids as $id) {
                        Product::update($id, ['is_active' => $isActive]);
                        $updated++;
                    }
                    break;
                    
                case 'users':
                    // Không cho khóa chính mình
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $currentUserId = $_SESSION['user_id'] ?? null;
                    
                    foreach ($ids as $id) {
                        if ($id != $currentUserId) {
                            User::update($id, ['is_active' => $isActive]);
                            $updated++;
                        }
                    }
                    break;
                    
                default:
                    return $this->error('Type không hợp lệ', 400);
            }
            
            return $this->success([
                'updated_count' => $updated,
                'total_requested' => count($ids)
            ], "Đã " . ($isActive ? 'kích hoạt' : 'khóa') . " {$updated} items");
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

