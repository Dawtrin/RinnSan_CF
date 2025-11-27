<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'RinnSan Web'; ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="/" class="logo">RinnSan</a>
                <ul class="nav-links">
                    <li><a href="/">Trang chủ</a></li>
                    <li><a href="/about">Giới thiệu</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="content">
        <?php echo $content ?? ''; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 RinnSan Web. All rights reserved.</p>
        </div>
    </footer>

    <script src="/js/app.js"></script>
</body>
</html>
