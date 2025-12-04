<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Payment;
use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Core\Database;

class PaymentService extends Service
{
    public function create($data)
    {
        $order = Order::find($data['order_id']);
        if (!$order) {
            return null;
        }
        $paymentData = [
            'order_id' => $data['order_id'],
            'payment_method' => $data['payment_method'] ?? 'cash',
            'amount' => $data['amount'] ?? $order['total_amount'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'payment_status' => $data['payment_status'] ?? 'pending',
            'payment_data' => isset($data['payment_data']) ? json_encode($data['payment_data']) : null
        ];
        Payment::createPayment($paymentData);
        $paymentId = Database::lastInsertId();
        Order::update($data['order_id'], ['payment_status' => $paymentData['payment_status']]);
        return Payment::find($paymentId);
    }

    public function updateStatus($id, $status, $transactionId = null)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return null;
        }
        Payment::updatePaymentStatus($id, $status, $transactionId);
        Order::update($payment['order_id'], ['payment_status' => $status]);
        return Payment::find($id);
    }

    public function getByOrderId($orderId)
    {
        return Payment::getByOrderId($orderId);
    }
}

