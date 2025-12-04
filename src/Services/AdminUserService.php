<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\ActivityLog;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminUserService extends Service
{
    public function create($data)
    {
        $existing = User::findByEmail($data['email']);
        if ($existing) {
            return null;
        }
        $userData = [
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'full_name' => $data['full_name'],
            'phone' => $data['phone'] ?? null,
            'role_id' => $data['role_id'] ?? 3,
            'is_active' => $data['is_active'] ?? 1
        ];
        User::create($userData);
        $userId = Database::lastInsertId();
        ActivityLog::log('admin.user.create', "Admin tạo user: {$data['email']}", $userId);
        $user = User::find($userId);
        unset($user['password']);
        return $user;
    }

    public function delete($id, $actorId)
    {
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        if ($actorId == $id) {
            return false;
        }
        ActivityLog::log('admin.user.delete', "Admin xóa user: {$user['email']}", $id);
        User::delete($id);
        return true;
    }

    public function activate($id, $isActive)
    {
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        User::update($id, ['is_active' => (int)$isActive]);
        $action = $isActive ? 'activate' : 'deactivate';
        ActivityLog::log("admin.user.$action", "Admin {$action} user: {$user['email']}", $id);
        $user = User::find($id);
        unset($user['password']);
        return $user;
    }

    public function changeRole($id, $roleId)
    {
        $user = User::find($id);
        if (!$user) {
            return null;
        }
        User::update($id, ['role_id' => (int)$roleId]);
        ActivityLog::log('admin.user.change_role', "Admin thay đổi role user: {$user['email']}", $id);
        $user = User::find($id);
        unset($user['password']);
        return $user;
    }
}

