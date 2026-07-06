<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Core\Database;

class OrderController extends ApiController
{
    /**
     * Tạo đơn hàng mới + TỰ ĐỘNG TẠO TÀI KHOẢN & TRỪ KHO
     * POST /api/orders
     */
    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // 1. Kiểm tra giỏ hàng
            if (!$data || !isset($data['items']) || empty($data['items'])) {
                return $this->error('Giỏ hàng trống, không thể đặt hàng', 400);
            }

            // --- [TÍNH NĂNG MỚI] TỰ ĐỘNG TẠO TÀI KHOẢN (SILENT REGISTER) ---
            $userId = $data['user_id'] ?? null;
            $customerPhone = $data['customer_phone'] ?? '';
            $customerName = $data['customer_name'] ?? 'Khách lẻ';
            $customerEmail = $data['customer_email'] ?? ''; // Email khách nhập (nếu có)

            // Nếu chưa đăng nhập (userId rỗng) nhưng có nhập SĐT
            if (empty($userId) && !empty($customerPhone)) {
                // B1: Kiểm tra SĐT này đã có tài khoản chưa
                $existingUser = Database::fetch("SELECT id FROM users WHERE phone = ?", [$customerPhone]);

                if ($existingUser) {
                    // => Đã có tài khoản: Gán đơn hàng vào ID cũ để tích điểm
                    $userId = $existingUser['id'];
                } else {
                    // => Chưa có: Tạo tài khoản mới âm thầm
                    try {
                        $fakeUsername = $customerPhone; // Username là SĐT
                        // Nếu không nhập email, tạo email ảo để không lỗi CSDL
                        $emailToUse = !empty($customerEmail) ? $customerEmail : ($customerPhone . '@guest.rinnsan.com');
                        $defaultPass = password_hash('123456', PASSWORD_DEFAULT); // Pass mặc định: 123456

                        // Kiểm tra email có trùng không trước khi tạo
                        $checkEmail = Database::fetch("SELECT id FROM users WHERE email = ?", [$emailToUse]);
                        
                        if (!$checkEmail) {
                            $sqlUser = "INSERT INTO users (username, email, password, full_name, phone, role_id, is_active, created_at, updated_at) 
                                        VALUES (?, ?, ?, ?, ?, 3, 1, GETDATE(), GETDATE())"; // Role 3 = Khách hàng
                            
                            Database::query($sqlUser, [
                                $fakeUsername, 
                                $emailToUse, 
                                $defaultPass, 
                                $customerName, 
                                $customerPhone
                            ]);

                            // Lấy ID user vừa tạo
                            $newUserRes = Database::fetch("SELECT CAST(@@IDENTITY AS INT) as id");
                            $userId = $newUserRes['id'] ?? null;
                            
                            // Fallback tìm lại nếu @@IDENTITY lỗi
                            if (!$userId) {
                                $u = Database::fetch("SELECT id FROM users WHERE phone = ?", [$customerPhone]);
                                $userId = $u['id'] ?? null;
                            }
                        }
                    } catch (\Exception $ex) {
                        // Nếu tạo lỗi (ví dụ trùng lặp hiếm gặp), chấp nhận để là Khách vãng lai (NULL)
                        // Để không chặn việc đặt hàng của khách
                        $userId = null;
                    }
                }
            }
            // -------------------------------------------------------------------

            // 2. Build items & Tính tiền
            $buildItems = [];
            $subtotal = 0;
            $totalQuantity = 0;

