USE RinnSanCF;
GO

-- CHÈN ĐƠN HÀNG MẪU VỚI ĐỊA CHỈ ĐA DẠNG ĐÀ NẴNG
IF NOT EXISTS (SELECT * FROM orders WHERE order_code = 'RINN-2024-001')
BEGIN
    INSERT INTO orders (order_code, user_id, customer_name, customer_phone, customer_email, shipping_address, item_count, quantity_total, subtotal, discount_amount, shipping_fee, total_amount, order_status, payment_status, payment_method, created_at, completed_at) VALUES
    -- === QUẬN HẢI CHÂU ===
    ('RINN-2024-001', 5, 'Nguyễn Văn Minh', '0901234567', 'minh@gmail.com', '123 Trần Phú, Quận Hải Châu, Đà Nẵng', 5, 7, 385000, 0, 15000, 400000, 'completed', 'paid', 'momo', '2024-01-15 08:30:00', '2024-01-15 09:15:00'),
    ('RINN-2024-002', NULL, 'Trần Thị Hương', '0901234572', 'huong@gmail.com', '45 Lê Duẩn, Quận Hải Châu, Đà Nẵng', 3, 4, 165000, 15000, 0, 150000, 'completed', 'paid', 'cash', '2024-01-15 14:20:00', '2024-01-15 15:00:00'),
    ('RINN-2024-003', NULL, 'Lê Văn Tài', '0901234573', 'tai@gmail.com', '78 Hùng Vương, Quận Hải Châu, Đà Nẵng', 4, 5, 220000, 0, 15000, 235000, 'completed', 'paid', 'zalopay', '2024-01-16 10:15:00', '2024-01-16 11:00:00'),

    -- === QUẬN SƠN TRÀ ===
    ('RINN-2024-004', 6, 'Phạm Thị Lan', '0901234568', 'lan@gmail.com', '234 Võ Nguyên Giáp, Quận Sơn Trà, Đà Nẵng', 4, 6, 275000, 20000, 15000, 270000, 'completed', 'paid', 'momo', '2024-01-16 12:30:00', '2024-01-16 13:15:00'),
    ('RINN-2024-005', NULL, 'Hoàng Văn Dũng', '0901234574', 'dung@gmail.com', '56 Hoàng Sa, Quận Sơn Trà, Đà Nẵng', 2, 3, 120000, 0, 15000, 135000, 'completed', 'paid', 'cash', '2024-01-17 09:45:00', '2024-01-17 10:30:00'),
    ('RINN-2024-006', 7, 'Võ Thị Mai', '0901234575', 'mai@gmail.com', '89 Trường Sa, Quận Sơn Trà, Đà Nẵng', 3, 4, 185000, 10000, 0, 175000, 'completed', 'paid', 'card', '2024-01-17 16:20:00', '2024-01-17 17:00:00'),

    -- === QUẬN NGŨ HÀNH SƠN ===
    ('RINN-2024-007', NULL, 'Đặng Văn Hải', '0901234576', 'hai@gmail.com', '12 Trần Đăng Ninh, Quận Ngũ Hành Sơn, Đà Nẵng', 5, 8, 320000, 25000, 15000, 310000, 'completed', 'paid', 'momo', '2024-01-18 11:10:00', '2024-01-18 12:00:00'),
    ('RINN-2024-008', NULL, 'Nguyễn Thị Thu', '0901234577', 'thu@gmail.com', '34 Lê Văn Hiến, Quận Ngũ Hành Sơn, Đà Nẵng', 3, 5, 195000, 0, 15000, 210000, 'completed', 'paid', 'cash', '2024-01-18 15:45:00', '2024-01-18 16:30:00'),
    ('RINN-2024-009', 5, 'Nguyễn Văn Minh', '0901234567', 'minh@gmail.com', '78 Nguyễn Văn Thoại, Quận Ngũ Hành Sơn, Đà Nẵng', 6, 9, 410000, 30000, 0, 380000, 'completed', 'paid', 'zalopay', '2024-01-19 13:20:00', '2024-01-19 14:15:00'),

    -- === HUYỆN HÒA VANG ===
    ('RINN-2024-010', NULL, 'Trần Văn Sơn', '0901234578', 'son@gmail.com', '23 Nguyễn Lương Bằng, Huyện Hòa Vang, Đà Nẵng', 4, 6, 240000, 0, 20000, 260000, 'completed', 'paid', 'momo', '2024-01-19 17:30:00', '2024-01-19 18:30:00'),
    ('RINN-2024-011', NULL, 'Lê Thị Nga', '0901234579', 'nga@gmail.com', '45 Điện Biên Phủ, Huyện Hòa Vang, Đà Nẵng', 2, 3, 110000, 10000, 20000, 120000, 'completed', 'paid', 'cash', '2024-01-20 08:15:00', '2024-01-20 09:15:00'),
    ('RINN-2024-012', 6, 'Phạm Thị Lan', '0901234568', 'lan@gmail.com', '67 Tôn Đức Thắng, Huyện Hòa Vang, Đà Nẵng', 3, 4, 175000, 0, 20000, 195000, 'completed', 'paid', 'card', '2024-01-20 14:40:00', '2024-01-20 15:40:00'),

    -- === ĐƠN HỦY & ĐANG XỬ LÝ ===
    ('RINN-2024-013', NULL, 'Võ Văn Tú', '0901234580', 'tu@gmail.com', '89 Lê Đình Lý, Quận Hải Châu, Đà Nẵng', 3, 4, 180000, 0, 15000, 195000, 'cancelled', 'refunded', 'card', '2024-01-21 11:20:00', NULL),
    ('RINN-2024-014', NULL, 'Hoàng Thị Hằng', '0901234581', 'hang@gmail.com', '56 Phan Châu Trinh, Quận Hải Châu, Đà Nẵng', 2, 2, 90000, 0, 15000, 105000, 'processing', 'pending', 'cash', '2024-01-21 16:35:00', NULL),
    ('RINN-2024-015', 7, 'Lê Văn Hùng', '0901234569', 'hung@gmail.com', '34 Ông Ích Khiêm, Quận Sơn Trà, Đà Nẵng', 4, 5, 210000, 15000, 15000, 210000, 'ready', 'paid', 'momo', '2024-01-22 10:10:00', NULL);

    -- === CHI TIẾT ĐƠN HÀNG ===
    -- Đơn 1: Hải Châu
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (1, 1, 'Cafe Espresso', 40000, '{"size": "Lớn"}', 2, 80000),
    (1, 11, 'Tiramisu', 65000, NULL, 1, 65000),
    (1, 15, 'Trà sữa trân châu', 60000, '{"size": "Vừa", "topping": "Trân châu đen"}', 2, 136000),
    (1, 20, 'Croissant', 25000, NULL, 2, 50000);

    -- Đơn 2: Hải Châu
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (2, 2, 'Cafe Latte', 50000, '{"size": "Vừa", "đường": "Vừa"}', 1, 50000),
    (2, 16, 'Trà đào cam sả', 50000, '{"size": "Lớn"}', 1, 55000),
    (2, 25, 'Bánh mì que', 15000, NULL, 2, 30000);

    -- Đơn 3: Hải Châu
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (3, 5, 'Cold Brew', 55000, NULL, 2, 110000),
    (3, 12, 'Cheesecake dâu', 55000, NULL, 1, 55000),
    (3, 21, 'Chocolate đá xay', 60000, '{"size": "Lớn"}', 1, 65000),
    (3, 30, 'Bánh mì baguette', 18000, NULL, 1, 18000);

    -- Đơn 4: Sơn Trà
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (4, 6, 'Vietnamese Coconut Coffee', 60000, NULL, 2, 120000),
    (4, 13, 'Macaron', 40000, NULL, 3, 120000),
    (4, 22, 'Matcha đá xay', 55000, '{"size": "Vừa"}', 1, 55000);

    -- Đơn 5: Sơn Trà
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (5, 3, 'Cappuccino', 50000, '{"size": "Nhỏ"}', 1, 35000),
    (5, 26, 'Bánh mì gối', 20000, NULL, 2, 40000);

    -- Đơn 6: Sơn Trà
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (6, 7, 'Hazelnut Latte', 55000, '{"size": "Vừa"}', 1, 50000),
    (6, 14, 'Red Velvet', 75000, NULL, 1, 70000),
    (6, 23, 'Caramel đá xay', 60000, '{"size": "Lớn"}', 1, 65000);

    -- Đơn 7: Ngũ Hành Sơn
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (7, 8, 'Caramel Macchiato', 55000, NULL, 3, 165000),
    (7, 17, 'Trà sen vàng', 65000, NULL, 2, 130000),
    (7, 24, 'Cookies & Cream', 60000, '{"size": "Vừa"}', 2, 110000),
    (7, 31, 'Bánh mì ngọt', 12000, NULL, 1, 12000);

    -- Đơn 8: Ngũ Hành Sơn
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (8, 9, 'Mocha', 55000, '{"size": "Lớn"}', 2, 110000),
    (8, 18, 'Trà đen sữa', 45000, NULL, 1, 40000),
    (8, 27, 'Bánh tart trứng', 40000, NULL, 1, 35000);

    -- Đơn 9: Ngũ Hành Sơn
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (9, 10, 'Americano', 45000, NULL, 3, 120000),
    (9, 19, 'Trà xanh matcha latte', 55000, '{"size": "Vừa"}', 2, 100000),
    (9, 28, 'Bánh su', 33000, NULL, 4, 112000),
    (9, 35, 'Nước ép cam', 40000, NULL, 2, 70000);

    -- Đơn 10: Hòa Vang
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (10, 21, 'Chocolate đá xay', 60000, '{"size": "Lớn"}', 2, 130000),
    (10, 29, 'Bánh phô mai chanh leo', 50000, NULL, 1, 45000),
    (10, 36, 'Nước ép táo', 35000, NULL, 2, 60000),
    (10, 40, 'Sinh tố bơ', 45000, NULL, 1, 40000);

    -- Đơn 11: Hòa Vang
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (11, 4, 'Cold Brew', 55000, NULL, 1, 50000),
    (11, 25, 'Bánh mì que', 15000, NULL, 3, 45000);

    -- Đơn 12: Hòa Vang
    INSERT INTO order_items (order_id, product_id, product_name, product_price, variant_combination, quantity, total_price) VALUES
    (12, 2, 'Cafe Latte', 50000, '{"size": "Vừa"}', 2, 90000),
    (12, 13, 'Macaron', 40000, NULL, 1, 35000),
    (12, 37, 'Nước ép dưa hấu', 37000, NULL, 1, 32000);

    -- === THANH TOÁN ===
    INSERT INTO payments (order_id, payment_method, amount, payment_status, paid_at) VALUES
    (1, 'momo', 400000, 'success', '2024-01-15 08:35:00'),
    (2, 'cash', 150000, 'success', '2024-01-15 14:25:00'),
    (3, 'zalopay', 235000, 'success', '2024-01-16 10:20:00'),
    (4, 'momo', 270000, 'success', '2024-01-16 12:35:00'),
    (5, 'cash', 135000, 'success', '2024-01-17 09:50:00'),
    (6, 'card', 175000, 'success', '2024-01-17 16:25:00'),
    (7, 'momo', 310000, 'success', '2024-01-18 11:15:00'),
    (8, 'cash', 210000, 'success', '2024-01-18 15:50:00'),
    (9, 'zalopay', 380000, 'success', '2024-01-19 13:25:00'),
    (10, 'momo', 260000, 'success', '2024-01-19 17:35:00'),
    (11, 'cash', 120000, 'success', '2024-01-20 08:20:00'),
    (12, 'card', 195000, 'success', '2024-01-20 14:45:00'),
    (13, 'card', 195000, 'refunded', '2024-01-21 12:00:00'),
    (15, 'momo', 210000, 'success', '2024-01-22 10:15:00');
    
    PRINT '✅ Dữ liệu orders mẫu đã được chèn - 15 đơn hàng đa dạng địa chỉ Đà Nẵng';
END
ELSE
    PRINT 'ℹ️ Dữ liệu orders đã tồn tại';
GO