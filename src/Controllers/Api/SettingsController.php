<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Settings;
use Rinnsan\RinnSanWeb\Services\SettingsService;

class SettingsController extends ApiController
{
    /**
     * Lấy tất cả settings
     * GET /api/settings
     */
    public function index()
    {
        try {
            $service = new SettingsService();
            $settings = $service->all();
            return $this->success($settings, 'Lấy settings thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy setting theo key
     * GET /api/settings/{key}
     */
    public function show($key)
    {
        try {
            $service = new SettingsService();
            $value = $service->get($key);
            return $this->success(['key' => $key, 'value' => $value], 'Lấy setting thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật hoặc tạo setting
     * POST /api/settings
     */
    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['key']) || !isset($data['value'])) {
                return $this->error('Thiếu key hoặc value', 400);
            }
            $service = new SettingsService();
            $result = $service->set($data['key'], $data['value'], $data['description'] ?? null);
            return $this->success($result, 'Cập nhật setting thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật nhiều settings cùng lúc
     * PUT /api/settings/batch
     */
    public function batchUpdate()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!is_array($data)) {
                return $this->error('Dữ liệu phải là object', 400);
            }
            $service = new SettingsService();
            $service->batchSet($data);
            return $this->success([], 'Cập nhật settings thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

