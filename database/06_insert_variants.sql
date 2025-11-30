USE RinnSanCF;
GO

-- CHÈN BIẾN THỂ SẢN PHẨM
IF NOT EXISTS (SELECT * FROM product_variants WHERE product_id = 1)
BEGIN
    -- Cafe Espresso
    INSERT INTO product_variants (product_id, name, variant_values, is_required, sort_order) VALUES
    (1, 'Size', '["Nhỏ", "Vừa", "Lớn"]', 1, 1),
    (1, 'Đá', '["Không đá", "Ít đá", "Nhiều đá"]', 0, 2),
    
    -- Cafe Latte
    (2, 'Size', '["Nhỏ", "Vừa", "Lớn"]', 1, 1),
    (2, 'Đá', '["Không đá", "Ít đá", "Nhiều đá"]', 0, 2),
    (2, 'Đường', '["Không đường", "Ít đường", "Vừa", "Ngọt"]', 0, 3),
    
    -- Trà sữa trân châu
    (5, 'Size', '["Nhỏ", "Vừa", "Lớn"]', 1, 1),
    (5, 'Đá', '["Không đá", "Ít đá", "Nhiều đá"]', 0, 2),
    (5, 'Đường', '["Không đường", "Ít đường", "Vừa", "Ngọt"]', 0, 3),
    (5, 'Topping', '["Không topping", "Trân châu trắng", "Trân châu đen", "Thạch café"]', 0, 4),
    
    -- Đá xay
    (8, 'Size', '["Nhỏ", "Vừa", "Lớn"]', 1, 1),
    (8, 'Topping', '["Không topping", "Kem whipping", "Sốt caramel", "Hạt cốm"]', 0, 2);
    
    PRINT '✅ Dữ liệu product_variants đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu product_variants đã tồn tại';
GO

-- CHÈN TÙY CHỌN GIÁ
IF NOT EXISTS (SELECT * FROM product_options WHERE product_id = 1)
BEGIN
    -- Cafe Espresso - Size
    INSERT INTO product_options (product_id, variant_combination, price_modifier, sku) VALUES
    (1, '{"size": "Nhỏ"}', 0, 'CF-ESP-001-S'),
    (1, '{"size": "Vừa"}', 5000, 'CF-ESP-001-M'),
    (1, '{"size": "Lớn"}', 10000, 'CF-ESP-001-L'),
    
    -- Cafe Latte - Size
    (2, '{"size": "Nhỏ"}', 0, 'CF-LAT-002-S'),
    (2, '{"size": "Vừa"}', 5000, 'CF-LAT-002-M'),
    (2, '{"size": "Lớn"}', 10000, 'CF-LAT-002-L'),
    
    -- Trà sữa - Size + Topping
    (5, '{"size": "Nhỏ"}', 0, 'TS-TC-101-S'),
    (5, '{"size": "Vừa"}', 5000, 'TS-TC-101-M'),
    (5, '{"size": "Lớn"}', 10000, 'TS-TC-101-L'),
    (5, '{"size": "Vừa", "topping": "Trân châu đen"}', 8000, 'TS-TC-101-M-TC');
    
    PRINT '✅ Dữ liệu product_options đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu product_options đã tồn tại';
GO