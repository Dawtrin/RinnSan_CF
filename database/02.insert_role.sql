USE RinnSanCF;
GO

-- CHÈN DỮ LIỆU VAI TRÒ
IF NOT EXISTS (SELECT * FROM roles WHERE name = 'admin')
BEGIN
    INSERT INTO roles (name, permissions) VALUES 
    ('admin', '["users.create", "users.update", "users.delete", "products.create", "products.update", "products.delete", "orders.view", "orders.update", "orders.delete", "customers.view", "reports.view", "inventory.manage", "settings.manage"]'),
    ('staff', '["orders.view", "orders.update", "products.view", "customers.view", "inventory.view"]'),
    ('customer', '["profile.view", "orders.create", "orders.view_own", "cart.manage"]');
    
    PRINT '✅ Dữ liệu roles đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu roles đã tồn tại';
GO

-- CHỈ KIỂM TRA BẢNG roles (vì users chưa được tạo)
SELECT 'Roles' as table_name, COUNT(*) as record_count FROM roles;
GO