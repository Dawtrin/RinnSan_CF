USE RinnSanCF;
GO

-- CHÈN NGƯỜI DÙNG MẪU VỚI MẬT KHẨU TỰ ĐẶT
IF NOT EXISTS (SELECT * FROM users WHERE username = 'admin')
BEGIN
    INSERT INTO users (username, email, password, full_name, phone, role_id) VALUES
    ('admin', 'admin@rinnsancafe.com', 'admin123', 'Quản Trị Viên', '0123456789', 1),
    ('manager', 'manager@rinnsancafe.com', 'manager123', 'Quản Lý Chi Nhánh', '0123456790', 1),
    ('staff01', 'staff01@rinnsancafe.com', 'staff123', 'Nguyễn Văn A - Nhân Viên', '0123456791', 2),
    ('staff02', 'staff02@rinnsancafe.com', 'staff456', 'Trần Thị B - Nhân Viên', '0123456792', 2),
    ('customer01', 'minh@gmail.com', 'minh123', 'Nguyễn Văn Minh', '0901234567', 3),
    ('customer02', 'lan@gmail.com', 'lan123', 'Trần Thị Lan', '0901234568', 3),
    ('customer03', 'hung@gmail.com', 'hung123', 'Lê Văn Hùng', '0901234569', 3);
    
    PRINT '✅ Dữ liệu users đã được chèn';
END
ELSE
    PRINT 'ℹ️ Dữ liệu users đã tồn tại';
GO


