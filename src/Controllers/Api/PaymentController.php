<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Payment;
use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Core\Database;

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
            
            $order = Order::find($data['order_id']);
            if (!$order) {
                return $this->error('Đơn hàng không tồn tại', 404);
            }
            
            $paymentData = [
                'order_id' => $data['order_id'],
                'payment_method' => $data['payment_method'] ?? 'cash',
                'amount' => $data['amount'] ?? $order['total_amount'],
                'transaction_id' => $data['transaction_id'] ?? null,
                'payment_status' => $data['payment_status'] ?? 'pending',
                'payment_data' => isset($data['payment_data']) ? json_encode($data['payment_data']) : null
            ];
            
            $payment = Payment::createPayment($paymentData);
            $paymentId = Database::lastInsertId();
            
            // Cập nhật trạng thái thanh toán của đơn hàng
            Order::update($data['order_id'], [
                'payment_status' => $paymentData['payment_status']
            ]);
            
            $payment = Payment::find($paymentId);
            
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
            $payment = Payment::find($id);
            if (!$payment) {
                return $this->error('Thanh toán không tồn tại', 404);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['status'])) {
                return $this->error('Thiếu trường status', 400);
            }
            
            Payment::updatePaymentStatus($id, $data['status'], $data['transaction_id'] ?? null);
            
            // Cập nhật trạng thái thanh toán của đơn hàng
            Order::update($payment['order_id'], [
                'payment_status' => $data['status']
            ]);
            
            $payment = Payment::find($id);
            
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
            $payments = Payment::getByOrderId($orderId);
            
            return $this->success($payments, 'Lấy danh sách thanh toán thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

