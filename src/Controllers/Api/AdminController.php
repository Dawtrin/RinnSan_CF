<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Services\AdminService;
use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Models\User;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminController extends ApiController
{
    /**
     * Dashboard statistics
     * GET /api/admin/dashboard
     */
    public function dashboard()
    {
        try {
            $service = new AdminService();
            $dashboard = $service->dashboard();
            return $this->success($dashboard, 'Lấy dashboard thành công');
            // Tổng quan
            $totalProducts = count(Product::all());
            $totalOrders = count(Order::all());
            $totalUsers = count(User::all());
            
            // Doanh thu hôm nay
            $today = date('Y-m-d');
            $todayStats = Order::getStatistics($today . ' 00:00:00', $today . ' 23:59:59');
            
            // Doanh thu tháng này
            $firstDayOfMonth = date('Y-m-01 00:00:00');
            $lastDayOfMonth = date('Y-m-t 23:59:59');
            $monthStats = Order::getStatistics($firstDayOfMonth, $lastDayOfMonth);
            
            // Đơn hàng gần đây
            $recentOrders = Order::getByStatus('pending', 10);
            
            // Top sản phẩm bán chạy
            $topProducts = Database::fetchAll("
                SELECT TOP 10 
                    p.id, p.name, p.price, 
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.total_price) as total_revenue
                FROM products p
                LEFT JOIN order_items oi ON p.id = oi.product_id
                LEFT JOIN orders o ON oi.order_id = o.id AND o.order_status = 'completed'
                GROUP BY p.id, p.name, p.price
                ORDER BY total_sold DESC
            ");
            
            return $this->success([
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
            ], 'Lấy dashboard thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Statistics by date range
     * GET /api/admin/statistics
     */
    public function statistics()
    {
        try {
            $startDate = $_GET['start_date'] ?? date('Y-m-01');
            $endDate = $_GET['end_date'] ?? date('Y-m-d');
            $service = new AdminService();
            $result = $service->statistics($startDate, $endDate);
            return $this->success($result, 'Lấy thống kê thành công');
            
            $stats = Order::getStatistics($startDate . ' 00:00:00', $endDate . ' 23:59:59');
            
            // Doanh thu theo ngày
            $dailyRevenue = Database::fetchAll("
                SELECT 
                    CAST(created_at AS DATE) as date,
                    COUNT(*) as order_count,
                    SUM(total_amount) as revenue
                FROM orders
                WHERE created_at >= ? AND created_at <= ?
                AND order_status = 'completed'
                GROUP BY CAST(created_at AS DATE)
                ORDER BY date ASC
            ", [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            
            // Đơn hàng theo trạng thái
            $ordersByStatus = Database::fetchAll("
                SELECT 
                    order_status,
                    COUNT(*) as count
                FROM orders
                WHERE created_at >= ? AND created_at <= ?
                GROUP BY order_status
            ", [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            
            return $this->success([
                'summary' => $stats,
                'daily_revenue' => $dailyRevenue,
                'orders_by_status' => $ordersByStatus
            ], 'Lấy thống kê thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy đơn hàng gần đây với pagination
     * GET /api/admin/orders/recent
     */
    public function recentOrders()
    {
        try {
            $pagination = \Rinnsan\RinnSanWeb\Helpers\RequestHelper::getPaginationParams();
            $status = $_GET['status'] ?? null;
            $service = new AdminService();
            $result = $service->recentOrders($pagination['page'], $pagination['per_page'], $status);
            return $this->success($result['data'], 'Lấy đơn hàng thành công', 200, [
                'pagination' => $result['pagination']
            ]);
            
            $conditions = [];
            if ($status) {
                $conditions['order_status'] = $status;
            }
            
            $result = \Rinnsan\RinnSanWeb\Models\Order::paginate(
                $pagination['page'],
                $pagination['per_page'],
                $conditions,
                'created_at DESC'
            );
            
            // Lấy items cho mỗi order
            foreach ($result['data'] as &$order) {
                $order['items'] = \Rinnsan\RinnSanWeb\Models\OrderItem::getByOrderId($order['id']);
            }
            
            return $this->success($result['data'], 'Lấy đơn hàng thành công', 200, [
                'pagination' => $result['pagination']
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Top sản phẩm bán chạy
     * GET /api/admin/products/top-selling
     */
    public function topSellingProducts()
    {
        try {
            $limit = (int)($_GET['limit'] ?? 10);
            $service = new AdminService();
            $products = $service->topSellingProducts($limit);
            return $this->success($products, 'Lấy top sản phẩm thành công');
            
            $products = Database::fetchAll("\n                SELECT TOP $limit\n                    p.id, p.name, p.price, p.sku,\n                    SUM(oi.quantity) as total_sold,\n                    SUM(oi.total_price) as total_revenue,\n                    COUNT(DISTINCT oi.order_id) as order_count\n                FROM products p\n                LEFT JOIN order_items oi ON p.id = oi.product_id\n                LEFT JOIN orders o ON oi.order_id = o.id AND o.order_status = 'completed'\n                GROUP BY p.id, p.name, p.price, p.sku\n                ORDER BY total_sold DESC\n            ");
            
            return $this->success($products, 'Lấy top sản phẩm thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

