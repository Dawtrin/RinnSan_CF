USE RinnSanCF;
GO

-- CHÈN DANH MỤC MẪU (THEO BONPAS & B&B)
-- Lưu ý: Đã thêm N'' trước các chuỗi tiếng Việt để không bị lỗi font
IF NOT EXISTS (SELECT * FROM categories WHERE name = 'Cafe')
BEGIN
    INSERT INTO categories (name, slug, description, image, sort_order) VALUES
    (N'Cafe', 'cafe', N'Các loại cafe truyền thống và hiện đại', '/images/categories/cafe.jpg', 1),
    (N'Trà', 'tra', N'Trà sữa, trà trái cây thơm ngon', '/images/categories/tra.jpg', 2),
    (N'Đá xay', 'da-xay', N'Các loại đá xay mát lạnh', '/images/categories/da-xay.jpg', 3),
    (N'Bánh ngọt', 'banh-ngot', N'Bánh kem, bánh ngọt tự làm', '/images/categories/banh-ngot.jpg', 4),
    (N'Bánh mì', 'banh-mi', N'Bánh mì tươi nóng hổi', '/images/categories/banh-mi.jpg', 5),
    (N'Bánh Âu', 'banh-au', N'Bánh Âu cao cấp', '/images/categories/banh-au.jpg', 6),
    (N'Nước ép', 'nuoc-ep', N'Nước ép trái cây tươi', '/images/categories/nuoc-ep.jpg', 7);
    
    PRINT '✅ Dữ liệu categories đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu categories đã tồn tại';
GO
```exe