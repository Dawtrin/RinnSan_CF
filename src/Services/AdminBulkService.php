<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Models\Category;
use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Models\ActivityLog;

class AdminBulkService extends Service
{
    public function delete($type, $ids, $actorId = null)
    {
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
                foreach ($ids as $id) {
                    if ($id != $actorId) {
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
                return null;
        }
        ActivityLog::log('admin.bulk.delete.' . $type, 'Admin bulk delete ' . $deleted . ' / ' . count($ids) . ' ' . $type, $actorId, $type, null);
        return [
            'deleted_count' => $deleted,
            'total_requested' => count($ids)
        ];
    }

    public function update($type, $ids, $updates, $actorId = null)
    {
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
                unset($updates['password']);
                foreach ($ids as $id) {
                    User::update($id, $updates);
                    $updated++;
                }
                break;
            default:
                return null;
        }
        ActivityLog::log('admin.bulk.update.' . $type, 'Admin bulk update ' . $updated . ' / ' . count($ids) . ' ' . $type, $actorId, $type, null);
        return [
            'updated_count' => $updated,
            'total_requested' => count($ids)
        ];
    }

    public function activate($type, $ids, $isActive, $actorId = null)
    {
        $updated = 0;
        switch ($type) {
            case 'products':
                foreach ($ids as $id) {
                    Product::update($id, ['is_active' => (int)$isActive]);
                    $updated++;
                }
                break;
            case 'users':
                foreach ($ids as $id) {
                    if ($id != $actorId) {
                        User::update($id, ['is_active' => (int)$isActive]);
                        $updated++;
                    }
                }
                break;
            default:
                return null;
        }
        ActivityLog::log('admin.bulk.' . ((int)$isActive ? 'activate' : 'deactivate') . '.' . $type, 'Admin bulk ' . ((int)$isActive ? 'activate' : 'deactivate') . ' ' . $updated . ' / ' . count($ids) . ' ' . $type, $actorId, $type, null);
        return [
            'updated_count' => $updated,
            'total_requested' => count($ids)
        ];
    }
}
