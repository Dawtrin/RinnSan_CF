-- FIX DATABASE SCHEMA ISSUES
-- Chạy file này để sửa các lỗi trong schema

USE RinnSanCF;
GO

-- 1. FIX: Thêm ID cho products table nếu thiếu
IF NOT EXISTS (
    SELECT * FROM sys.columns 
    WHERE object_id = OBJECT_ID('products') AND name = 'id'
)
BEGIN
    ALTER TABLE products ADD id INT IDENTITY(1,1) PRIMARY KEY;
    PRINT '✅ Đã thêm id cho bảng products';
END
ELSE
    PRINT 'ℹ️ Bảng products đã có id';
GO

-- 2. FIX: Thêm ID cho inventory_transactions table nếu thiếu
IF NOT EXISTS (
    SELECT * FROM sys.columns 
    WHERE object_id = OBJECT_ID('inventory_transactions') AND name = 'id'
)
BEGIN
    ALTER TABLE inventory_transactions ADD id INT IDENTITY(1,1) PRIMARY KEY;
    PRINT '✅ Đã thêm id cho bảng inventory_transactions';
END
ELSE
    PRINT 'ℹ️ Bảng inventory_transactions đã có id';
GO

-- 3. TẠO INDEXES CHO PERFORMANCE
PRINT '📊 Đang tạo indexes...';
GO

-- Indexes cho users
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_users_email')
    CREATE INDEX IX_users_email ON users(email);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_users_role_id')
    CREATE INDEX IX_users_role_id ON users(role_id);
GO

-- Indexes cho products
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_products_category_id')
    CREATE INDEX IX_products_category_id ON products(category_id);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_products_is_active')
    CREATE INDEX IX_products_is_active ON products(is_active);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_products_is_featured')
    CREATE INDEX IX_products_is_featured ON products(is_featured);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_products_created_at')
    CREATE INDEX IX_products_created_at ON products(created_at);
GO

-- Indexes cho orders
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_orders_user_id')
    CREATE INDEX IX_orders_user_id ON orders(user_id);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_orders_order_status')
    CREATE INDEX IX_orders_order_status ON orders(order_status);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_orders_payment_status')
    CREATE INDEX IX_orders_payment_status ON orders(payment_status);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_orders_created_at')
    CREATE INDEX IX_orders_created_at ON orders(created_at);
GO

-- Indexes cho order_items
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_order_items_order_id')
    CREATE INDEX IX_order_items_order_id ON order_items(order_id);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_order_items_product_id')
    CREATE INDEX IX_order_items_product_id ON order_items(product_id);
GO

-- Indexes cho payments
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_payments_order_id')
    CREATE INDEX IX_payments_order_id ON payments(order_id);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_payments_payment_status')
    CREATE INDEX IX_payments_payment_status ON payments(payment_status);
GO

-- Indexes cho inventory_transactions
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_inventory_transactions_inventory_id')
    CREATE INDEX IX_inventory_transactions_inventory_id ON inventory_transactions(inventory_id);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_inventory_transactions_created_at')
    CREATE INDEX IX_inventory_transactions_created_at ON inventory_transactions(created_at);
GO

-- Indexes cho activity_logs
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_activity_logs_user_id')
    CREATE INDEX IX_activity_logs_user_id ON activity_logs(user_id);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_activity_logs_action')
    CREATE INDEX IX_activity_logs_action ON activity_logs(action);
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_activity_logs_created_at')
    CREATE INDEX IX_activity_logs_created_at ON activity_logs(created_at);
GO

PRINT '✅ Đã tạo tất cả indexes';
GO

-- 4. THÊM CONSTRAINTS
PRINT '🔒 Đang thêm constraints...';
GO

-- Check constraints cho giá trị >= 0
IF NOT EXISTS (SELECT * FROM sys.check_constraints WHERE name = 'CK_products_price')
    ALTER TABLE products ADD CONSTRAINT CK_products_price CHECK (price >= 0);
GO

IF NOT EXISTS (SELECT * FROM sys.check_constraints WHERE name = 'CK_orders_total_amount')
    ALTER TABLE orders ADD CONSTRAINT CK_orders_total_amount CHECK (total_amount >= 0);
GO

IF NOT EXISTS (SELECT * FROM sys.check_constraints WHERE name = 'CK_order_items_quantity')
    ALTER TABLE order_items ADD CONSTRAINT CK_order_items_quantity CHECK (quantity > 0);
GO

IF NOT EXISTS (SELECT * FROM sys.check_constraints WHERE name = 'CK_payments_amount')
    ALTER TABLE payments ADD CONSTRAINT CK_payments_amount CHECK (amount >= 0);
GO

PRINT '✅ Đã thêm tất cả constraints';
GO

PRINT '🎉 Hoàn thành fix schema!';
GO

