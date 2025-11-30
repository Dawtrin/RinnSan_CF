USE RinnSanCF;
GO

-- CHÈN MÃ GIẢM GIÁ
IF NOT EXISTS (SELECT * FROM coupons WHERE code = 'WELCOME10')
BEGIN
    INSERT INTO coupons (code, discount_type, discount_value, min_order_amount, max_discount_amount, usage_limit, valid_from, valid_to) VALUES
    ('WELCOME10', 'percentage', 10.00, 50000, 20000, 1000, '2024-01-01', '2024-12-31'),
    ('FREESHIP', 'fixed', 15000, 150000, 15000, 500, '2024-01-01', '2024-06-30'),
    ('SUMMER20', 'percentage', 20.00, 100000, 50000, 200, '2024-06-01', '2024-08-31'),
    ('NEWYEAR25', 'percentage', 25.00, 200000, 75000, 100, '2024-12-20', '2025-01-10');
    
    PRINT '✅ Dữ liệu coupons đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu coupons đã tồn tại';
GO