# API Endpoints Documentation

## Base URL
```
http://your-domain/api
```

## Authentication Endpoints

### POST /api/auth/login
Đăng nhập
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

### POST /api/auth/register
Đăng ký tài khoản mới
```json
{
  "username": "username",
  "email": "user@example.com",
  "password": "password123",
  "full_name": "Họ và Tên",
  "phone": "0123456789"
}
```

### GET /api/auth/me
Lấy thông tin user hiện tại (cần đăng nhập)

### PUT /api/auth/profile
Cập nhật thông tin profile (cần đăng nhập)
```json
{
  "full_name": "Tên mới",
  "phone": "0987654321"
}
```

### POST /api/auth/logout
Đăng xuất

---

## Product Endpoints

### GET /api/products
Lấy danh sách sản phẩm
- Query params:
  - `category_id`: Lọc theo danh mục
  - `featured=1`: Chỉ lấy sản phẩm nổi bật
  - `search`: Tìm kiếm sản phẩm
  - `limit`: Số lượng (mặc định: 20)

### GET /api/products/{id}
Lấy chi tiết sản phẩm

### POST /api/products
Tạo sản phẩm mới
```json
{
  "name": "Cafe Latte",
  "slug": "cafe-latte",
  "price": 45000,
  "category_id": 1,
  "description": "Mô tả sản phẩm",
  "images": ["img1.jpg", "img2.jpg"]
}
```

### PUT /api/products/{id}
Cập nhật sản phẩm

### DELETE /api/products/{id}
Xóa sản phẩm

---

## Category Endpoints

### GET /api/categories
Lấy danh sách danh mục
- Query params:
  - `with_count=1`: Kèm số lượng sản phẩm
  - `parent_only=1`: Chỉ lấy danh mục cha

### GET /api/categories/{id}
Lấy chi tiết danh mục

### GET /api/categories/slug/{slug}
Lấy danh mục theo slug

### POST /api/categories
Tạo danh mục mới
```json
{
  "name": "Cà Phê",
  "slug": "ca-phe",
  "description": "Mô tả danh mục"
}
```

### PUT /api/categories/{id}
Cập nhật danh mục

---

## Order Endpoints

### GET /api/orders
Lấy danh sách đơn hàng
- Query params:
  - `user_id`: Lọc theo user
  - `status`: Lọc theo trạng thái (pending/confirmed/processing/ready/completed/cancelled)
  - `limit`: Số lượng

### GET /api/orders/{id}
Lấy chi tiết đơn hàng

### POST /api/orders
Tạo đơn hàng mới
```json
{
  "user_id": 1,
  "customer_name": "Nguyễn Văn A",
  "customer_phone": "0123456789",
  "customer_email": "customer@example.com",
  "shipping_address": "123 Đường ABC, Quận XYZ",
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "variant_combination": {"size": "L", "sweetness": "Vừa"},
      "note": "Ít đá"
    }
  ],
  "coupon_code": "GIAM20K",
  "payment_method": "cash",
  "shipping_fee": 20000,
  "note": "Giao hàng trước 12h"
}
```

### PUT /api/orders/{id}/status
Cập nhật trạng thái đơn hàng
```json
{
  "status": "confirmed",
  "reason": "Lý do (nếu hủy)"
}
```

### GET /api/orders/statistics
Lấy thống kê đơn hàng
- Query params:
  - `start_date`: Ngày bắt đầu (YYYY-MM-DD)
  - `end_date`: Ngày kết thúc (YYYY-MM-DD)

---

## Payment Endpoints

### POST /api/payments
Tạo thanh toán
```json
{
  "order_id": 1,
  "payment_method": "momo",
  "amount": 100000,
  "transaction_id": "TXN123456",
  "payment_status": "success"
}
```

### PUT /api/payments/{id}/status
Cập nhật trạng thái thanh toán
```json
{
  "status": "success",
  "transaction_id": "TXN123456"
}
```

### GET /api/payments/order/{orderId}
Lấy thanh toán theo order_id

---

## Coupon Endpoints

### GET /api/coupons
Lấy danh sách coupon đang active

### GET /api/coupons/{id}
Lấy chi tiết coupon

### POST /api/coupons/validate
Validate coupon
```json
{
  "code": "GIAM20K",
  "order_amount": 100000
}
```

---

## Status Endpoints

### GET /api/status
Kiểm tra trạng thái API

### GET /api/health
Kiểm tra health check (database connection)

---

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Thông báo thành công",
  "data": {
    // Dữ liệu trả về
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Thông báo lỗi",
  "data": {}
}
```

## Status Codes

- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `403`: Forbidden
- `404`: Not Found
- `500`: Internal Server Error


