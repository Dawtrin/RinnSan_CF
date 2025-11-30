<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'permissions'];

    /**
     * Lấy role theo name
     */
    public static function findByName($name)
    {
        $sql = "SELECT * FROM roles WHERE name = ?";
        return Database::fetch($sql, [$name]);
    }

    /**
     * Parse permissions từ JSON
     */
    public static function parsePermissions($role)
    {
        if (isset($role['permissions']) && is_string($role['permissions'])) {
            $role['permissions'] = json_decode($role['permissions'], true) ?: [];
        }
        return $role;
    }

    /**
     * Kiểm tra quyền
     */
    public static function hasPermission($role, $permission)
    {
        $role = self::parsePermissions($role);
        if (!isset($role['permissions']) || !is_array($role['permissions'])) {
            return false;
        }
        return in_array($permission, $role['permissions']);
    }
}

