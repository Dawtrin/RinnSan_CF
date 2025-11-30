# 🔍 Database & API Design Review

## 📊 Database Schema Analysis

### ✅ Tables Đã Có (16 tables)
1. ✅ roles
2. ✅ users
3. ✅ user_addresses
4. ✅ categories
5. ✅ products
6. ✅ product_variants
7. ✅ product_options
8. ✅ orders
9. ✅ order_items
10. ✅ payments
11. ✅ suppliers
12. ✅ inventory
13. ✅ inventory_transactions
14. ✅ coupons
15. ✅ settings
16. ✅ activity_logs

### ⚠️ Vấn Đề Phát Hiện

#### 1. **Missing Models**
- ❌ `ProductVariant` - Model chưa có
- ❌ `ProductOption` - Model chưa có
- ❌ `InventoryTransaction` - Model chưa có
- ❌ `Role` - Model chưa có

#### 2. **Missing Indexes** (Performance Issues)
Database thiếu indexes cho các trường thường query:
- `users.email` - Đã có UNIQUE nhưng nên có index riêng
- `users.username` - Đã có UNIQUE
- `products.slug` - Đã có UNIQUE
- `products.category_id` - Cần index cho JOIN
- `orders.user_id` - Cần index
- `orders.order_code` - Đã có UNIQUE
- `orders.order_status` - Cần index cho filter
- `orders.created_at` - Cần index cho sorting
- `order_items.order_id` - Cần index
- `order_items.product_id` - Cần index
- `payments.order_id` - Cần index
- `coupons.code` - Đã có UNIQUE

#### 3. **Missing Constraints**
- `orders.total_amount` - Nên có CHECK >= 0
- `products.price` - Nên có CHECK >= 0
- `order_items.quantity` - Nên có CHECK > 0

#### 4. **Model Fields Mismatch**
- `products` table có `id` nhưng trong schema line 69 thiếu `id INT PRIMARY KEY IDENTITY(1,1)`
- Cần verify tất cả fields trong models khớp với database

#### 5. **Missing Relationships in Models**
- Product -> ProductVariant (hasMany)
- Product -> ProductOption (hasMany)
- Inventory -> InventoryTransaction (hasMany)
- User -> Role (belongsTo)

---

## 🔌 API Design Analysis

### ✅ API Endpoints Đã Có

#### Auth (5 endpoints)
- ✅ POST /api/auth/login
- ✅ POST /api/auth/register
- ✅ GET /api/auth/me
- ✅ PUT /api/auth/profile
- ✅ POST /api/auth/logout

#### Products (5 endpoints)
- ✅ GET /api/products
- ✅ GET /api/products/{id}
- ✅ POST /api/products
- ✅ PUT /api/products/{id}
- ✅ DELETE /api/products/{id}

#### Categories (5 endpoints)
- ✅ GET /api/categories
- ✅ GET /api/categories/{id}
- ✅ GET /api/categories/slug/{slug}
- ✅ POST /api/categories
- ✅ PUT /api/categories/{id}

#### Orders (5 endpoints)
- ✅ GET /api/orders
- ✅ GET /api/orders/{id}
- ✅ POST /api/orders
- ✅ PUT /api/orders/{id}/status
- ✅ GET /api/orders/statistics

#### Payments (3 endpoints)
- ✅ POST /api/payments
- ✅ PUT /api/payments/{id}/status
- ✅ GET /api/payments/order/{orderId}

#### Coupons (3 endpoints)
- ✅ GET /api/coupons
- ✅ GET /api/coupons/{id}
- ✅ POST /api/coupons/validate

#### Cart (5 endpoints)
- ✅ GET /api/cart
- ✅ POST /api/cart/add
- ✅ PUT /api/cart/update
- ✅ DELETE /api/cart/remove
- ✅ DELETE /api/cart/clear

#### Upload (3 endpoints)
- ✅ POST /api/upload
- ✅ POST /api/upload/multiple
- ✅ DELETE /api/upload/{filename}

#### Settings (4 endpoints)
- ✅ GET /api/settings
- ✅ GET /api/settings/{key}
- ✅ POST /api/settings
- ✅ PUT /api/settings/batch

#### Admin (2 endpoints)
- ✅ GET /api/admin/dashboard
- ✅ GET /api/admin/statistics

### ⚠️ API Design Issues

#### 1. **Missing Endpoints**
- ❌ GET /api/products/{id}/variants - Lấy variants của sản phẩm
- ❌ GET /api/products/{id}/options - Lấy options của sản phẩm
- ❌ GET /api/users - Quản lý users (admin)
- ❌ PUT /api/users/{id} - Cập nhật user
- ❌ GET /api/inventory - Quản lý kho
- ❌ POST /api/inventory/transactions - Tạo transaction kho
- ❌ GET /api/suppliers - Quản lý nhà cung cấp

#### 2. **API Response Consistency**
- Một số endpoints trả về `data` object, một số trả về array trực tiếp
- Nên standardize response format

#### 3. **Error Handling**
- Cần consistent error response format
- Cần HTTP status codes đúng chuẩn

#### 4. **Pagination**
- Một số endpoints có pagination, một số không
- Nên thêm pagination cho tất cả list endpoints

#### 5. **Filtering & Sorting**
- Thiếu query parameters cho filtering (price range, category, etc.)
- Thiếu sorting options

---

## 🎯 Recommendations

### Priority 1 (Critical)
1. ✅ Tạo missing models: ProductVariant, ProductOption, InventoryTransaction, Role
2. ✅ Thêm indexes cho performance
3. ✅ Fix database schema (missing id in products table)
4. ✅ Verify model fields match database

### Priority 2 (Important)
5. ✅ Thêm missing API endpoints
6. ✅ Standardize API response format
7. ✅ Thêm pagination cho tất cả list endpoints
8. ✅ Thêm filtering & sorting

### Priority 3 (Nice to have)
9. ✅ Add database constraints (CHECK)
10. ✅ Add relationships methods trong models
11. ✅ Add validation middleware
12. ✅ Add rate limiting

