<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Role;
use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminRoleService extends Service
{
    public function list()
    {
        $roles = Role::all();
        foreach ($roles as &$role) {
            $role = Role::parsePermissions($role);
        }
        return $roles;
    }

    public function create($data)
    {
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $data['permissions'] = json_encode($data['permissions']);
        }
        Role::create($data);
        $role = Role::find(Database::lastInsertId());
        return Role::parsePermissions($role);
    }

    public function update($id, $data)
    {
        $role = Role::find($id);
        if (!$role) {
            return null;
        }
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $data['permissions'] = json_encode($data['permissions']);
        }
        Role::update($id, $data);
        $role = Role::find($id);
        return Role::parsePermissions($role);
    }

    public function delete($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return null;
        }
        $users = User::where('role_id', '=', $id);
        if (!empty($users)) {
            return false;
        }
        Role::delete($id);
        return true;
    }
}

