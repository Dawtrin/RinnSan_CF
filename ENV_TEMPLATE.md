# 📝 File .env Template

## ⚠️ QUAN TRỌNG: Bạn cần tạo file `.env` ở thư mục gốc!

Copy nội dung dưới đây và tạo file `.env`:

```env
# ============================================
# APPLICATION CONFIGURATION
# ============================================
APP_NAME=RinnSan Web
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Ho_Chi_Minh

# ============================================
# DATABASE CONFIGURATION
# ============================================

# Chọn loại database: sqlsrv (SQL Server) hoặc mysql (MySQL)
DB_TYPE=sqlsrv

# SQL Server Configuration
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=RinnSanCF
DB_USERNAME=sa
DB_PASSWORD=your_password_here
DB_CHARSET=utf8mb4

# MySQL Configuration (nếu dùng MySQL thay vì SQL Server)
# DB_TYPE=mysql
# DB_HOST=localhost
# DB_PORT=3306
# DB_DATABASE=rinnsan_web
# DB_USERNAME=root
# DB_PASSWORD=your_password_here
# DB_CHARSET=utf8mb4

# ============================================
# SESSION CONFIGURATION
# ============================================
SESSION_LIFETIME=120

# ============================================
# API CONFIGURATION
# ============================================
API_PREFIX=api

# ============================================
# UPLOAD CONFIGURATION
# ============================================
UPLOAD_MAX_SIZE=5242880
UPLOAD_PATH=public/uploads
```

## 🔧 Cách Tạo File .env

### Windows:
1. Mở Notepad hoặc text editor
2. Copy nội dung trên
3. Save As với tên `.env` (không có extension)
4. Đặt ở thư mục gốc của project (cùng cấp với composer.json)

### Linux/Mac:
```bash
cd /path/to/RinnSan_Web
nano .env
# Paste nội dung và save (Ctrl+X, Y, Enter)
```

## ✅ Kiểm Tra File .env

Sau khi tạo file, kiểm tra:
1. File có tên chính xác là `.env` (không phải `.env.txt`)
2. File ở thư mục gốc của project
3. Thay đổi các giá trị database theo cấu hình của bạn:
   - `DB_HOST`: Địa chỉ server
   - `DB_PORT`: Port (1433 cho SQL Server, 3306 cho MySQL)
   - `DB_DATABASE`: Tên database
   - `DB_USERNAME`: Username
   - `DB_PASSWORD`: Password

## 🚨 Lưu Ý Bảo Mật

- **KHÔNG** commit file `.env` lên git
- Thêm `.env` vào `.gitignore`
- Sử dụng `.env.example` cho team (không có password thật)

