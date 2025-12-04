<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Settings;

class SettingsService extends Service
{
    public function all()
    {
        return Settings::getAll();
    }

    public function get($key)
    {
        return Settings::get($key);
    }

    public function set($key, $value, $description = null)
    {
        Settings::set($key, $value, $description);
        return ['key' => $key, 'value' => $value];
    }

    public function batchSet($data)
    {
        foreach ($data as $key => $value) {
            Settings::set($key, $value);
        }
        return true;
    }
}

