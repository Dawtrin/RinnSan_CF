USE RinnSanCF;
GO

-- CHÈN CÀI ĐẶT HỆ THỐNG
IF NOT EXISTS (SELECT * FROM settings WHERE setting_key = 'store_name')
BEGIN
    INSERT INTO settings (setting_key, value, description) VALUES
    ('store_name', 'RinnSan Cafe & Bakery', 'Tên cửa hàng'),
    ('store_phone', '028 3620 8888', 'Số điện thoại cửa hàng'),
    ('store_email', 'info@rinnsancafe.com', 'Email liên hệ'),
    ('store_address', '123 Nguyễn Văn Linh, Quận 7, TP.HCM', 'Địa chỉ cửa hàng'),
    ('store_hours', '{"mon_fri": "7:00 - 22:00", "sat_sun": "7:30 - 23:00"}', 'Giờ mở cửa'),
    ('shipping_fee', '15000', 'Phí giao hàng tiêu chuẩn'),
    ('free_shipping_min', '150000', 'Đơn tối thiểu để miễn phí ship'),
    ('tax_rate', '0.08', 'Thuế VAT (8%)'),
    ('currency', 'VND', 'Đơn vị tiền tệ'),
    ('facebook_url', 'https://facebook.com/rinnsancafe', 'Facebook page'),
    ('instagram_url', 'https://instagram.com/rinnsancafe', 'Instagram'),
    ('delivery_zones', '["Quận Hải Châu", "Quận Sơn Trà", "Quận Ngũ Hành Sơn", "Xã Hòa Vang"]', 'Khu vực giao hàng');
    
    PRINT '✅ Dữ liệu settings đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu settings đã tồn tại';
GO