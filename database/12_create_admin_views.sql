USE RinnSanCF;
GO

-- TẠO CÁC VIEW CHO ADMIN DASHBOARD
IF EXISTS (SELECT * FROM sys.views WHERE name = 'vw_dashboard_stats')
    DROP VIEW vw_dashboard_stats;
GO

CREATE VIEW vw_dashboard_stats AS
SELECT 
    -- Tổng doanh thu
    (SELECT ISNULL(SUM(total_amount), 0) FROM orders WHERE order_status = 'completed') as total_revenue,
    
    -- Tổng đơn hàng
    (SELECT COUNT(*) FROM orders WHERE order_status = 'completed') as total_orders,
    
    -- Đơn hàng hôm nay
    (SELECT COUNT(*) FROM orders WHERE CAST(created_at AS DATE) = CAST(GETDATE() AS DATE)) as today_orders,
    
    -- Doanh thu hôm nay
    (SELECT ISNULL(SUM(total_amount), 0) FROM orders WHERE CAST(created_at AS DATE) = CAST(GETDATE() AS DATE) AND order_status = 'completed') as today_revenue,
    
    -- Sản phẩm tồn kho thấp
    (SELECT COUNT(*) FROM products WHERE quantity < 10 AND track_quantity = 1) as low_stock_products,
    
    -- Khách hàng mới hôm nay
    (SELECT COUNT(*) FROM users WHERE CAST(created_at AS DATE) = CAST(GETDATE() AS DATE) AND role_id = 3) as new_customers_today;
GO

-- VIEW DOANH THU THEO THÁNG
IF EXISTS (SELECT * FROM sys.views WHERE name = 'vw_monthly_revenue')
    DROP VIEW vw_monthly_revenue;
GO

CREATE VIEW vw_monthly_revenue AS
SELECT 
    YEAR(created_at) as year,
    MONTH(created_at) as month,
    COUNT(*) as total_orders,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as avg_order_value
FROM orders 
WHERE order_status = 'completed'
GROUP BY YEAR(created_at), MONTH(created_at);
GO

-- VIEW TOP 10 SẢN PHẨM BÁN CHẠY
IF EXISTS (SELECT * FROM sys.views WHERE name = 'vw_top_products')
    DROP VIEW vw_top_products;
GO

CREATE VIEW vw_top_products AS
SELECT TOP 10
    p.id,
    p.name as product_name,
    c.name as category_name,
    SUM(oi.quantity) as total_sold,
    SUM(oi.total_price) as total_revenue,
    p.price,
    p.quantity as current_stock
FROM order_items oi
JOIN products p ON oi.product_id = p.id
JOIN categories c ON p.category_id = c.id
JOIN orders o ON oi.order_id = o.id
WHERE o.order_status = 'completed'
GROUP BY p.id, p.name, c.name, p.price, p.quantity
ORDER BY total_sold DESC;
GO

-- VIEW DOANH THU THEO KHU VỰC
IF EXISTS (SELECT * FROM sys.views WHERE name = 'vw_revenue_by_district')
    DROP VIEW vw_revenue_by_district;
GO

CREATE VIEW vw_revenue_by_district AS
SELECT 
    CASE 
        WHEN shipping_address LIKE '%Hải Châu%' THEN 'Hải Châu'
        WHEN shipping_address LIKE '%Sơn Trà%' THEN 'Sơn Trà' 
        WHEN shipping_address LIKE '%Ngũ Hành Sơn%' THEN 'Ngũ Hành Sơn'
        WHEN shipping_address LIKE '%Hòa Vang%' THEN 'Hòa Vang'
        ELSE 'Khác'
    END as district,
    COUNT(*) as total_orders,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as avg_order_value
FROM orders 
WHERE order_status = 'completed'
GROUP BY CASE 
    WHEN shipping_address LIKE '%Hải Châu%' THEN 'Hải Châu'
    WHEN shipping_address LIKE '%Sơn Trà%' THEN 'Sơn Trà' 
    WHEN shipping_address LIKE '%Ngũ Hành Sơn%' THEN 'Ngũ Hành Sơn'
    WHEN shipping_address LIKE '%Hòa Vang%' THEN 'Hòa Vang'
    ELSE 'Khác'
END;
GO

-- VIEW DOANH THU THEO GIỜ TRONG NGÀY
IF EXISTS (SELECT * FROM sys.views WHERE name = 'vw_hourly_revenue')
    DROP VIEW vw_hourly_revenue;
GO

CREATE VIEW vw_hourly_revenue AS
SELECT 
    DATEPART(HOUR, created_at) as hour_of_day,
    COUNT(*) as total_orders,
    SUM(total_amount) as total_revenue
FROM orders 
WHERE order_status = 'completed'
GROUP BY DATEPART(HOUR, created_at);
GO

PRINT '✅ Đã tạo các view cho Admin Dashboard';
GO