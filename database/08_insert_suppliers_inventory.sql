USE RinnSanCF;
GO

-- CHÈN NHÀ CUNG CẤP
IF NOT EXISTS (SELECT * FROM suppliers WHERE name = 'Cung cấp cafe Trung Nguyên')
BEGIN
    INSERT INTO suppliers (name, contact_person, phone, email, address) VALUES
    ('Cung cấp cafe Trung Nguyên', 'Ông Minh', '0909123456', 'minh@trungnguyen.com', '123 Lý Thường Kiệt, Quận 10, TP.HCM'),
    ('Công ty sữa Vinamilk', 'Bà Lan', '0909123457', 'lan@vinamilk.com', '456 Nguyễn Thị Minh Khai, Quận 3, TP.HCM'),
    ('Cung cấp trà Lipton', 'Anh Tuấn', '0909123458', 'tuan@lipton.com', '789 Lê Lợi, Quận 1, TP.HCM'),
    ('Cung cấp bột mì Kinh Đô', 'Chị Hương', '0909123459', 'huong@kinhdo.com', '321 Hai Bà Trưng, Quận 1, TP.HCM');
    
    PRINT '✅ Dữ liệu suppliers đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu suppliers đã tồn tại';
GO

-- CHÈN KHO NGUYÊN LIỆU
IF NOT EXISTS (SELECT * FROM inventory WHERE name = 'Cafe Arabica')
BEGIN
    INSERT INTO inventory (name, sku, unit, current_quantity, min_quantity, cost_price, supplier_id) VALUES
    ('Cafe Arabica hạt', 'CAFE-ARAB-1KG', 'kg', 25.5, 5.0, 250000, 1),
    ('Sữa tươi Vinamilk', 'SUA-TUOI-1L', 'liter', 50.0, 10.0, 25000, 2),
    ('Trà đen Lipton', 'TRA-DEN-500G', 'gram', 15000.0, 2000.0, 150000, 3),
    ('Bột mì số 8', 'BOT-MI-25KG', 'kg', 100.0, 20.0, 300000, 4),
    ('Đường trắng', 'DUONG-TRANG-1KG', 'kg', 30.0, 5.0, 20000, 4),
    ('Trân châu đen', 'TRAN-CHAU-1KG', 'kg', 15.0, 3.0, 45000, 3);
    
    PRINT '✅ Dữ liệu inventory đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu inventory đã tồn tại';
GO