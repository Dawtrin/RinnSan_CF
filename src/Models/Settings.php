<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Settings extends Model
{
    protected $table = 'settings';
    protected $fillable = ['setting_key', 'value', 'description'];

    /**
     * Lấy giá trị setting theo key
     */
    public static function get($key, $default = null)
    {
        $setting = Database::fetch(
            "SELECT value FROM settings WHERE setting_key = ?",
            [$key]
        );
        
        return $setting ? $setting['value'] : $default;
    }

    /**
     * Set giá trị setting
     */
    public static function set($key, $value, $description = null)
    {
        $existing = Database::fetch(
            "SELECT id FROM settings WHERE setting_key = ?",
            [$key]
        );
        
        if ($existing) {
            return Database::query(
                "UPDATE settings SET value = ?, description = ?, updated_at = GETDATE() WHERE setting_key = ?",
                [$value, $description, $key]
            );
        } else {
            return self::create([
                'setting_key' => $key,
                'value' => $value,
                'description' => $description
            ]);
        }
    }

    /**
     * Lấy tất cả settings
     */
    public static function getAll()
    {
        $settings = Database::fetchAll("SELECT * FROM settings ORDER BY setting_key ASC");
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['value'];
        }
        return $result;
    }

    /**
     * Xóa setting
     */
    public static function remove($key)
    {
        return Database::query("DELETE FROM settings WHERE setting_key = ?", [$key]);
    }
}

