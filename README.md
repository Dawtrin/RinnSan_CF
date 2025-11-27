# 🌟 RinnSan Web - Dự Án Web Siêu Định Cao

Một dự án web hiện đại kết hợp **PHP backend** với **React frontend**, sử dụng các công nghệ tiên tiến nhất.

## 🛠️ Công Nghệ Sử Dụng

### Frontend
- **React 18** - Library xây dựng UI
- **Vite** - Build tool nhanh
- **Tailwind CSS & Bootstrap** - Styling
- **Framer Motion & GSAP** - Animation
- **React Three Fiber** - 3D Graphics
- **TensorFlow.js & Face API** - AI/ML

### Backend
- **PHP** - Ngôn ngữ lập trình
- **Custom Router** - Định tuyến tùy chỉnh
- **MySQL** - Database
- **Composer** - Package manager

## 📋 Cấu Trúc Dự Án

```
RinnSan_Web/
├── public/                 # Public assets
│   ├── index.php          # Entry point
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── src/                   # Source code
│   ├── Controllers/       # Request handlers
│   ├── Models/            # Database models
│   ├── Views/             # View templates
│   ├── Core/              # Core classes (Router, Database)
│   ├── Helpers/           # Helper functions
│   ├── Middleware/        # Middleware classes
│   ├── Services/          # Business logic
│   └── Validation/        # Validation rules
├── resources/
│   ├── js/                # React components
│   └── css/               # Global styles
├── database/
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── config/                # Configuration files
├── routes/                # Route definitions
├── test/                  # Test files
├── composer.json          # PHP dependencies
├── package.json           # Node.js dependencies
├── vite.config.js         # Vite configuration
└── tailwind.config.js     # Tailwind configuration
```

## 🚀 Cách Chạy Dự Án

### 1. Cài Đặt Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Cấu Hình Môi Trường

Sửa file `.env`:

```env
APP_ENV=local
APP_DEBUG=true
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=rinnsan_web
DB_USERNAME=root
DB_PASSWORD=
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

## 📚 Hướng Dẫn Sử Dụng

### Tạo Route

Thêm route trong `routes/web.php`:

```php
$router->get('/hello', [HelloController::class, 'index']);
$router->post('/submit', [HelloController::class, 'store']);
```

### Tạo Controller

Tạo file `src/Controllers/Web/HelloController.php`:

```php
<?php
namespace Rinnsan\RinnSanWeb\Controllers\Web;

class HelloController
{
    public function index($params = [])
    {
        return $this->render('hello', ['name' => 'World']);
    }
}
```

### Tạo Model

Tạo file `src/Models/User.php`:

```php
<?php
namespace Rinnsan\RinnSanWeb\Models;

class User extends Model
{
    protected $table = 'users';
}
```

### Sử Dụng Database

```php
// Get all
$users = User::all();

// Find by ID
$user = User::find(1);

// Where condition
$admin_users = User::where('role', '=', 'admin');

// Create
User::create(['name' => 'John', 'email' => 'john@example.com']);

// Update
User::update(1, ['name' => 'Jane']);

// Delete
User::delete(1);
```

### Sử Dụng Validation

```php
$validator = new Validator();

$rules = [
    'name' => 'required|min:3|max:255',
    'email' => 'required|email',
];

if ($validator->validate($_POST, $rules)) {
    // Valid
} else {
    $errors = $validator->getErrors();
}
```

### Sử Dụng Helpers

```php
// Get environment variable
$debug = Helper::env('APP_DEBUG', false);

// Redirect
Helper::redirect('/dashboard');

// Escape HTML
$safe = Helper::escape($user_input);

// Generate URL
$url = Helper::url('/users/1');

// Debug (dump and die)
Helper::dd($data);
```

## 🔧 Build Cho Production

```bash
# Build frontend
npm run build

# Output sẽ ở trong public/dist/
```

## 📝 Linting & Testing

```bash
# Run tests
php -m test/

# Lint code
npm run lint
```

## 🐛 Troubleshooting

### Port đã được sử dụng

```bash
# Thay đổi port
php -S localhost:8001 -t public
```

### Database connection error

- Kiểm tra MySQL server có chạy không
- Kiểm tra `.env` file cấu hình đúng
- Kiểm tra tên database, username, password

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

RinnSan Web Team

---

**Happy Coding! 🎉**
