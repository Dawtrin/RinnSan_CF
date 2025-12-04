<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Models\OrderItem;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminService extends Service
{
    public function dashboard()
    {
        $totalProducts = count(Product::all());
        $totalOrders = count(Order::all());
        $totalUsers = count(User::all());

        $today = date('Y-m-d');
        $todayStats = Order::getStatistics($today . ' 00:00:00', $today . ' 23:59:59');

        $firstDayOfMonth = date('Y-m-01 00:00:00');
        $lastDayOfMonth = date('Y-m-t 23:59:59');
        $monthStats = Order::getStatistics($firstDayOfMonth, $lastDayOfMonth);

        $recentOrders = Order::getByStatus('pending', 10);

        $topProducts = Database::fetchAll(
            "SELECT TOP 10 
                p.id, p.name, p.price, 
                SUM(oi.quantity) as total_sold,
                SUM(oi.total_price) as total_revenue
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            LEFT JOIN orders o ON oi.order_id = o.id AND o.order_status = 'completed'
            GROUP BY p.id, p.name, p.price
            ORDER BY total_sold DESC"
        );

        return [
            'overview' => [
                'total_products' => $totalProducts,
                'total_orders' => $totalOrders,
                'total_users' => $totalUsers
            ],
            'revenue' => [
                'today' => $todayStats,
                'this_month' => $monthStats
            ],
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts
        ];
    }

    public function statistics($startDate, $endDate)
    {
        $stats = Order::getStatistics($startDate . ' 00:00:00', $endDate . ' 23:59:59');

        $dailyRevenue = Database::fetchAll(
            "SELECT 
                CAST(created_at AS DATE) as date,
                COUNT(*) as order_count,
                SUM(total_amount) as revenue
            FROM orders
            WHERE created_at >= ? AND created_at <= ?
            AND order_status = 'completed'
            GROUP BY CAST(created_at AS DATE)
            ORDER BY date ASC",
            [$startDate . ' 00:00:00', $endDate . ' 23:59:59']
        );

        $ordersByStatus = Database::fetchAll(
            "SELECT 
                order_status,
                COUNT(*) as count
            FROM orders
            WHERE created_at >= ? AND created_at <= ?
            GROUP BY order_status",
            [$startDate . ' 00:00:00', $endDate . ' 23:59:59']
        );

        return [
            'summary' => $stats,
            'daily_revenue' => $dailyRevenue,
            'orders_by_status' => $ordersByStatus
        ];
    }

    public function recentOrders($page, $perPage, $status = null)
    {
        $conditions = [];
        if ($status) {
            $conditions['order_status'] = $status;
        }
        $result = Order::paginate($page, $perPage, $conditions, 'created_at DESC');
        foreach ($result['data'] as &$order) {
            $order['items'] = OrderItem::getByOrderId($order['id']);
        }
        return $result;
    }

    public function topSellingProducts($limit = 10)
    {
        $sql = "SELECT TOP $limit
                p.id, p.name, p.price, p.sku,
                SUM(oi.quantity) as total_sold,
                SUM(oi.total_price) as total_revenue,
                COUNT(DISTINCT oi.order_id) as order_count
            FROM products p
            LEFT JOIN order_items oi ON p.id = oi.product_id
            LEFT JOIN orders o ON oi.order_id = o.id AND o.order_status = 'completed'
            GROUP BY p.id, p.name, p.price, p.sku
            ORDER BY total_sold DESC";
        return Database::fetchAll($sql);
    }
}