            foreach ($data['items'] as $item) {
                $prodId = $item['product_id'] ?? null;
                if (!$prodId) continue;

                $product = Database::fetch("SELECT id, name, price, quantity FROM products WHERE id = ?", [$prodId]);
                
                if ($product) {
                    $qty = (int)($item['quantity'] ?? 1);
                    $price = (float)$product['price'];
                    $total = $price * $qty;
                    
                    $variant = isset($item['options']) ? json_encode($item['options'], JSON_UNESCAPED_UNICODE) : '';

                    $buildItems[] = [
                        'product_id' => $prodId,
                        'product_name' => $product['name'],
                        'product_price' => $price,
                        'variant_combination' => $variant,
                        'quantity' => $qty,
                        'total_price' => $total,
                        'note' => $item['note'] ?? ''
                    ];

                    $subtotal += $total;
                    $totalQuantity += $qty;
                }
            }

            if (empty($buildItems)) return $this->error('Sản phẩm không hợp lệ', 400);

            // 3. Tính phí & Tổng cộng
            $shippingFee = (float)($data['shipping_fee'] ?? 0);
            $discountAmount = (float)($data['discount_amount'] ?? 0);
            $totalAmount = max(0, $subtotal + $shippingFee - $discountAmount);

            // 4. Tạo Mã Đơn Hàng
            $prefix = 'CAFE-' . date('Ymd') . '-';
            try {
                $countRes = Database::fetch("SELECT COUNT(*) as c FROM orders WHERE order_code LIKE ?", ["$prefix%"]);
                $nextId = ($countRes['c'] ?? 0) + 1;
            } catch (\Exception $e) { $nextId = 1; }
            $orderCode = $prefix . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            // 5. INSERT Đơn Hàng (Sử dụng $userId đã xử lý ở trên)
            $sqlOrder = "INSERT INTO orders (
                order_code, user_id, customer_name, customer_phone, customer_email,
                shipping_address, note, item_count, quantity_total, subtotal,
                discount_amount, shipping_fee, tax_amount, total_amount,
                order_status, payment_status, payment_method, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, GETDATE(), GETDATE())";

            $note = $data['note'] ?? '';
            if (!empty($data['coupon_code'])) {
                $note .= " [Voucher: {$data['coupon_code']}]";
            }

            Database::query($sqlOrder, [
                $orderCode,
                $userId, // <--- ID user (Cũ hoặc Mới hoặc Null)
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

            // 6. Lấy ID Order
            $idRes = Database::fetch("SELECT CAST(@@IDENTITY AS INT) as id");
            $orderId = $idRes['id'] ?? null;
            if (!$orderId) {
                $findOrder = Database::fetch("SELECT id FROM orders WHERE order_code = ?", [$orderCode]);
                $orderId = $findOrder['id'];
            }

            // 7. INSERT Items & TRỪ TỒN KHO
            foreach ($buildItems as $item) {
                $sqlItem = "INSERT INTO order_items (
                    order_id, product_id, product_name, product_price,
                    variant_combination, quantity, total_price, note, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, GETDATE())";

                Database::query($sqlItem, [
                    $orderId, $item['product_id'], $item['product_name'], $item['product_price'],
                    $item['variant_combination'], $item['quantity'], $item['total_price'], $item['note']
                ]);

                // Update Kho (Xử lý NULL)
                $qty = $item['quantity'];
                $prodId = $item['product_id'];
                $sqlUpdate = "UPDATE products 
                              SET quantity = COALESCE(quantity, 0) - ?, 
                                  sold_count = COALESCE(sold_count, 0) + ?, 
                                  updated_at = GETDATE() 
                              WHERE id = ?";
                Database::query($sqlUpdate, [$qty, $qty, $prodId]);
            }

            // --- [FIX LỖI] CẬP NHẬT SỐ LẦN DÙNG VOUCHER ---
            if (!empty($data['coupon_code'])) {
                Database::query("UPDATE coupons SET used_count = COALESCE(used_count, 0) + 1 WHERE code = ?", [$data['coupon_code']]);
            }

            return $this->success([
                'id' => $orderId,
                'order_code' => $orderCode,
                'total_amount' => $totalAmount
            ], 'Đặt hàng thành công', 201);

        } catch (\Exception $e) {
            return $this->error("Lỗi Server: " . $e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật trạng thái (Cộng điểm tích lũy & Hoàn kho & Tự động cập nhật thanh toán)
     */
    public function updateStatus($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $newStatus = $data['status'] ?? null;

            if (!$newStatus) return $this->error('Thiếu trạng thái', 400);

            // Lấy thêm payment_method để xử lý logic thanh toán
            $order = Database::fetch("SELECT id, user_id, total_amount, order_status, payment_method FROM orders WHERE id = ?", [$id]);
            if (!$order) return $this->error('Đơn hàng không tồn tại', 404);

            // [LOGIC 1] CỘNG ĐIỂM KHI HOÀN THÀNH (10.000đ = 1 điểm)
            if ($newStatus === 'completed' && $order['order_status'] !== 'completed' && !empty($order['user_id'])) {
                $points = floor($order['total_amount'] / 10000); 
                try {
                    Database::query("UPDATE users SET loyalty_points = COALESCE(loyalty_points, 0) + ? WHERE id = ?", 
                        [$points, $order['user_id']]
                    );
                } catch (\Exception $e) {}
            }

            // [LOGIC 2] Hoàn kho nếu hủy đơn
            if ($newStatus === 'cancelled' && $order['order_status'] !== 'cancelled') {
                $items = Database::fetchAll("SELECT product_id, quantity FROM order_items WHERE order_id = ?", [$id]);
                foreach ($items as $item) {
                    Database::query("UPDATE products 
                                     SET quantity = COALESCE(quantity, 0) + ?, 
                                         sold_count = COALESCE(sold_count, 0) - ? 
                                     WHERE id = ?", 
                                     [$item['quantity'], $item['quantity'], $item['product_id']]);
                }
            }

            // [LOGIC 3] Tự động cập nhật trạng thái thanh toán
            // Vì bạn đã thêm cột paid_at vào DB nên code này sẽ chạy đúng
            $paymentUpdateSql = "";

            if ($newStatus === 'completed') {
                // Hoàn thành -> Tiền đã vào túi (dù Cash hay Bank) -> PAID
                $paymentUpdateSql = ", payment_status = 'paid', completed_at = GETDATE(), paid_at = GETDATE()";
            } 
            elseif ($newStatus === 'shipping') {
                // Đang giao:
                // - Nếu là Banking/Transfer -> Đã nhận tiền trước -> PAID
                // - Nếu là Cash (COD) -> Chưa nhận tiền -> Giữ nguyên
                if ($order['payment_method'] === 'banking' || $order['payment_method'] === 'transfer') {
                    $paymentUpdateSql = ", payment_status = 'paid', paid_at = GETDATE()";
                }
            }

            // Thực thi Update
            $sqlFinal = "UPDATE orders SET order_status = ?, updated_at = GETDATE() $paymentUpdateSql WHERE id = ?";
            Database::query($sqlFinal, [$newStatus, $id]);

            return $this->success([], 'Cập nhật trạng thái thành công');

        } catch (\Exception $e) {
            return $this->error("Lỗi cập nhật: " . $e->getMessage(), 500);
        }
    }

    /**
     * Sửa chi tiết đơn hàng (Logic cũ vẫn giữ nguyên)
     */
    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) return $this->error('Dữ liệu không hợp lệ', 400);

            $oldOrder = Database::fetch("SELECT id FROM orders WHERE id = ?", [$id]);
            if (!$oldOrder) return $this->error('Đơn hàng không tồn tại', 404);

            // 1. Hoàn kho món cũ
            $oldItems = Database::fetchAll("SELECT product_id, quantity FROM order_items WHERE order_id = ?", [$id]);
            foreach ($oldItems as $item) {
                Database::query("UPDATE products SET quantity = COALESCE(quantity, 0) + ?, sold_count = COALESCE(sold_count, 0) - ? WHERE id = ?", 
                    [$item['quantity'], $item['quantity'], $item['product_id']]);
            }

            // 2. Xóa món cũ
            Database::query("DELETE FROM order_items WHERE order_id = ?", [$id]);

            // 3. Thêm món mới
            $newItems = $data['items'] ?? [];
            $subtotal = 0;
            $totalQuantity = 0;

            foreach ($newItems as $item) {
                $prodId = $item['product_id'];
                $product = Database::fetch("SELECT name, price FROM products WHERE id = ?", [$prodId]);
                
                if ($product) {
                    $qty = (int)$item['quantity'];
                    $price = (float)$product['price'];
                    $total = $price * $qty;
                    $variant = is_array($item['options'] ?? []) ? json_encode($item['options']) : ($item['options'] ?? '');

                    Database::query("INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, GETDATE())", 
                        [$id, $prodId, $product['name'], $price, $variant, $qty, $total]);

                    // Trừ kho lại
                    Database::query("UPDATE products SET quantity = COALESCE(quantity, 0) - ?, sold_count = COALESCE(sold_count, 0) + ? WHERE id = ?", 
                        [$qty, $qty, $prodId]);

                    $subtotal += $total;
                    $totalQuantity += $qty;
                }
            }

            // 4. Cập nhật thông tin đơn
            $shippingFee = $data['shipping_fee'] ?? 0;
            $discount = $data['discount_amount'] ?? 0;
            $totalAmount = max(0, $subtotal + $shippingFee - $discount);

            $sqlUpdateOrder = "UPDATE orders SET 
                                customer_name = ?, customer_phone = ?, shipping_address = ?,
                                subtotal = ?, total_amount = ?, quantity_total = ?, item_count = ?,
                                updated_at = GETDATE()
                                WHERE id = ?";
            
            Database::query($sqlUpdateOrder, [
                $data['customer_name'] ?? '',
                $data['customer_phone'] ?? '',
                $data['shipping_address'] ?? '',
                $subtotal, $totalAmount, $totalQuantity, count($newItems),
                $id
            ]);

            return $this->success([], 'Cập nhật đơn hàng thành công');

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function index()
    {
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
            $userId = $_GET['user_id'] ?? null;
            $status = $_GET['status'] ?? null;
            $sql = "SELECT TOP $limit o.*, COALESCE(u.full_name, o.customer_name, N'Khách vãng lai') as customer_display_name 
                    FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE 1=1";
            $params = [];
            if ($userId) { $sql .= " AND o.user_id = ?"; $params[] = $userId; }
            if ($status && $status !== 'all') { $sql .= " AND o.order_status = ?"; $params[] = $status; }
            $sql .= " ORDER BY o.created_at DESC";
            return $this->success(Database::fetchAll($sql, $params));
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    public function show($id)
    {
        try {
            $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$id]);
            if (!$order) return $this->error('Not Found', 404);
            
            $items = Database::fetchAll("
                SELECT oi.*, COALESCE(p.name, oi.product_name) as current_product_name, p.images 
                FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = ?", [$id]);
                
            foreach ($items as &$item) {
                if (!empty($item['images'])) {
                    $decoded = json_decode($item['images'], true);
                    $item['image'] = is_array($decoded) ? $decoded[0] : $item['images'];
                }
            }
            $order['items'] = $items;
            return $this->success($order);
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    public function adminDestroy($id)
    {
        try {
            $items = Database::fetchAll("SELECT product_id, quantity FROM order_items WHERE order_id = ?", [$id]);
            foreach ($items as $item) {
                Database::query("UPDATE products SET quantity = COALESCE(quantity, 0) + ?, sold_count = COALESCE(sold_count, 0) - ? WHERE id = ?", 
                    [$item['quantity'], $item['quantity'], $item['product_id']]);
            }
            Database::query("DELETE FROM order_items WHERE order_id = ?", [$id]);
            Database::query("DELETE FROM orders WHERE id = ?", [$id]);
            return $this->success([], 'Xóa thành công');
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }
}