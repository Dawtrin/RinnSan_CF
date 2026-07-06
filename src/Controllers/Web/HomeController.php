<?php

namespace Rinnsan\RinnSanWeb\Controllers\Web;

use Rinnsan\RinnSanWeb\Controllers\Controller;
use Rinnsan\RinnSanWeb\Core\Database;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'RINNSAN_WEB - Home',
            'app_name' => $_ENV['APP_NAME'] ?? 'RINNSAN_WEB',
            'version' => '1.0.0'
        ];

        $this->view('home', $data);
    }

    public function dashboard()
    {
        try {
            // Get basic stats
            $stats = [
                'total_products' => Database::fetch("SELECT COUNT(*) as count FROM products")['count'],
                'total_users' => Database::fetch("SELECT COUNT(*) as count FROM users")['count'],
                'total_orders' => Database::fetch("SELECT COUNT(*) as count FROM orders")['count'],
                'total_categories' => Database::fetch("SELECT COUNT(*) as count FROM categories")['count']
            ];

            $data = [
                'title' => 'Dashboard',
                'stats' => $stats
            ];

            $this->view('dashboard', $data);

        } catch (\Exception $e) {
            $data = [
                'title' => 'Dashboard',
                'error' => $e->getMessage()
            ];
            $this->view('dashboard', $data);
        }
    }

    public function apiDocs()
    {
        $data = [
            'title' => 'API Documentation',
            'base_url' => $_ENV['APP_URL'] ?? 'http://localhost:8000'
        ];

        $this->view('api-docs', $data);
    }
}