-- FILE: 00_run_all.sql
-- CHẠY TOÀN BỘ DATABASE THEO THỨ TỰ

PRINT '🟡 BẮT ĐẦU TẠO DATABASE RINNSAN CAFE...';
GO

-- 1. Tạo bảng
PRINT '📁 1. Đang tạo các bảng...';
:r "C:\Your\Path\To\cr-Table.sql"
GO

-- 2. Chèn dữ liệu theo thứ tự
PRINT '📁 2. Đang chèn roles...';
:r "C:\Your\Path\To\02.insert_role.sql"
GO

PRINT '📁 3. Đang chèn users...';
:r "C:\Your\Path\To\03.insert_users.sql"
GO

PRINT '📁 4. Đang chèn categories...';
:r "C:\Your\Path\To\04.insert_categories.sql"
GO

PRINT '📁 5. Đang chèn products...';
:r "C:\Your\Path\To\05_insert_products.sql"
GO

PRINT '📁 6. Đang chèn variants...';
:r "C:\Your\Path\To\06_insert_variants.sql"
GO

PRINT '📁 7. Đang chèn settings...';
:r "C:\Your\Path\To\07_insert_settings.sql"
GO

PRINT '📁 8. Đang chèn suppliers và inventory...';
:r "C:\Your\Path\To\08_insert_suppliers_inventory.sql"
GO

PRINT '📁 9. Đang chèn coupons...';
:r "C:\Your\Path\To\09_insert_coupons.sql"
GO

PRINT '📁 10. Đang chèn sample orders...';
:r "C:\Your\Path\To\10_insert_sample_orders.sql"
GO

PRINT '📁 11. Đang chèn missing data...';
:r "C:\Your\Path\To\11_insert_missing_data.sql"
GO

PRINT '📁 12. Đang tạo admin views...';
:r "C:\Your\Path\To\12_create_admin_views.sql"
GO

PRINT '✅ HOÀN THÀNH! DATABASE RINNSAN CAFE ĐÃ SẴN SÀNG!';
GO

-- KIỂM TRA TỔNG QUAN
PRINT '📊 KIỂM TRA DỮ LIỆU ĐÃ CHÈN:';
SELECT 
    'Roles' as table_name, COUNT(*) as record_count FROM roles
UNION ALL SELECT 'Users', COUNT(*) FROM users
UNION ALL SELECT 'Categories', COUNT(*) FROM categories
UNION ALL SELECT 'Products', COUNT(*) FROM products
UNION ALL SELECT 'Orders', COUNT(*) FROM orders
UNION ALL SELECT 'Order Items', COUNT(*) FROM order_items
UNION ALL SELECT 'Payments', COUNT(*) FROM payments
UNION ALL SELECT 'User Addresses', COUNT(*) FROM user_addresses
UNION ALL SELECT 'Activity Logs', COUNT(*) FROM activity_logs;
GO