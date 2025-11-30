<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'order_id', 'payment_method', 'amount', 'transaction_id',
        'payment_status', 'payment_data', 'paid_at'
    ];

    /**
     * Lấy thanh toán theo order_id
     */
    public static function getByOrderId($orderId)
    {
        $sql = "SELECT * FROM payments WHERE order_id = ? ORDER BY created_at DESC";
        return Database::fetchAll($sql, [$orderId]);
    }

    /**
     * Tạo thanh toán mới
     */
    public static function createPayment($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        if ($data['payment_status'] === 'success') {
            $data['paid_at'] = date('Y-m-d H:i:s');
        }
        return self::create($data);
    }

    /**
     * Cập nhật trạng thái thanh toán
     */
    public static function updatePaymentStatus($id, $status, $transactionId = null)
    {
        $data = [
            'payment_status' => $status
        ];
        
        if ($status === 'success') {
            $data['paid_at'] = date('Y-m-d H:i:s');
        }
        
        if ($transactionId) {
            $data['transaction_id'] = $transactionId;
        }
        
        return self::update($id, $data);
    }

    /**
     * Lấy thanh toán theo transaction_id
     */
    public static function findByTransactionId($transactionId)
    {
        $sql = "SELECT * FROM payments WHERE transaction_id = ?";
        return Database::fetch($sql, [$transactionId]);
    }
}

