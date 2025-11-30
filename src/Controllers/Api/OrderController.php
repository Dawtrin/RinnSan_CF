<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Models\OrderItem;
use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Models\Coupon;
use Rinnsan\RinnSanWeb\Core\Database;

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
            
            if ($userId) {
                $orders = Order::getByUserId($userId, $limit);
            } elseif ($status) {
                $orders = Order::getByStatus($status, $limit);
            } else {
                $orders = Order::getByStatus('pending', $limit);
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
            
            // Tính toán tổng tiền
            $subtotal = 0;
            $quantityTotal = 0;
            
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    return $this->error("Sản phẩm ID {$item['product_id']} không tồn tại", 400);
                }
                
                $price = $product['price'];
                if (isset($item['variant_combination'])) {
                    // Có thể tính thêm giá từ variant nếu cần
                }
                
                $itemTotal = $price * $item['quantity'];
                $subtotal += $itemTotal;
                $quantityTotal += $item['quantity'];
            }
            
            // Áp dụng coupon nếu có
            $discountAmount = 0;
            if (isset($data['coupon_code']) && $data['coupon_code']) {
                $couponValidation = Coupon::validateCoupon($data['coupon_code'], $subtotal);
                if ($couponValidation['valid']) {
                    $discountAmount = Coupon::calculateDiscount($couponValidation['coupon'], $subtotal);
                    Coupon::incrementUsage($couponValidation['coupon']['id']);
                } else {
                    return $this->error($couponValidation['message'], 400);
                }
            }
            
            // Tính phí ship và thuế
            $shippingFee = $data['shipping_fee'] ?? 0;
            $taxAmount = $data['tax_amount'] ?? 0;
            $totalAmount = $subtotal - $discountAmount + $shippingFee + $taxAmount;
            
            // Tạo đơn hàng
            $orderData = [
                'user_id' => $data['user_id'] ?? null,
                'customer_name' => $data['customer_name'] ?? 'Khách hàng',
                'customer_phone' => $data['customer_phone'] ?? '',
                'customer_email' => $data['customer_email'] ?? null,
                'shipping_address' => $data['shipping_address'] ?? null,
                'item_count' => count($data['items']),
                'quantity_total' => $quantityTotal,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_fee' => $shippingFee,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'order_status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $data['payment_method'] ?? null,
                'note' => $data['note'] ?? null
            ];
            
            $order = Order::createOrder($orderData);
            $orderId = Database::lastInsertId();
            
            // Tạo order items
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                $price = $product['price'];
                
                $itemData = [
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $product['name'],
                    'product_price' => $price,
                    'variant_combination' => isset($item['variant_combination']) 
                        ? json_encode($item['variant_combination']) : null,
                    'quantity' => $item['quantity'],
                    'total_price' => $price * $item['quantity'],
                    'note' => $item['note'] ?? null
                ];
                
                OrderItem::create($itemData);
                
                // Cập nhật số lượng đã bán
                Product::incrementSold($item['product_id'], $item['quantity']);
            }
            
            $order = Order::findWithItems($orderId);
            
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
            $order = Order::find($id);
            if (!$order) {
                return $this->error('Đơn hàng không tồn tại', 404);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['status'])) {
                return $this->error('Thiếu trường status', 400);
            }
            
            Order::updateStatus($id, $data['status'], $data['reason'] ?? null);
            $order = Order::findWithItems($id);
            
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
            
            $stats = Order::getStatistics($startDate, $endDate);
            
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

