<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Order;
use Rinnsan\RinnSanWeb\Models\OrderItem;
use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Core\Database;

class OrderService extends Service
{
    public function create($payload)
    {
        $userId = $payload['user_id'] ?? null;
        $items = $payload['items'] ?? [];
        $customer = $payload['customer'] ?? [];
        $shippingFee = $payload['shipping_fee'] ?? 0;
        $discountAmount = $payload['discount_amount'] ?? 0;
        $taxRate = (float)($_ENV['TAX_RATE'] ?? 0);
        if (empty($items)) {
            return null;
        }
        $buildItems = [];
        $subtotal = 0;
        $totalQuantity = 0;
        foreach ($items as $item) {
            $productId = $item['product_id'];
            $quantity = (int)$item['quantity'];
            $note = $item['note'] ?? null;
            $variant = $item['variant_combination'] ?? null;
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }
            $price = (float)$product['price'];
            $total = $price * $quantity;
            $buildItems[] = [
                'product_id' => $productId,
                'product_name' => $product['name'],
                'product_price' => $price,
                'variant_combination' => is_array($variant) ? json_encode($variant) : $variant,
                'quantity' => $quantity,
                'total_price' => $total,
                'note' => $note,
            ];
            $subtotal += $total;
            $totalQuantity += $quantity;
        }
        $taxAmount = $taxRate > 0 ? round($subtotal * $taxRate, 2) : 0;
        $totalAmount = $subtotal - $discountAmount + $shippingFee + $taxAmount;
        $orderData = [
            'user_id' => $userId,
            'customer_name' => $customer['name'] ?? null,
            'customer_phone' => $customer['phone'] ?? null,
            'customer_email' => $customer['email'] ?? null,
            'shipping_address' => $customer['shipping_address'] ?? null,
            'item_count' => count($buildItems),
            'quantity_total' => $totalQuantity,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'shipping_fee' => $shippingFee,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'order_status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $payload['payment_method'] ?? null,
        ];
        Order::createOrder($orderData);
        $orderId = Database::lastInsertId();
        OrderItem::createMultiple($orderId, $buildItems);
        foreach ($buildItems as $item) {
            $p = Product::find($item['product_id']);
            if ($p && isset($p['quantity'])) {
                $newQty = max(0, (int)$p['quantity'] - (int)$item['quantity']);
                Product::updateQuantity($item['product_id'], $newQty);
            }
            Product::incrementSold($item['product_id'], (int)$item['quantity']);
        }
        return Order::findWithItems($orderId);
    }

    public function updateStatus($orderId, $status, $reason = null)
    {
        Order::updateStatus($orderId, $status, $reason);
        return Order::findWithItems($orderId);
    }

    public function getByUser($userId, $limit = 20)
    {
        return Order::getByUserId($userId, $limit);
    }

    public function getByStatus($status, $limit = 50)
    {
        return Order::getByStatus($status, $limit);
    }

    public function statistics($startDate = null, $endDate = null)
    {
        return Order::getStatistics($startDate, $endDate);
    }
}

