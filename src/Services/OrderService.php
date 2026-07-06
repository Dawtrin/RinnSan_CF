<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Core\Database;

class OrderService
{
    public function create($data)
    {
        try {
            // 1. Tính toán lại tiền (Bảo mật)
            $buildItems = [];
            $subtotal = 0;
            $totalQuantity = 0;

            foreach ($data['items'] as $item) {
                $prodId = $item['product_id'] ?? null;
                if (!$prodId) continue;

                // Lấy giá từ DB
                $product = Database::fetch("SELECT id, name, price FROM products WHERE id = ?", [$prodId]);
                
                if ($product) {
                    $qty = (int)($item['quantity'] ?? 1);
                    $price = (float)$product['price'];
                    $total = $price * $qty;
                    
                    $variant = $item['options'] ?? [];
                    $variantStr = is_string($variant) ? $variant : json_encode($variant, JSON_UNESCAPED_UNICODE);

                    $buildItems[] = [
                        'product_id' => $prodId,
                        'product_name' => $product['name'],
                        'product_price' => $price,
                        'variant_combination' => $variantStr,
                        'quantity' => $qty,
                        'total_price' => $total,
                        'note' => $item['note'] ?? ''
                    ];

                    $subtotal += $total;
                    $totalQuantity += $qty;
                }
            }

            if (empty($buildItems)) throw new \Exception("Không có sản phẩm hợp lệ");

            // 2. Tính tổng tiền cuối
            $shippingFee = (float)($data['shipping_fee'] ?? 0);
            $discountAmount = (float)($data['discount_amount'] ?? 0);
            $totalAmount = max(0, $subtotal + $shippingFee - $discountAmount);

            // 3. Tạo Mã Đơn Hàng
            $prefix = 'CAFE-' . date('Ymd') . '-';
            $countRes = Database::fetch("SELECT COUNT(*) as c FROM orders WHERE order_code LIKE ?", ["$prefix%"]);
            $nextId = ($countRes['c'] ?? 0) + 1;
            $orderCode = $prefix . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            // 4. INSERT Đơn Hàng
            $sqlOrder = "INSERT INTO orders (
                order_code, user_id, customer_name, customer_phone, customer_email,
                shipping_address, note, item_count, quantity_total, subtotal,
                discount_amount, shipping_fee, tax_amount, total_amount,
                order_status, payment_status, payment_method, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE(), GETDATE())";

            $customerName = $data['customer_name'] ?? 'Khách lẻ';
            $customerPhone = $data['customer_phone'] ?? '';
            $note = $data['note'] ?? '';
            if (!empty($data['coupon_code'])) $note .= " [Voucher: {$data['coupon_code']}]";

            Database::query($sqlOrder, [
                $orderCode,
                $data['user_id'] ?? null,
                $customerName,
                $customerPhone,
                $data['customer_email'] ?? '',
                $data['shipping_address'] ?? '',
                $note,
                count($buildItems),
                $totalQuantity,
                $subtotal,
                $discountAmount,
                $shippingFee,
                0,
                $totalAmount,
                'pending',
                'unpaid',
                $data['payment_method'] ?? 'cash'
            ]);

            // 5. [FIX LỖI] LẤY ID VỪA TẠO (SQL SERVER)
            // Thay vì dùng lastInsertId() (bị lỗi), ta dùng query trực tiếp
            $lastIdRow = Database::fetch("SELECT CAST(@@IDENTITY AS INT) as id"); // Hoặc SCOPE_IDENTITY()
            $orderId = $lastIdRow['id'] ?? null;

            // Fallback: Nếu không lấy được ID, tìm lại bằng order_code
            if (!$orderId) {
                $findOrder = Database::fetch("SELECT id FROM orders WHERE order_code = ?", [$orderCode]);
                $orderId = $findOrder['id'] ?? null;
            }

            if (!$orderId) throw new \Exception("Không thể lấy ID đơn hàng");

            // 6. INSERT Chi Tiết Đơn Hàng
            foreach ($buildItems as $item) {
                $sqlItem = "INSERT INTO order_items (
                    order_id, product_id, product_name, product_price,
                    variant_combination, quantity, total_price, note, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, GETDATE())";

                Database::query($sqlItem, [
                    $orderId,
                    $item['product_id'],
                    $item['product_name'],
                    $item['product_price'],
                    $item['variant_combination'],
                    $item['quantity'],
                    $item['total_price'],
                    $item['note']
                ]);
            }

            return [
                'id' => $orderId,
                'order_code' => $orderCode,
                'total_amount' => $totalAmount
            ];

        } catch (\Exception $e) {
            // Ghi log lỗi để debug
            error_log("OrderService Error: " . $e->getMessage());
            throw $e;
        }
    }
}