<?php

namespace App\Core;

class Config
{
    private $config = [];

    public function __construct()
    {
        $this->loadConfigFiles();
    }

    private function loadConfigFiles(): void
    {
        $configPath = __DIR__ . '/../../config/';
        
        if (!is_dir($configPath)) {
            return;
        }

        $files = scandir($configPath);
        
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $key = pathinfo($file, PATHINFO_FILENAME);
                $this->config[$key] = require $configPath . $file;
            }
        }
    }

    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public function set(string $key, $value): void
    {
        $keys = explode('.', $key);
        $config = &$this->config;

        foreach ($keys as $k) {
            if (!isset($config[$k]) || !is_array($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }

        $config = $value;
    }

    public function all(): array
    {
        return $this->config;
    }
}