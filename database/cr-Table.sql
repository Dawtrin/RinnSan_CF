-- TẠO DATABASE
CREATE DATABASE RinnSanCF;
GO

USE RinnSanCF;
GO

-- 1. BẢNG PHÂN QUYỀN
CREATE TABLE roles (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID tự tăng, bắt đầu từ 1
    name VARCHAR(50) NOT NULL UNIQUE,           -- Tên vai trò: admin, staff, customer
    permissions NVARCHAR(MAX),                  -- Quyền chi tiết dạng JSON: ["users.create", "orders.view"]
    created_at DATETIME2 DEFAULT GETDATE()      -- Thời gian tạo bản ghi
);
GO

-- 2. BẢNG NGƯỜI DÙNG
CREATE TABLE users (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID người dùng
    username VARCHAR(100) UNIQUE NOT NULL,      -- Tên đăng nhập (duy nhất)
    email VARCHAR(255) UNIQUE NOT NULL,         -- Email (duy nhất)
    password VARCHAR(255) NOT NULL,             -- Mật khẩu đã mã hóa
    full_name NVARCHAR(255) NOT NULL,           -- Họ và tên đầy đủ
    phone VARCHAR(20),                          -- Số điện thoại
    avatar VARCHAR(500),                        -- Đường dẫn ảnh đại diện
    role_id INT DEFAULT 3,                      -- Khóa ngoại đến bảng roles (mặc định là customer)
    email_verified_at DATETIME2 NULL,           -- Thời gian xác thực email
    is_active BIT DEFAULT 1,                    -- Trạng thái kích hoạt: 1 = active, 0 = inactive
    last_login_at DATETIME2 NULL,               -- Thời gian đăng nhập cuối cùng
    created_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian tạo tài khoản
    updated_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian cập nhật cuối
    FOREIGN KEY (role_id) REFERENCES roles(id)  -- Liên kết đến bảng roles
);
GO

-- 3. BẢNG ĐỊA CHỈ NGƯỜI DÙNG
CREATE TABLE user_addresses (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID địa chỉ
    user_id INT NOT NULL,                       -- Khóa ngoại đến người dùng
    address_line1 NVARCHAR(500) NOT NULL,       -- Địa chỉ chính (số nhà, đường)
    address_line2 NVARCHAR(500),                -- Địa chỉ phụ (phường/xã)
    city NVARCHAR(100) NOT NULL,                -- Thành phố/tỉnh
    district NVARCHAR(100) NOT NULL,            -- Quận/huyện
    ward NVARCHAR(100) NOT NULL,                -- Phường/xã
    is_default BIT DEFAULT 0,                   -- Địa chỉ mặc định: 1 = mặc định, 0 = không
    created_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian tạo
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE -- Xóa cùng khi user bị xóa
);
GO

-- 4. BẢNG DANH MỤC SẢN PHẨM
CREATE TABLE categories (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID danh mục
    name NVARCHAR(255) NOT NULL,                -- Tên danh mục: "Cafe", "Trà sữa"
    slug VARCHAR(255) UNIQUE NOT NULL,          -- Slug URL: "cafe", "tra-sua"
    description NVARCHAR(MAX),                  -- Mô tả danh mục
    image VARCHAR(500),                         -- Đường dẫn ảnh danh mục
    parent_id INT NULL,                         -- Danh mục cha (cho danh mục con)
    sort_order INT DEFAULT 0,                   -- Thứ tự sắp xếp hiển thị
    is_active BIT DEFAULT 1,                    -- Trạng thái hiển thị
    created_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian tạo
    updated_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian cập nhật
    FOREIGN KEY (parent_id) REFERENCES categories(id) -- Liên kết tự thân
);
GO

