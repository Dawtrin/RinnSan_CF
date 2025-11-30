USE RinnSanCF;
GO

-- CHÈN DỮ LIỆU USER_ADDRESSES
IF NOT EXISTS (SELECT * FROM user_addresses WHERE user_id = 5)
BEGIN
    INSERT INTO user_addresses (user_id, address_line1, address_line2, city, district, ward, is_default) VALUES
    (5, '123 Trần Phú', 'Phường Thạch Thang', 'Đà Nẵng', 'Hải Châu', 'Thạch Thang', 1),
    (5, '78 Nguyễn Văn Thoại', 'Phường Mỹ An', 'Đà Nẵng', 'Ngũ Hành Sơn', 'Mỹ An', 0),
    (6, '234 Võ Nguyên Giáp', 'Phường Mân Thái', 'Đà Nẵng', 'Sơn Trà', 'Mân Thái', 1),
    (6, '67 Tôn Đức Thắng', 'Xã Hòa Phong', 'Đà Nẵng', 'Hòa Vang', 'Hòa Phong', 0),
    (7, '34 Ông Ích Khiêm', 'Phường Phước Mỹ', 'Đà Nẵng', 'Sơn Trà', 'Phước Mỹ', 1);
    
    PRINT '✅ Dữ liệu user_addresses đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu user_addresses đã tồn tại';
GO

-- CHÈN DỮ LIỆU INVENTORY TRANSACTIONS
IF NOT EXISTS (SELECT * FROM inventory_transactions WHERE inventory_id = 1)
BEGIN
    INSERT INTO inventory_transactions (inventory_id, type, quantity, note, reference_type, reference_id, created_by) VALUES
    (1, 'in', 50.000, 'Nhập hàng tháng 1/2024', 'purchase', 1, 1),
    (1, 'out', 2.500, 'Pha 100 ly cafe espresso', 'order', 1, 3),
    (1, 'out', 1.800, 'Pha 70 ly cafe latte', 'order', 2, 3),
    (2, 'in', 100.000, 'Nhập sữa tươi', 'purchase', 2, 1),
    (2, 'out', 15.000, 'Pha cafe và trà sữa', 'order', NULL, 3),
    (3, 'in', 20.000, 'Nhập trà đen', 'purchase', 3, 1),
    (3, 'out', 3.000, 'Pha trà sữa', 'order', NULL, 4),
    (4, 'in', 200.000, 'Nhập bột mì', 'purchase', 4, 1),
    (4, 'out', 25.000, 'Làm bánh mì và bánh ngọt', 'order', NULL, 4);
    
    PRINT '✅ Dữ liệu inventory_transactions đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu inventory_transactions đã tồn tại';
GO

-- CHÈN DỮ LIỆU ACTIVITY LOGS
IF NOT EXISTS (SELECT * FROM activity_logs WHERE user_id = 1)
BEGIN
    INSERT INTO activity_logs (user_id, action, description, ip_address, reference_type, reference_id) VALUES
    (1, 'user.login', 'Đăng nhập hệ thống', '192.168.1.100', 'User', 1),
    (1, 'order.update', 'Cập nhật trạng thái đơn hàng #1', '192.168.1.100', 'Order', 1),
    (3, 'user.login', 'Đăng nhập hệ thống', '192.168.1.101', 'User', 3),
    (3, 'order.create', 'Tạo đơn hàng mới #15', '192.168.1.101', 'Order', 15),
    (4, 'user.login', 'Đăng nhập hệ thống', '192.168.1.102', 'User', 4),
    (4, 'product.update', 'Cập nhật số lượng sản phẩm Cafe Espresso', '192.168.1.102', 'Product', 1),
    (5, 'user.login', 'Đăng nhập tài khoản khách hàng', '192.168.1.103', 'User', 5),
    (5, 'order.create', 'Đặt đơn hàng #1', '192.168.1.103', 'Order', 1),
    (6, 'user.login', 'Đăng nhập tài khoản khách hàng', '192.168.1.104', 'User', 6),
    (6, 'order.create', 'Đặt đơn hàng #2', '192.168.1.104', 'Order', 2);
    
    PRINT '✅ Dữ liệu activity_logs đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu activity_logs đã tồn tại';
GO

-- CẬP NHẬT SỐ LƯỢNG TỒN KHO DỰA TRÊN ĐƠN HÀNG
IF EXISTS (SELECT * FROM orders WHERE order_status = 'completed')
BEGIN
    -- Cập nhật sold_count cho products
    UPDATE p 
    SET p.sold_count = p.sold_count + oi_sum.total_sold,
        p.quantity = CASE WHEN p.track_quantity = 1 THEN p.quantity - oi_sum.total_sold ELSE p.quantity END
    FROM products p
    JOIN (
        SELECT product_id, SUM(quantity) as total_sold
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        WHERE o.order_status = 'completed'
        GROUP BY product_id
    ) oi_sum ON p.id = oi_sum.product_id;
    
    PRINT '✅ Đã cập nhật sold_count và quantity cho products';
END
ELSE
    PRINT 'ℹ️ Không có đơn hàng completed để cập nhật';
GO