<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Models\OrderItem;
use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Models\Coupon;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Services\OrderService;

class OrderController extends ApiController
{
    /**
     * Lấy danh sách đơn hàng
     * GET /api/orders
     */
    public function index()
    {
        try {
            $userId = $_GET['user_id'] ?? null;
            $status = $_GET['status'] ?? null;
            $limit = (int)($_GET['limit'] ?? 20);
            $service = new OrderService();
            if ($userId) {
                $orders = $service->getByUser($userId, $limit);
            } elseif ($status) {
                $orders = $service->getByStatus($status, $limit);
            } else {
                $orders = $service->getByStatus('pending', $limit);
            }
            return $this->success($orders, 'Lấy danh sách đơn hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy chi tiết đơn hàng
     * GET /api/orders/{id}
     */
    public function show($id)
    {
        try {
            $order = Order::findWithItems($id);
            
            if (!$order) {
                return $this->error('Đơn hàng không tồn tại', 404);
            }
            
            return $this->success($order, 'Lấy chi tiết đơn hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo đơn hàng mới
     * POST /api/orders
     */
    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || !isset($data['items']) || empty($data['items'])) {
                return $this->error('Dữ liệu không hợp lệ. Cần có items', 400);
            }
            $service = new OrderService();
            $order = $service->create([
                'user_id' => $data['user_id'] ?? null,
                'items' => $data['items'],
                'customer' => [
                    'name' => $data['customer_name'] ?? 'Khách hàng',
                    'phone' => $data['customer_phone'] ?? '',
                    'email' => $data['customer_email'] ?? null,
                    'shipping_address' => $data['shipping_address'] ?? null,
                ],
                'shipping_fee' => $data['shipping_fee'] ?? 0,
                'discount_amount' => isset($data['coupon_code']) ? 0 : ($data['discount_amount'] ?? 0),
                'payment_method' => $data['payment_method'] ?? null,
            ]);
            if (!$order) {
                return $this->error('Không thể tạo đơn hàng', 400);
            }
            return $this->success($order, 'Tạo đơn hàng thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật trạng thái đơn hàng
     * PUT /api/orders/{id}/status
     */
    public function updateStatus($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['status'])) {
                return $this->error('Thiếu trường status', 400);
            }
            $service = new OrderService();
            $order = $service->updateStatus($id, $data['status'], $data['reason'] ?? null);
            if (!$order) {
                return $this->error('Đơn hàng không tồn tại', 404);
            }
            return $this->success($order, 'Cập nhật trạng thái đơn hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy thống kê đơn hàng
     * GET /api/orders/statistics
     */
    public function statistics()
    {
        try {
            $startDate = $_GET['start_date'] ?? null;
            $endDate = $_GET['end_date'] ?? null;
            
            $service = new OrderService();
            $stats = $service->statistics($startDate, $endDate);
            
            return $this->success($stats, 'Lấy thống kê thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật đơn hàng (Admin - full update)
     * PUT /api/admin/orders/{id}
     */
    public function adminUpdate($id)
    {
        try {
            $order = Order::find($id);
            if (!$order) {
                return $this->error('Đơn hàng không tồn tại', 404);
            }
            
            $data = \Rinnsan\RinnSanWeb\Helpers\RequestHelper::inputSanitized();
            if (!$data) {
                return $this->error('Dữ liệu không hợp lệ', 400);
            }
            
            // Không cho phép thay đổi một số fields
            unset($data['id'], $data['order_code'], $data['created_at']);
            
            Order::update($id, $data);
            $order = Order::findWithItems($id);
            
            return $this->success($order, 'Cập nhật đơn hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa đơn hàng (Admin)
     * DELETE /api/admin/orders/{id}
     */
    public function adminDestroy($id)
    {
        try {
            $order = Order::find($id);
            if (!$order) {
                return $this->error('Đơn hàng không tồn tại', 404);
            }
            
            // Chỉ cho phép xóa đơn hàng ở trạng thái cancelled hoặc pending
            if (!in_array($order['order_status'], ['cancelled', 'pending'])) {
                return $this->error('Chỉ có thể xóa đơn hàng đã hủy hoặc đang chờ', 400);
            }
            
            Order::delete($id);
            
            return $this->success([], 'Xóa đơn hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

