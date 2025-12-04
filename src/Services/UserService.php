<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\UserAddress;

class UserService extends Service
{
    public function list($page = 1, $perPage = 20, $roleId = null)
    {
        $conditions = [];
        if ($roleId) {
            $conditions['role_id'] = $roleId;
        }
        $result = User::paginate($page, $perPage, $conditions, 'created_at DESC');
        foreach ($result['data'] as &$user) {
            unset($user['password']);
        }
        return $result;
    }

    public function get($id)
    {
        $user = User::findWithRole($id);
        if (!$user) {
            return null;
        }
        $user['addresses'] = UserAddress::getByUserId($id);
        unset($user['password']);
        return $user;
    }

    public function update($id, $data)
    {
        unset($data['id'], $data['created_at']);
        User::update($id, $data);
        $user = User::findWithRole($id);
        if ($user) {
            unset($user['password']);
        }
        return $user;
    }
}

