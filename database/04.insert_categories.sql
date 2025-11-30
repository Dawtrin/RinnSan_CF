USE RinnSanCF;
GO

-- CHÈN DANH MỤC MẪU (THEO BONPAS & B&B)
IF NOT EXISTS (SELECT * FROM categories WHERE name = 'Cafe')
BEGIN
    INSERT INTO categories (name, slug, description, image, sort_order) VALUES
    ('Cafe', 'cafe', 'Các loại cafe truyền thống và hiện đại', '/images/categories/cafe.jpg', 1),
    ('Trà', 'tra', 'Trà sữa, trà trái cây thơm ngon', '/images/categories/tra.jpg', 2),
    ('Đá xay', 'da-xay', 'Các loại đá xay mát lạnh', '/images/categories/da-xay.jpg', 3),
    ('Bánh ngọt', 'banh-ngot', 'Bánh kem, bánh ngọt tự làm', '/images/categories/banh-ngot.jpg', 4),
    ('Bánh mì', 'banh-mi', 'Bánh mì tươi nóng hổi', '/images/categories/banh-mi.jpg', 5),
    ('Bánh Âu', 'banh-au', 'Bánh Âu cao cấp', '/images/categories/banh-au.jpg', 6),
    ('Nước ép', 'nuoc-ep', 'Nước ép trái cây tươi', '/images/categories/nuoc-ep.jpg', 7);
    
    PRINT '✅ Dữ liệu categories đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu categories đã tồn tại';
GO