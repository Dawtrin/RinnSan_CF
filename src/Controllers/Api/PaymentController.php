<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Payment;
use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Services\PaymentService;

class PaymentController extends ApiController
{
    /**
     * Tạo thanh toán
     * POST /api/payments
     */
    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || !isset($data['order_id'])) {
                return $this->error('Thiếu order_id', 400);
            }
            $service = new PaymentService();
            $payment = $service->create($data);
            if (!$payment) {
                return $this->error('Đơn hàng không tồn tại', 404);
            }
            return $this->success($payment, 'Tạo thanh toán thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật trạng thái thanh toán
     * PUT /api/payments/{id}/status
     */
    public function updateStatus($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['status'])) {
                return $this->error('Thiếu trường status', 400);
            }
            $service = new PaymentService();
            $payment = $service->updateStatus($id, $data['status'], $data['transaction_id'] ?? null);
            if (!$payment) {
                return $this->error('Thanh toán không tồn tại', 404);
            }
            return $this->success($payment, 'Cập nhật trạng thái thanh toán thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy thanh toán theo order_id
     * GET /api/payments/order/{orderId}
     */
    public function getByOrder($orderId)
    {
        try {
            $service = new PaymentService();
            $payments = $service->getByOrderId($orderId);
            
            return $this->success($payments, 'Lấy danh sách thanh toán thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

