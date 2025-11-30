# ✅ Kiểm Tra File .env

## 📋 Checklist

Vui lòng kiểm tra file `.env` của bạn có đầy đủ các thông tin sau:

### ✅ Bắt Buộc Phải Có:

```env
# Application
APP_NAME=RinnSan Web
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Ho_Chi_Minh

# Database Type - QUAN TRỌNG!
DB_TYPE=sqlsrv

# Database Connection
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=RinnSanCF
DB_USERNAME=sa
DB_PASSWORD=your_password_here
DB_CHARSET=utf8mb4
```

### ⚠️ Lưu Ý Quan Trọng:

1. **DB_TYPE** phải là `sqlsrv` (vì database của bạn là SQL Server)
   - ❌ KHÔNG dùng `mysql` 
   - ✅ Dùng `sqlsrv`

2. **DB_DATABASE** phải là `RinnSanCF` (theo schema)
   - ❌ KHÔNG dùng `rinnsan_web`

3. **DB_PORT** cho SQL Server là `1433`
   - ❌ KHÔNG dùng `3306` (đó là port MySQL)

4. **DB_PASSWORD** - Thay bằng password thật của bạn
   - ❌ KHÔNG để trống nếu database có password

### 🔍 Kiểm Tra Nhanh:

Sau khi tạo file `.env`, test kết nối:

```bash
# Chạy server
php -S localhost:8000 -t public

# Test API
curl http://localhost:8000/api/health
```

Nếu thấy `"database": "connected"` → ✅ Thành công!
Nếu thấy lỗi → Kiểm tra lại thông tin trong `.env`

---

## 📝 Dán Nội Dung File .env Của Bạn

Nếu bạn muốn tôi kiểm tra file `.env` của bạn, vui lòng dán nội dung vào đây (ẩn password nếu cần):

```
[Paste nội dung .env của bạn ở đây]
```

Tôi sẽ kiểm tra:
- ✅ Các biến bắt buộc có đủ không
- ✅ Giá trị có đúng không (DB_TYPE, DB_DATABASE, DB_PORT)
- ✅ Format có đúng không
- ✅ Có thiếu gì không