-- 5. BẢNG SẢN PHẨM
CREATE TABLE products (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID sản phẩm
    name NVARCHAR(255) NOT NULL,                -- Tên sản phẩm: "Cafe Latte"
    slug VARCHAR(255) UNIQUE NOT NULL,          -- Slug URL: "cafe-latte"
    description NVARCHAR(MAX),                  -- Mô tả chi tiết sản phẩm
    short_description NVARCHAR(500),            -- Mô tả ngắn cho hiển thị
    price DECIMAL(10,2) NOT NULL,               -- Giá bán: 45000.00
    compare_price DECIMAL(10,2),                -- Giá so sánh (giá cũ): 50000.00
    cost_price DECIMAL(10,2),                   -- Giá vốn: 20000.00
    sku VARCHAR(100) UNIQUE,                    -- Mã SKU sản phẩm: "CFL-001"
    barcode VARCHAR(100),                       -- Mã vạch
    category_id INT NOT NULL,                   -- Danh mục sản phẩm
    is_featured BIT DEFAULT 0,                  -- Sản phẩm nổi bật: 1 = có, 0 = không
    is_active BIT DEFAULT 1,                    -- Đang kinh doanh: 1 = có, 0 = ngừng
    track_quantity BIT DEFAULT 1,               -- Theo dõi tồn kho: 1 = có, 0 = không
    quantity INT DEFAULT 0,                     -- Số lượng tồn kho hiện tại
    sold_count INT DEFAULT 0,                   -- Số lượng đã bán
    images NVARCHAR(MAX),                       -- Danh sách ảnh dạng JSON: ["img1.jpg", "img2.jpg"]
    weight DECIMAL(8,2),                        -- Trọng lượng (gram)
    tags NVARCHAR(MAX),                         -- Tags sản phẩm dạng JSON: ["new", "hot"]
    meta_title NVARCHAR(255),                   -- Tiêu đề SEO
    meta_description NVARCHAR(500),             -- Mô tả SEO
    created_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian tạo
    updated_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian cập nhật
    FOREIGN KEY (category_id) REFERENCES categories(id) -- Liên kết đến danh mục
);
GO

-- 6. BẢNG BIẾN THỂ SẢN PHẨM (SIZE, ĐƯỜNG, ĐÁ...)
IF EXISTS (SELECT * FROM sys.objects WHERE name = 'product_variants')
    DROP TABLE product_variants;
GO

