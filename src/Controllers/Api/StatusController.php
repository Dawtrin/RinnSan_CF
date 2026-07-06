<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

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
            $pdo = new \PDO(
                "mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'],
                $_ENV['DB_USERNAME'],
                $_ENV['DB_PASSWORD']
            );
            return 'connected';
        } catch (\Exception $e) {
            return 'disconnected';
        }
    }
}
