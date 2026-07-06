USE RinnSanCF;
GO

-- CHÈN SẢN PHẨM CAFE & BÁNH ĐẦY ĐỦ
-- Lưu ý: Đã thêm N'' vào Name, Description, Short Description
IF NOT EXISTS (SELECT * FROM products WHERE name = 'Cafe Espresso')
BEGIN
    INSERT INTO products (name, slug, description, short_description, price, compare_price, cost_price, sku, category_id, is_featured, quantity, sold_count, images, tags) VALUES
    
    -- === CAFE ===
    (N'Cafe Espresso', 'cafe-espresso', N'Cafe espresso nguyên chất đậm đà, hương vị cà phê Ý truyền thống', N'Espresso đậm đà nguyên chất', 35000, 40000, 15000, 'CF-ESP-001', 1, 1, 100, 45, '["/images/products/espresso.jpg"]', '["best-seller", "hot"]'),
    (N'Cafe Latte', 'cafe-latte', N'Cafe latte với sữa tươi hảo hạng, vị cafe êm dịu pha lẫn vị sữa thơm ngon', N'Latte với sữa tươi hảo hạng', 45000, 50000, 18000, 'CF-LAT-002', 1, 1, 80, 67, '["/images/products/latte.jpg"]', '["best-seller", "featured"]'),
    (N'Cappuccino', 'cappuccino', N'Cappuccino với lớp foam sữa dày mịn, hương vị cafe cân bằng hoàn hảo', N'Cappuccino foam sữa mịn', 45000, 50000, 18000, 'CF-CAP-003', 1, 1, 75, 52, '["/images/products/cappuccino.jpg"]', '["featured"]'),
    (N'Americano', 'americano', N'Americano pha loãng từ espresso, vị cafe đậm nhưng nhẹ nhàng dễ uống', N'Americano đậm vị nhẹ nhàng', 40000, 45000, 16000, 'CF-AME-004', 1, 0, 90, 38, '["/images/products/americano.jpg"]', '["new"]'),
    (N'Cold Brew', 'cold-brew', N'Cold Brew ủ lạnh 24h, vị cafe mượt mà ít acid, thơm hương hạt dẻ', N'Cold Brew ủ lạnh 24h', 50000, 55000, 20000, 'CF-CBR-005', 1, 1, 45, 28, '["/images/products/cold-brew.jpg"]', '["trending", "summer"]'),
    (N'Vietnamese Coconut Coffee', 'cafe-cot-dua', N'Cafe phin truyền thống kết hợp cốt dừa béo ngậy, đá xay mát lạnh', N'Cafe cốt dừa Việt Nam', 55000, 60000, 22000, 'CF-CDU-006', 1, 1, 35, 41, '["/images/products/cafe-cot-dua.jpg"]', '["vietnamese", "featured"]'),
    (N'Hazelnut Latte', 'hazelnut-latte', N'Latte với hương hạnh nhân thơm ngon, vị béo ngậy đặc trưng', N'Latte hạnh nhân thơm ngon', 50000, 55000, 21000, 'CF-HZL-007', 1, 0, 40, 23, '["/images/products/hazelnut-latte.jpg"]', '["nutty", "sweet"]'),
    (N'Caramel Macchiato', 'caramel-macchiato', N'Espresso với caramel ngọt ngào và sữa tươi thơm béo', N'Caramel Macchiato ngọt ngào', 50000, 55000, 21000, 'CF-CM-008', 1, 1, 55, 34, '["/images/products/caramel-macchiato.jpg"]', '["sweet", "caramel"]'),
    (N'Mocha', 'mocha', N'Sự kết hợp hoàn hảo giữa espresso, socola và sữa', N'Mocha socola thơm ngon', 50000, 55000, 21000, 'CF-MOC-009', 1, 0, 48, 29, '["/images/products/mocha.jpg"]', '["chocolate", "sweet"]'),
    
    -- === TRÀ ===
    (N'Trà sữa trân châu', 'tra-sua-tran-chau', N'Trà sữa trân châu đường đen thơm ngon, trân châu dai mềm', N'Trà sữa trân châu đường đen', 55000, 60000, 22000, 'TS-TC-101', 2, 1, 60, 89, '["/images/products/trasua-tranchau.jpg"]', '["best-seller", "hot"]'),
    (N'Trà đào cam sả', 'tra-dao-cam-sa', N'Trà đào cam sả thanh mát, vị đào ngọt dịu kết hợp cam sả thơm', N'Trà đào cam sả thanh mát', 45000, 50000, 18000, 'TS-DCS-102', 2, 1, 70, 63, '["/images/products/tra-dao-cam-sa.jpg"]', '["featured", "summer"]'),
    (N'Trà vải', 'tra-vai', N'Trà vải ngọt thanh, hương vải thơm ngon tự nhiên', N'Trà vải ngọt thanh', 40000, 45000, 16000, 'TS-VAI-103', 2, 0, 65, 41, '["/images/products/tra-vai.jpg"]', '["summer"]'),
    (N'Trà sen vàng', 'tra-sen-vang', N'Trà sen vàng cao cấp, hương sen thơm ngát thanh tao', N'Trà sen vàng cao cấp', 60000, 65000, 25000, 'TS-SV-104', 2, 1, 25, 19, '["/images/products/tra-sen-vang.jpg"]', '["premium", "vietnamese"]'),
    (N'Trà đen sữa', 'tra-den-sua', N'Trà đen kết hợp sữa tươi, vị đậm đà thơm ngon', N'Trà đen sữa đậm đà', 40000, 45000, 16000, 'TS-DS-105', 2, 0, 50, 32, '["/images/products/tra-den-sua.jpg"]', '["classic"]'),
    (N'Trà xanh matcha latte', 'tra-xanh-matcha-latte', N'Matcha latte Nhật Bản, vị trà xanh thanh mát với sữa', N'Matcha latte Nhật Bản', 50000, 55000, 20000, 'TS-ML-106', 2, 1, 30, 47, '["/images/products/matcha-latte.jpg"]', '["japanese", "healthy"]'),
    (N'Trà sữa oolong', 'tra-sua-oolong', N'Trà sữa oolong thơm ngon, vị trà thanh nhẹ đặc trưng', N'Trà sữa oolong thơm ngon', 45000, 50000, 18000, 'TS-OL-107', 2, 0, 42, 25, '["/images/products/tra-sua-oolong.jpg"]', '["oolong", "taiwan"]'),
    (N'Trà đào dâu tây', 'tra-dao-dau-tay', N'Trà đào kết hợp dâu tây tươi, vị chua ngọt cân bằng', N'Trà đào dâu tây tươi mát', 48000, 53000, 19000, 'TS-DDT-108', 2, 1, 38, 31, '["/images/products/tra-dao-dau-tay.jpg"]', '["fruity", "summer"]'),
    
    -- === ĐÁ XAY ===
    (N'Chocolate đá xay', 'chocolate-da-xay', N'Chocolate đá xay thơm béo, vị socola đậm đà hấp dẫn', N'Chocolate đá xay thơm béo', 55000, 60000, 22000, 'DX-CHO-201', 3, 1, 50, 72, '["/images/products/chocolate-da-xay.jpg"]', '["best-seller", "chocolate"]'),
    (N'Matcha đá xay', 'matcha-da-xay', N'Matcha đá xay vị trà xanh Nhật Bản, thanh mát và bổ dưỡng', N'Matcha đá xay trà xanh Nhật', 50000, 55000, 20000, 'DX-MAT-202', 3, 1, 55, 58, '["/images/products/matcha-da-xay.jpg"]', '["featured", "healthy"]'),
    (N'Caramel đá xay', 'caramel-da-xay', N'Caramel đá xay vị ngọt caramel đặc trưng, thơm ngon khó cưỡng', N'Caramel đá xay ngọt ngào', 55000, 60000, 22000, 'DX-CAR-203', 3, 0, 45, 34, '["/images/products/caramel-da-xay.jpg"]', '["sweet"]'),
    (N'Cookies & Cream', 'cookies-cream-da-xay', N'Đá xay vị bánh cookie và kem, thơm ngon hấp dẫn', N'Cookies & Cream đá xay', 55000, 60000, 22000, 'DX-CC-204', 3, 1, 40, 51, '["/images/products/cookies-cream.jpg"]', '["cookies", "creamy"]'),
    (N'Strawberry đá xay', 'strawberry-da-xay', N'Đá xay dâu tươi, vị chua ngọt thanh mát', N'Đá xay dâu tươi', 50000, 55000, 20000, 'DX-STR-205', 3, 0, 35, 29, '["/images/products/strawberry-da-xay.jpg"]', '["fruity", "summer"]'),
    (N'Mango đá xay', 'mango-da-xay', N'Đá xay xoài chín vàng, vị ngọt thanh nhiệt đới', N'Đá xay xoài nhiệt đới', 50000, 55000, 20000, 'DX-MAN-206', 3, 0, 38, 26, '["/images/products/mango-da-xay.jpg"]', '["tropical", "fruity"]'),
    (N'Blueberry đá xay', 'blueberry-da-xay', N'Đá xay việt quất, chống oxy hóa, tốt cho sức khỏe', N'Đá xay việt quất tốt cho sức khỏe', 52000, 57000, 21000, 'DX-BLU-207', 3, 1, 32, 18, '["/images/products/blueberry-da-xay.jpg"]', '["healthy", "antioxidant"]'),
    (N'Vanilla đá xay', 'vanilla-da-xay', N'Đá xay vani thơm ngon, vị ngọt dịu nhẹ', N'Đá xay vani thơm ngon', 48000, 53000, 19000, 'DX-VAN-208', 3, 0, 44, 22, '["/images/products/vanilla-da-xay.jpg"]', '["classic", "sweet"]'),
    
    -- === BÁNH NGỌT ===
    (N'Tiramisu', 'tiramisu', N'Bánh Tiramisu Ý với lớp mascarpone béo ngậy, cafe espresso đậm đà', N'Tiramisu Ý béo ngậy', 65000, 70000, 28000, 'BN-TIR-301', 4, 1, 30, 95, '["/images/products/tiramisu.jpg"]', '["best-seller", "premium"]'),
    (N'Cheesecake dâu', 'cheesecake-dau', N'Cheesecake dâu tươi mát lạnh, vị phô mai béo hòa quyện dâu ngọt', N'Cheesecake dâu tươi mát lạnh', 55000, 60000, 23000, 'BN-CHE-302', 4, 1, 25, 78, '["/images/products/cheesecake-dau.jpg"]', '["featured", "fruity"]'),
    (N'Macaron', 'macaron', N'Macaron Pháp với nhiều hương vị, vỏ giòn tan nhân mềm mịn', N'Macaron Pháp nhiều hương vị', 35000, 40000, 15000, 'BN-MAC-303', 4, 1, 40, 112, '["/images/products/macaron.jpg"]', '["best-seller", "french"]'),
    (N'Croissant', 'croissant', N'Bánh sừng bò Croissant Pháp giòn tan, bơ thơm lừng', N'Croissant Pháp giòn tan', 25000, 30000, 10000, 'BN-CRO-304', 4, 0, 35, 67, '["/images/products/croissant.jpg"]', '["french", "buttery"]'),
    (N'Red Velvet', 'red-velvet', N'Bánh Red Velvet với kem cheese, màu đỏ quyến rũ', N'Red Velvet kem cheese', 70000, 75000, 30000, 'BN-RV-305', 4, 1, 20, 63, '["/images/products/red-velvet.jpg"]', '["premium", "american"]'),
    (N'Mousse socola', 'mousse-socola', N'Mousse socola mềm mịn, vị socola đậm đà thơm ngon', N'Mousse socola mềm mịn', 45000, 50000, 18000, 'BN-MOU-306', 4, 1, 28, 54, '["/images/products/mousse-socola.jpg"]', '["chocolate", "smooth"]'),
    (N'Bánh flan phô mai', 'banh-flan-pho-mai', N'Flan phô mai béo ngậy, vị ngọt thanh dễ ăn', N'Flan phô mai béo ngậy', 30000, 35000, 12000, 'BN-FLP-307', 4, 0, 45, 48, '["/images/products/flan-pho-mai.jpg"]', '["creamy", "cheese"]'),
    (N'Donut', 'donut', N'Donut nhiều vị: socola, dâu, vani, rắc hạt cốm', N'Donut nhiều vị thơm ngon', 25000, 30000, 10000, 'BN-DON-308', 4, 0, 60, 72, '["/images/products/donut.jpg"]', '["american", "sweet"]'),
    (N'Bánh su kem', 'banh-su-kem', N'Bánh su kem nhân kem tươi, vỏ giòn nhân mềm', N'Bánh su kem nhân kem tươi', 20000, 25000, 8000, 'BN-SK-309', 4, 1, 55, 89, '["/images/products/banh-su-kem.jpg"]', '["french", "creamy"]'),
    (N'Opera Cake', 'opera-cake', N'Bánh Opera nhiều lớp, hương vị cà phê và socola hòa quyện', N'Opera Cake nhiều lớp', 75000, 80000, 32000, 'BN-OP-310', 4, 1, 15, 26, '["/images/products/opera-cake.jpg"]', '["premium", "french"]'),
    
    -- === BÁNH MÌ ===
    (N'Bánh mì que', 'banh-mi-que', N'Bánh mì que giòn rụm, thơm mùi bơ tỏi', N'Bánh mì que giòn rụm', 15000, 18000, 6000, 'BM-QUE-401', 5, 1, 50, 145, '["/images/products/banh-mi-que.jpg"]', '["best-seller", "snack"]'),
    (N'Bánh mì gối', 'banh-mi-goi', N'Bánh mì gối tươi mềm, thích hợp cho bữa sáng', N'Bánh mì gối tươi mềm', 20000, 25000, 8000, 'BM-GOI-402', 5, 0, 40, 89, '["/images/products/banh-mi-goi.jpg"]', '["breakfast"]'),
    (N'Bánh mì baguette', 'banh-mi-baguette', N'Bánh mì baguette Pháp giòn tan, ruột mềm thơm', N'Baguette Pháp giòn tan', 18000, 22000, 7000, 'BM-BAG-403', 5, 1, 35, 67, '["/images/products/baguette.jpg"]', '["french", "crusty"]'),
    (N'Bánh mì ngọt', 'banh-mi-ngot', N'Bánh mì ngọt mềm xốp, thơm mùi sữa', N'Bánh mì ngọt mềm xốp', 12000, 15000, 5000, 'BM-NGOT-404', 5, 0, 65, 98, '["/images/products/banh-mi-ngot.jpg"]', '["sweet", "soft"]'),
    (N'Bánh mì sandwich', 'banh-mi-sandwich', N'Bánh mì sandwich mềm, thích hợp làm sandwich', N'Bánh mì sandwich mềm', 22000, 27000, 9000, 'BM-SAN-405', 5, 0, 30, 45, '["/images/products/sandwich-bread.jpg"]', '["sandwich", "soft"]'),
    
    -- === BÁNH ÂU ===
    (N'Bánh tart trứng', 'banh-tart-trung', N'Tart trứng bồ đào ngọt thanh, vỏ bánh giòn xốp', N'Tart trứng bồ đào', 35000, 40000, 14000, 'BA-TT-501', 6, 1, 25, 56, '["/images/products/tart-trung.jpg"]', '["portuguese", "egg"]'),
    (N'Bánh su', 'banh-su', N'Bánh su nhân kem tươi, vỏ giòn nhân mát lạnh', N'Bánh su nhân kem tươi', 28000, 33000, 11000, 'BA-SU-502', 6, 0, 40, 43, '["/images/products/banh-su.jpg"]', '["french", "cream"]'),
    (N'Bánh phô mai chanh leo', 'banh-pho-mai-chanh-leo', N'Bánh phô mai chanh leo chua ngọt, thanh mát', N'Phô mai chanh leo thanh mát', 45000, 50000, 18000, 'BA-PMCL-503', 6, 1, 22, 38, '["/images/products/pho-mai-chanh-leo.jpg"]', '["cheese", "citrus"]'),
    (N'Bánh cam', 'banh-cam', N'Bánh cam vàng ươm, nhân đậu xanh thơm ngon', N'Bánh cam nhân đậu xanh', 18000, 22000, 7000, 'BA-CAM-504', 6, 0, 55, 72, '["/images/products/banh-cam.jpg"]', '["vietnamese", "bean"]'),
    
    -- === NƯỚC ÉP ===
    (N'Nước ép cam', 'nuoc-ep-cam', N'Nước ép cam tươi nguyên chất, giàu vitamin C', N'Nước ép cam tươi nguyên chất', 35000, 40000, 14000, 'NE-CAM-601', 7, 1, 60, 56, '["/images/products/nuoc-ep-cam.jpg"]', '["healthy", "vitamin"]'),
    (N'Nước ép táo', 'nuoc-ep-tao', N'Nước ép táo tươi ngọt thanh, tốt cho sức khỏe', N'Nước ép táo tươi ngọt thanh', 30000, 35000, 12000, 'NE-TAO-602', 7, 0, 55, 43, '["/images/products/nuoc-ep-tao.jpg"]', '["healthy"]'),
    (N'Nước ép dưa hấu', 'nuoc-ep-dua-hau', N'Nước ép dưa hấu thanh mát, giải nhiệt mùa hè', N'Nước ép dưa hấu thanh mát', 32000, 37000, 13000, 'NE-DH-603', 7, 1, 48, 39, '["/images/products/nuoc-ep-dua-hau.jpg"]', '["summer", "refreshing"]'),
    (N'Nước ép cà rốt', 'nuoc-ep-ca-rot', N'Nước ép cà rốt tốt cho mắt và da', N'Nước ép cà rốt tốt cho sức khỏe', 28000, 33000, 11000, 'NE-CR-604', 7, 0, 42, 27, '["/images/products/nuoc-ep-ca-rot.jpg"]', '["healthy", "carrot"]'),
    (N'Sinh tố bơ', 'sinh-to-bo', N'Sinh tố bơ béo ngậy, giàu dinh dưỡng', N'Sinh tố bơ béo ngậy', 40000, 45000, 16000, 'ST-BO-605', 7, 1, 35, 51, '["/images/products/sinh-to-bo.jpg"]', '["avocado", "nutritious"]'),
    (N'Sinh tố xoài', 'sinh-to-xoai', N'Sinh tố xoài chín vàng, vị ngọt thơm', N'Sinh tố xoài chín vàng', 38000, 43000, 15000, 'ST-XOAI-606', 7, 0, 40, 44, '["/images/products/sinh-to-xoai.jpg"]', '["tropical", "mango"]');
    
    PRINT '✅ Dữ liệu products đã được chèn - Tổng: 50 sản phẩm';
END
ELSE
    PRINT 'ℹ️ Dữ liệu products đã tồn tại';
GO