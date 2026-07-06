<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminController extends ApiController
{
    /**
     * API Dashboard (Trang chủ Admin)
     * [FIX] Thêm hàm này để trang Tổng quan không bị lỗi
     */
    public function dashboard()
    {
        // Tận dụng logic của hàm statistics cho dashboard
        return $this->statistics();
    }

    /**
     * API Thống kê tổng hợp & Báo cáo
     * URL: GET /api/admin/statistics?period=week
     */
    public function statistics()
    {
        try {
            $period = $_GET['period'] ?? 'week';

            // 1. KPI TỔNG QUAN
            $kpi = [];
            try { $kpi['total_revenue']   = Database::fetch("SELECT COALESCE(SUM(total_amount), 0) as t FROM orders WHERE order_status = 'completed'")['t']; } catch(\Exception $e) { $kpi['total_revenue'] = 0; }
            try { $kpi['total_orders']    = Database::fetch("SELECT COUNT(*) as t FROM orders")['t']; } catch(\Exception $e) { $kpi['total_orders'] = 0; }
            try { $kpi['total_products']  = Database::fetch("SELECT COUNT(*) as t FROM products WHERE is_active = 1")['t']; } catch(\Exception $e) { $kpi['total_products'] = 0; }
            try { $kpi['total_customers'] = Database::fetch("SELECT COUNT(*) as t FROM users WHERE role_id = 3")['t']; } catch(\Exception $e) { $kpi['total_customers'] = 0; }
            try { $kpi['total_staff']     = Database::fetch("SELECT COUNT(*) as t FROM users WHERE role_id IN (1, 2)")['t']; } catch(\Exception $e) { $kpi['total_staff'] = 0; }
            try { $kpi['total_suppliers'] = Database::fetch("SELECT COUNT(*) as t FROM suppliers")['t'] ?? 0; } catch(\Exception $e) { $kpi['total_suppliers'] = 0; }
            try { $kpi['total_vouchers']  = Database::fetch("SELECT COUNT(*) as t FROM coupons")['t'] ?? 0; } catch(\Exception $e) { $kpi['total_vouchers'] = 0; }
            try { $kpi['pending_orders']  = Database::fetch("SELECT COUNT(*) as t FROM orders WHERE order_status = 'pending'")['t']; } catch(\Exception $e) { $kpi['pending_orders'] = 0; }

            // 2. MÓN YÊU THÍCH (Bán chạy nhất theo số lượng)
            try {
                $favSql = "SELECT TOP 1 p.name, SUM(oi.quantity) as total_sold
                           FROM order_items oi
                           JOIN orders o ON oi.order_id = o.id
                           JOIN products p ON oi.product_id = p.id
                           WHERE o.order_status = 'completed'
                           GROUP BY p.name
                           ORDER BY total_sold DESC";
                $favResult = Database::fetch($favSql);
                $kpi['favorite_product'] = $favResult ? $favResult['name'] : 'Chưa có';
                $kpi['favorite_sold'] = $favResult ? $favResult['total_sold'] : 0;
            } catch (\Exception $e) {
                $kpi['favorite_product'] = '---';
            }

            // 3. BIỂU ĐỒ DOANH THU (Area Chart)
            $chartData = $this->getRevenueData($period);

            // 4. BIỂU ĐỒ DANH MỤC (Pie Chart)
            $pieChart = $this->getCategoryShareData();

            return $this->success([
                ...$kpi, 
                'chart_data' => $chartData,
                'pie_chart' => $pieChart
            ]);

        } catch (\Exception $e) {
            return $this->error("Lỗi thống kê: " . $e->getMessage(), 500);
        }
    }

    /**
     * API Top sản phẩm bán chạy (ĐÃ FIX LỖI ẢNH)
     */
    public function topSellingProducts()
    {
        try {
            // [FIX SQL] Dùng MAX(CAST(...)) để tránh lỗi Group By cột Text/Ntext (images)
            $sql = "SELECT TOP 5 
                        p.id, 
                        p.name, 
                        p.price, 
                        CAST(MAX(CAST(p.images AS NVARCHAR(MAX))) AS NVARCHAR(MAX)) as image_raw,
                        COALESCE(SUM(oi.quantity), 0) as sales
                    FROM order_items oi
                    JOIN orders o ON oi.order_id = o.id
                    JOIN products p ON oi.product_id = p.id
                    WHERE o.order_status = 'completed'
                    GROUP BY p.id, p.name, p.price
                    ORDER BY sales DESC";

            $products = Database::fetchAll($sql);

            // [FIX ẢNH] Xử lý chuỗi JSON ảnh thành Link thật
            foreach ($products as &$product) {
                $imageUrl = null;
                // 1. Kiểm tra xem có dữ liệu không
                if (!empty($product['image_raw'])) {
                    // 2. Giải nén JSON: '["img1.jpg"]' -> array
                    $decoded = json_decode($product['image_raw'], true);
                    
                    if (is_array($decoded) && count($decoded) > 0) {
                        // Lấy ảnh đầu tiên
                        $imageUrl = $decoded[0];
                    } else {
                        // Trường hợp lưu chuỗi thường không phải JSON
                        $imageUrl = $product['image_raw'];
                    }
                }
                
                // 3. Gán lại vào biến 'image' để Frontend dùng
                // Nếu đường dẫn chưa có http, Frontend sẽ tự thêm base_url
                $product['image'] = $imageUrl;
                unset($product['image_raw']); // Xóa biến tạm
            }

            return $this->success($products);
        } catch (\Exception $e) {
            return $this->success([]); // Trả về rỗng nếu lỗi để không sập trang
        }
    }

    /**
     * API Đơn hàng gần đây
     */
    public function recentOrders()
    {
        try {
            $sql = "SELECT TOP 5 
                        o.id, o.order_code, o.total_amount, 
                        o.order_status as status, 
                        o.created_at, 
                        COALESCE(u.full_name, o.customer_name, N'Khách vãng lai') as customer_name
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.id
                    ORDER BY o.created_at DESC";
            return $this->success(Database::fetchAll($sql));
        } catch (\Exception $e) { return $this->success([]); }
    }

    // --- PRIVATE HELPERS ---

    private function getRevenueData($period)
    {
        $data = [];
        try {
            if ($period === 'year') {
                $currentYear = date('Y');
                $sql = "SELECT MONTH(created_at) as m, SUM(total_amount) as total
                        FROM orders 
                        WHERE order_status = 'completed' AND YEAR(created_at) = ?
                        GROUP BY MONTH(created_at)";
                $results = Database::fetchAll($sql, [$currentYear]);
                
                $revenueByMonth = [];
                foreach ($results as $r) $revenueByMonth[$r['m']] = $r['total'];

                for ($m = 1; $m <= 12; $m++) {
                    $data[] = ['date' => "T$m", 'value' => (int)($revenueByMonth[$m] ?? 0)];
                }
            } else {
                $days = ($period === 'month') ? 30 : 7;
                for ($i = $days - 1; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $val = Database::fetch("SELECT SUM(total_amount) as t FROM orders WHERE order_status = 'completed' AND CAST(created_at AS DATE) = ?", [$date])['t'] ?? 0;
                    $data[] = ['date' => date('d/m', strtotime($date)), 'value' => (int)$val];
                }
            }
        } catch (\Exception $e) { return []; }
        return $data;
    }

    private function getCategoryShareData()
    {
        try {
            $sql = "SELECT TOP 5 c.name, COUNT(oi.id) as total_sold
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.id
                    JOIN categories c ON p.category_id = c.id
                    JOIN orders o ON oi.order_id = o.id
                    WHERE o.order_status = 'completed'
                    GROUP BY c.name
                    ORDER BY total_sold DESC";
            
            $results = Database::fetchAll($sql);
            if (empty($results)) return [];

            $total = array_sum(array_column($results, 'total_sold'));
            $colors = ['#f59e0b', '#10b981', '#3b82f6', '#ef4444', '#8b5cf6'];
            
            $finalData = [];
            foreach ($results as $index => $row) {
                $finalData[] = [
                    'name' => $row['name'],
                    'value' => $total > 0 ? round(($row['total_sold'] / $total) * 100) : 0,
                    'color' => $colors[$index % count($colors)]
                ];
            }
            return $finalData;
        } catch (\Exception $e) { return []; }
    }
}