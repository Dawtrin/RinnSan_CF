# 🚀 Frontend Integration Guide

## ✅ Backend Đã Sẵn Sàng 100%!

### 📊 Tổng Kết Backend

- ✅ **16 Models** - Đầy đủ
- ✅ **19 Controllers** (13 API + 6 Admin)
- ✅ **80+ API Endpoints** - Đầy đủ CRUD
- ✅ **4 Middleware** - Auth, Admin, RateLimit, Validation, CORS
- ✅ **4 Helpers** - Request, Response, Cache, File
- ✅ **Error Handling** - Global error handler
- ✅ **Security** - Rate limiting, Input sanitization
- ✅ **Performance** - Caching, Indexes

---

## 🔌 API Base URL

```
Development: http://localhost:8000
Production: https://your-domain.com
```

---

## 📡 API Response Format

### Success Response
```json
{
  "success": true,
  "message": "Thông báo thành công",
  "data": {
    // Dữ liệu trả về
  },
  "meta": {
    "pagination": {
      "current_page": 1,
      "per_page": 20,
      "total": 100,
      "total_pages": 5
    }
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Thông báo lỗi",
  "errors": {
    "field": ["Lỗi validation"]
  }
}
```

---

## 🔐 Authentication

### Login
```javascript
POST /api/auth/login
Body: {
  "email": "user@example.com",
  "password": "password123"
}

Response: {
  "success": true,
  "data": {
    "user": {...},
    "token": "session_id"
  }
}
```

### Sử dụng Session
- Sau khi login, session được tạo tự động
- Các request tiếp theo sẽ tự động có session
- Frontend không cần gửi token (dùng cookies)

### Hoặc dùng Bearer Token (nếu implement JWT sau)
```javascript
Headers: {
  "Authorization": "Bearer your_token_here"
}
```

---

## 📋 API Endpoints Chính

### Products
```javascript
GET    /api/products              // Danh sách (có pagination, filter, sort)
GET    /api/products/{id}         // Chi tiết
GET    /api/products/{id}/variants
GET    /api/products/{id}/options
POST   /api/products              // Tạo mới
PUT    /api/products/{id}         // Cập nhật
DELETE /api/products/{id}         // Xóa
```

### Orders
```javascript
GET    /api/orders                // Danh sách
GET    /api/orders/{id}           // Chi tiết
POST   /api/orders                // Tạo đơn hàng
PUT    /api/orders/{id}/status    // Cập nhật trạng thái
```

### Cart
```javascript
GET    /api/cart                  // Lấy giỏ hàng
POST   /api/cart/add              // Thêm vào giỏ
PUT    /api/cart/update           // Cập nhật
DELETE /api/cart/remove           // Xóa item
DELETE /api/cart/clear            // Xóa tất cả
```

### Auth
```javascript
POST   /api/auth/login
POST   /api/auth/register
GET    /api/auth/me               // User hiện tại
PUT    /api/auth/profile          // Cập nhật profile
POST   /api/auth/logout
```

### Admin (Cần đăng nhập admin)
```javascript
GET    /api/admin/dashboard
GET    /api/admin/statistics
GET    /api/admin/orders/recent
GET    /api/admin/products/top-selling
// ... và nhiều endpoints khác
```

---

## 🎯 Frontend Integration Examples

### React Example

```javascript
// API Service
const API_BASE = 'http://localhost:8000/api';

// Fetch Products
const fetchProducts = async (page = 1, filters = {}) => {
  const params = new URLSearchParams({
    page,
    per_page: 20,
    ...filters
  });
  
  const response = await fetch(`${API_BASE}/products?${params}`);
  const data = await response.json();
  
  if (data.success) {
    return data.data;
  }
  throw new Error(data.message);
};

// Login
const login = async (email, password) => {
  const response = await fetch(`${API_BASE}/auth/login`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    credentials: 'include', // Quan trọng cho session
    body: JSON.stringify({ email, password })
  });
  
  const data = await response.json();
  
  if (data.success) {
    return data.data.user;
  }
  throw new Error(data.message);
};

// Create Order
const createOrder = async (orderData) => {
  const response = await fetch(`${API_BASE}/orders`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    credentials: 'include',
    body: JSON.stringify(orderData)
  });
  
  const data = await response.json();
  
  if (data.success) {
    return data.data;
  }
  throw new Error(data.message);
};
```

### Axios Example

```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  withCredentials: true, // Quan trọng cho session
  headers: {
    'Content-Type': 'application/json'
  }
});

// Interceptor để xử lý errors
api.interceptors.response.use(
  response => response.data,
  error => {
    if (error.response?.data) {
      throw new Error(error.response.data.message);
    }
    throw error;
  }
);

// Sử dụng
const products = await api.get('/products', {
  params: { page: 1, per_page: 20 }
});

const user = await api.post('/auth/login', {
  email: 'user@example.com',
  password: 'password123'
});
```

---

## ⚠️ Lưu Ý Quan Trọng

### 1. CORS
- Backend đã config CORS cho tất cả origins
- Nếu cần restrict, sửa trong `ResponseHelper::cors()`

### 2. Session/Cookies
- Backend dùng PHP sessions
- Frontend cần set `credentials: 'include'` hoặc `withCredentials: true`
- Đảm bảo domain frontend và backend cùng origin hoặc config CORS đúng

### 3. Error Handling
```javascript
try {
  const data = await fetchProducts();
} catch (error) {
  // Xử lý lỗi
  console.error(error.message);
}
```

### 4. Pagination
```javascript
// Response có pagination
{
  "data": [...],
  "meta": {
    "pagination": {
      "current_page": 1,
      "per_page": 20,
      "total": 100,
      "total_pages": 5
    }
  }
}
```

### 5. Filtering & Sorting
```javascript
// Filter
GET /api/products?category_id=1&is_featured=1&price_min=10000&price_max=50000

// Sort
GET /api/products?sort=price&order=ASC

// Pagination
GET /api/products?page=1&per_page=20
```

---

## 🧪 Test APIs

### Sử dụng Postman/Thunder Client

1. **Test Health**
   ```
   GET http://localhost:8000/api/health
   ```

2. **Test Products**
   ```
   GET http://localhost:8000/api/products?page=1&per_page=10
   ```

3. **Test Login**
   ```
   POST http://localhost:8000/api/auth/login
   Body: {
     "email": "admin@example.com",
     "password": "password123"
   }
   ```

---

## 📚 Tài Liệu Đầy Đủ

- `API_ENDPOINTS.md` - Tất cả endpoints chi tiết
- `ADMIN_API_COMPLETE.md` - Admin APIs
- `OPTIMIZATION_SUMMARY.md` - Tối ưu đã làm

---

## ✅ Checklist Trước Khi Bắt Đầu Frontend

- [x] Backend đã chạy được
- [x] Database đã kết nối
- [x] File .env đã config đúng
- [x] Test `/api/health` thành công
- [x] Test một vài endpoints cơ bản
- [x] CORS đã được config
- [x] Error handling đã có

---

## 🚀 Bắt Đầu Frontend!

Backend đã **HOÀN TOÀN SẴN SÀNG** cho frontend integration!

Bạn có thể:
1. ✅ Tạo React components
2. ✅ Tích hợp API calls
3. ✅ Xây dựng UI/UX
4. ✅ Test end-to-end flows

**Chúc bạn thành công! 🎉**