CREATE TABLE product_variants (
    id INT PRIMARY KEY IDENTITY(1,1),
    product_id INT NOT NULL,
    name NVARCHAR(100) NOT NULL,
    variant_values NVARCHAR(MAX) NOT NULL,  -- ĐỔI TÊN TỪ values -> variant_values
    is_required BIT DEFAULT 0,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
GO

-- 7. BẢNG TÙY CHỌN SẢN PHẨM (GIÁ THEO BIẾN THỂ)
CREATE TABLE product_options (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID tùy chọn
    product_id INT NOT NULL,                    -- Sản phẩm cha
    variant_combination NVARCHAR(MAX) NOT NULL, -- Kết hợp biến thể: {"size":"L","sweetness":"Vừa"}
    price_modifier DECIMAL(10,2) DEFAULT 0,     -- Thay đổi giá: +5000.00, -2000.00
    sku VARCHAR(100),                           -- SKU riêng cho tùy chọn
    quantity INT DEFAULT 0,                     -- Số lượng tồn cho tùy chọn này
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE -- Xóa cùng sản phẩm
);
GO

-- 8. BẢNG ĐƠN HÀNG
CREATE TABLE orders (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID đơn hàng
    order_code VARCHAR(50) UNIQUE NOT NULL,     -- Mã đơn hàng: "CAFE-2024-001"
    user_id INT NULL,                           -- Người dùng (NULL = khách vãng lai)
    customer_name NVARCHAR(255) NOT NULL,       -- Tên khách hàng
    customer_phone VARCHAR(20) NOT NULL,        -- SĐT khách hàng
    customer_email VARCHAR(255),                -- Email khách hàng
    shipping_address NVARCHAR(MAX),             -- Địa chỉ giao hàng
    item_count INT DEFAULT 0,                   -- Số loại sản phẩm trong đơn
    quantity_total INT DEFAULT 0,               -- Tổng số lượng sản phẩm
    subtotal DECIMAL(12,2) DEFAULT 0,           -- Tổng tiền hàng
    discount_amount DECIMAL(12,2) DEFAULT 0,    -- Tiền giảm giá
    shipping_fee DECIMAL(12,2) DEFAULT 0,       -- Phí giao hàng
    tax_amount DECIMAL(12,2) DEFAULT 0,         -- Thuế
    total_amount DECIMAL(12,2) DEFAULT 0,       -- Tổng tiền thanh toán
    order_status VARCHAR(20) DEFAULT 'pending', -- Trạng thái: pending/confirmed/processing/ready/completed/cancelled
    payment_status VARCHAR(20) DEFAULT 'pending', -- Trạng thái thanh toán: pending/paid/failed/refunded
    payment_method VARCHAR(20),                 -- Phương thức: cash/card/momo/zalopay/banking
    note NVARCHAR(MAX),                         -- Ghi chú đơn hàng
    cancelled_reason NVARCHAR(MAX),             -- Lý do hủy đơn
    completed_at DATETIME2 NULL,                -- Thời gian hoàn thành
    created_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian tạo đơn
    updated_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian cập nhật
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Liên kết đến người dùng
);
GO

-- 9. BẢNG CHI TIẾT ĐƠN HÀNG
CREATE TABLE order_items (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID chi tiết
    order_id INT NOT NULL,                      -- Đơn hàng
    product_id INT NOT NULL,                    -- Sản phẩm
    product_name NVARCHAR(255) NOT NULL,        -- Tên sản phẩm lúc mua (lưu lại phòng case thay đổi)
    product_price DECIMAL(10,2) NOT NULL,       -- Giá sản phẩm lúc mua
    variant_combination NVARCHAR(MAX),          -- Tùy chọn: {"size":"L","sweetness":"Vừa"}
    quantity INT NOT NULL,                      -- Số lượng
    total_price DECIMAL(10,2) NOT NULL,         -- Thành tiền = product_price * quantity
    note NVARCHAR(500),                         -- Ghi chú riêng cho sản phẩm
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE, -- Xóa cùng đơn hàng
    FOREIGN KEY (product_id) REFERENCES products(id) -- Liên kết đến sản phẩm
);
GO

-- 10. BẢNG THANH TOÁN
CREATE TABLE payments (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID thanh toán
    order_id INT NOT NULL,                      -- Đơn hàng
    payment_method VARCHAR(20) NOT NULL,        -- cash/card/momo/zalopay/banking
    amount DECIMAL(12,2) NOT NULL,              -- Số tiền thanh toán
    transaction_id VARCHAR(255),                -- Mã giao dịch từ cổng thanh toán
    payment_status VARCHAR(20) DEFAULT 'pending', -- pending/success/failed/refunded
    payment_data NVARCHAR(MAX),                 -- Dữ liệu thanh toán từ gateway
    paid_at DATETIME2 NULL,                     -- Thời gian thanh toán thành công
    created_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian tạo
    FOREIGN KEY (order_id) REFERENCES orders(id) -- Liên kết đến đơn hàng
);
GO

-- 11. BẢNG NHÀ CUNG CẤP
CREATE TABLE suppliers (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID nhà cung cấp
    name NVARCHAR(255) NOT NULL,                -- Tên nhà cung cấp
    contact_person NVARCHAR(255),               -- Người liên hệ
    phone VARCHAR(20),                          -- SĐT liên hệ
    email VARCHAR(255),                         -- Email
    address NVARCHAR(MAX),                      -- Địa chỉ
    is_active BIT DEFAULT 1,                    -- Đang hợp tác
    created_at DATETIME2 DEFAULT GETDATE()      -- Thời gian tạo
);
GO

-- 12. BẢNG KHO NGUYÊN LIỆU
CREATE TABLE inventory (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID nguyên liệu
    name NVARCHAR(255) NOT NULL,                -- Tên nguyên liệu: "Cafe Arabica"
    sku VARCHAR(100) UNIQUE,                    -- Mã SKU: "CAFE-ARABICA-1KG"
    unit VARCHAR(50) NOT NULL,                  -- Đơn vị: kg, gram, liter, pack
    current_quantity DECIMAL(10,3) DEFAULT 0,   -- Số lượng hiện tại: 15.500
    min_quantity DECIMAL(10,3) DEFAULT 0,       -- Số lượng tối thiểu: 5.000
    cost_price DECIMAL(10,2),                   -- Giá nhập: 200000.00
    supplier_id INT,                            -- Nhà cung cấp
    is_active BIT DEFAULT 1,                    -- Đang sử dụng
    created_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian tạo
    updated_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian cập nhật
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) -- Liên kết đến nhà cung cấp
);
GO

