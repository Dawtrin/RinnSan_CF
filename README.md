# 🌟 RinnSan Web - Dự Án Web Cafe & Bakery

Một dự án web hiện đại kết hợp **PHP Backend (MVC)** với **React Frontend**, sử dụng cơ sở dữ liệu **SQL Server**. Hệ thống tích hợp các tính năng tự động hóa quy trình đặt hàng F&B.

## 🛠️ Công Nghệ Sử Dụng

### Frontend
- **React 18** - Thư viện xây dựng UI
- **Vite** - Build tool tốc độ cao
- **Tailwind CSS & Bootstrap** - Styling giao diện
- **Axios** - Xử lý HTTP Requests

### Backend
- **PHP 8.x** - Ngôn ngữ phía server
- **SQL Server** - Hệ quản trị cơ sở dữ liệu (Thay vì MySQL)
- **MVC Pattern** - Mô hình kiến trúc phần mềm
- **Custom Router** - Hệ thống định tuyến tùy chỉnh

## ✨ Tính Năng Nổi Bật (Mới Cập Nhật)

1.  **Silent Register (Đăng ký ngầm):** Khách hàng chỉ cần nhập SĐT khi đặt hàng, hệ thống tự động tạo tài khoản và tích hợp vào đơn hàng.
2.  **Loyalty Points (Tích điểm):** Tự động quy đổi doanh thu thành điểm thưởng (10.000đ = 1 điểm) khi đơn hàng hoàn thành.
3.  **Smart Payment Status:** Tự động cập nhật trạng thái "Đã thanh toán" khi Admin xác nhận giao hàng (đối với Chuyển khoản) hoặc Hoàn thành đơn (đối với Tiền mặt).
4.  **Voucher System:** Áp dụng mã giảm giá và tự động đếm số lần sử dụng.



## 🚀 Hướng Dẫn Cài Đặt & Chạy

### 1. Cài Đặt Dependencies

```bash
# Cài đặt thư viện PHP
composer install

# Cài đặt thư viện Node.js (Frontend)
npm install
```

### 2. Cấu Hình Môi Trường

Sửa file `.env`:

```env
# Application
APP_NAME=RINNSAN_WEB
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Ho_Chi_Minh

# Database - SQL SERVER
DB_TYPE=sqlsrv
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=RinnSanCF
DB_USERNAME=
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_TRUSTED_CONNECTION=yes

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Security
ENCRYPTION_KEY=your-secret-key-here
```

### 3. Chạy Dự Án

**Cách 1: Sử dụng script (Linux/Mac/WSL)**

```bash
bash dev.sh
```

**Cách 2: Chạy thủ công**

Terminal 1 - Backend PHP:
```bash
php -S localhost:8000 -t public
```

Terminal 2 - Frontend React:
```bash
npm run dev
```

### 4. Truy Cập Ứng Dụng

- **Frontend (React):** http://localhost:5173
- **Backend (PHP):** http://localhost:8000



## 📝 Linting & Testing

```bash
# Run tests
php -m test/

# Lint code
npm run lint
```


### Port đã được sử dụng

```bash
# Thay đổi port
php -S localhost:8001 -t public
```


### 📝 API Endpoints Chính
POST /api/orders: Tạo đơn hàng (Kèm logic tự tạo tài khoản & trừ kho).

PUT /api/orders/{id}/status: Cập nhật trạng thái đơn & Thanh toán tự động.

GET /api/products: Lấy danh sách sản phẩm.

GET /api/products/best-sellers: Lấy sản phẩm bán chạy (Top 6).


### Dependencies không được cài

```bash
# Clear cache và reinstall
rm -rf vendor node_modules
composer install
npm install
```

## 📦 Dependencies

Xem `composer.json` và `package.json` để xem danh sách đầy đủ.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

MIT License - see LICENSE file for details

## 👨‍💻 Author

RinnSan Vat_Trn


---

**Happy Coding! 🎉**
