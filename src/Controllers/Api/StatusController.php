<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;
use Rinnsan\RinnSanWeb\Core\Database;

class StatusController extends ApiController
{
    /**
     * Get API status
     */
    public function status($params = [])
    {
        return $this->success([
            'status' => 'running',
            'version' => '1.0.0',
            'environment' => $_ENV['APP_ENV'] ?? 'unknown',
            'timestamp' => date('Y-m-d H:i:s'),
        ], 'API is running');
    }

    /**
     * Get health check
     */
    public function health($params = [])
    {
        return $this->success([
            'health' => 'ok',
            'database' => $this->checkDatabase(),
        ], 'Health check passed');
    }

    /**
     * Check database connection
     */
    protected function checkDatabase()
    {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query('SELECT 1');
            $stmt->fetch();
            return 'connected';
        } catch (\Exception $e) {
            return 'disconnected';
        }
    }
}