-- 13. BẢNG LỊCH SỬ KHO
CREATE TABLE inventory_transactions (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID giao dịch
    inventory_id INT NOT NULL,                  -- Nguyên liệu
    type VARCHAR(10) NOT NULL,                  -- Loại: in (nhập), out (xuất), adjust (điều chỉnh)
    quantity DECIMAL(10,3) NOT NULL,            -- Số lượng: +2.500, -0.300
    note NVARCHAR(MAX),                         -- Ghi chú: "Nhập hàng tháng 1", "Pha 10 ly cafe"
    reference_type VARCHAR(20),                 -- Loại tham chiếu: purchase/order/adjustment/waste
    reference_id INT,                           -- ID tham chiếu
    created_by INT NOT NULL,                    -- Người thực hiện
    created_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian tạo
    FOREIGN KEY (inventory_id) REFERENCES inventory(id), -- Liên kết đến nguyên liệu
    FOREIGN KEY (created_by) REFERENCES users(id) -- Liên kết đến người dùng
);
GO

-- 14. BẢNG MÃ GIẢM GIÁ
CREATE TABLE coupons (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID mã giảm giá
    code VARCHAR(50) UNIQUE NOT NULL,           -- Mã: "GIAM20K", "SUMMER2024"
    discount_type VARCHAR(20) NOT NULL,         -- Loại: percentage (%), fixed (VND)
    discount_value DECIMAL(10,2) NOT NULL,      -- Giá trị: 20.00 (20%) hoặc 20000.00 (20k)
    min_order_amount DECIMAL(10,2) DEFAULT 0,   -- Đơn tối thiểu: 100000.00
    max_discount_amount DECIMAL(10,2),          -- Giảm tối đa: 50000.00
    usage_limit INT,                            -- Số lần sử dụng tối đa
    used_count INT DEFAULT 0,                   -- Số lần đã sử dụng
    valid_from DATETIME2 NULL,                  -- Có hiệu lực từ
    valid_to DATETIME2 NULL,                    -- Hết hiệu lực
    is_active BIT DEFAULT 1,                    -- Đang kích hoạt
    created_at DATETIME2 DEFAULT GETDATE()      -- Thời gian tạo
);
GO

-- 15. BẢNG CÀI ĐẶT HỆ THỐNG
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'settings')
BEGIN
    CREATE TABLE settings (
        id INT PRIMARY KEY IDENTITY(1,1),
        setting_key VARCHAR(255) UNIQUE NOT NULL,
        value NVARCHAR(MAX),
        description NVARCHAR(500),
        updated_at DATETIME2 DEFAULT GETDATE()
    );
    PRINT 'Bảng settings đã được tạo thành công';
END
ELSE
    PRINT 'Bảng settings đã tồn tại';
GO

-- 16. BẢNG NHẬT KÝ HOẠT ĐỘNG
CREATE TABLE activity_logs (
    id INT PRIMARY KEY IDENTITY(1,1),           -- ID nhật ký
    user_id INT NULL,                           -- Người dùng (NULL = hệ thống)
    action VARCHAR(100) NOT NULL,               -- Hành động: "user.login", "order.create"
    description NVARCHAR(MAX),                  -- Mô tả chi tiết
    ip_address VARCHAR(45),                     -- Địa chỉ IP
    user_agent NVARCHAR(MAX),                   -- Thông tin trình duyệt
    reference_type VARCHAR(100),                -- Loại tham chiếu: Order, Product, User
    reference_id INT,                           -- ID tham chiếu
    created_at DATETIME2 DEFAULT GETDATE(),     -- Thời gian ghi log
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Liên kết đến người dùng
);
GO

--ADMIN VIEW--
-- TẠO VIEW CHO BÁO CÁO DOANH THU
CREATE VIEW vw_daily_revenue AS
SELECT 
    CAST(created_at AS DATE) as order_date,
    COUNT(*) as total_orders,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as avg_order_value
FROM orders 
WHERE order_status = 'completed'
GROUP BY CAST(created_at AS DATE);
GO

-- TẢO VIEW TOP SẢN PHẨM
CREATE VIEW vw_top_products AS
SELECT 
    p.name as product_name,
    c.name as category_name,
    SUM(oi.quantity) as total_sold,
    SUM(oi.total_price) as total_revenue
FROM order_items oi
JOIN products p ON oi.product_id = p.id
JOIN categories c ON p.category_id = c.id
JOIN orders o ON oi.order_id = o.id
WHERE o.order_status = 'completed'
GROUP BY p.name, c.name;
GO

-- TẠO VIEW DOANH THU THEO QUẬN/HUYỆN
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
    SUM(total_amount) as total_revenue
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