# 🔧 Sửa File .env

## ⚠️ Vấn Đề Phát Hiện

File `.env` của bạn có **2 vấn đề**:

### 1. **Biến không khớp với code**
- ❌ File .env có: `DB_CONNECTION=sqlsrv`
- ✅ Code đang tìm: `DB_TYPE` (không phải `DB_CONNECTION`)

### 2. **Password chưa được thay**
- ❌ `DB_PASSWORD=your_password` (placeholder)
- ✅ Cần thay bằng password thật của SQL Server

---

## ✅ Cách Sửa

### Option 1: Sửa File .env (KHUYẾN NGHỊ)

Thay đổi trong file `.env`:

```env
# Thay đổi dòng này:
DB_CONNECTION=sqlsrv

# Thành:
DB_TYPE=sqlsrv
```

Và thay password:
```env
DB_PASSWORD=your_actual_password_here
```

### Option 2: Sửa Code (Nếu muốn giữ DB_CONNECTION)

Sửa `src/Core/Database.php`:
- Thay `$_ENV['DB_TYPE']` thành `$_ENV['DB_CONNECTION']`

---

## 📝 File .env Đúng Chuẩn

```env
# Application
APP_NAME=RINNSAN_WEB
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Ho_Chi_Minh

# Database - SQL SERVER
DB_TYPE=sqlsrv          # ← SỬA TỪ DB_CONNECTION
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=RinnSanCF
DB_USERNAME=sa
DB_PASSWORD=your_actual_password  # ← THAY PASSWORD THẬT
DB_CHARSET=utf8mb4

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Security
ENCRYPTION_KEY=your-secret-key-here
```

---

## ✅ Checklist

- [ ] Đổi `DB_CONNECTION` → `DB_TYPE`
- [ ] Thay `DB_PASSWORD` bằng password thật
- [ ] Test kết nối: `http://localhost:8000/api/health`

---

## 🔒 Lưu Ý Bảo Mật

File `.env` đã được gitignore (đúng rồi!) - không commit lên git.

