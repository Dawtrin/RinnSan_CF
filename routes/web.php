<?php

// WEB ROUTES - Trả về HTML cho SPA

$router->get('/', function () {
    $manifestPath = __DIR__ . '/../public/dist/manifest.json';
    $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;
    $key = isset($manifest['resources/src/main.jsx']) ? 'resources/src/main.jsx' : 'src/main.jsx';
    $entryJs = $manifest[$key]['file'] ?? 'assets/main.js';
    $cssFiles = $manifest[$key]['css'] ?? [];
    echo '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    foreach ($cssFiles as $css) {
        echo '<link rel="stylesheet" href="/dist/' . htmlspecialchars($css) . '">';
    }
    echo '<title>RinnSan Web</title></head><body><div id="root"></div>';
    echo '<script type="module" src="/dist/' . htmlspecialchars($entryJs) . '"></script>';
    echo '</body></html>';
});

$router->get('/about', function () {
    // Phục vụ cùng SPA để client-side router xử lý
    $manifestPath = __DIR__ . '/../public/dist/manifest.json';
    $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;
    $key = isset($manifest['resources/src/main.jsx']) ? 'resources/src/main.jsx' : 'src/main.jsx';
    $entryJs = $manifest[$key]['file'] ?? 'assets/main.js';
    $cssFiles = $manifest[$key]['css'] ?? [];
    echo '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    foreach ($cssFiles as $css) {
        echo '<link rel="stylesheet" href="/dist/' . htmlspecialchars($css) . '">';
    }
    echo '<title>RinnSan Web</title></head><body><div id="root"></div>';
    echo '<script type="module" src="/dist/' . htmlspecialchars($entryJs) . '"></script>';
    echo '</body></html>';
});

$router->get('/landing', function () {
    $manifestPath = __DIR__ . '/../public/dist/manifest.json';
    $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;
    $key = isset($manifest['resources/src/main.jsx']) ? 'resources/src/main.jsx' : 'src/main.jsx';
    $entryJs = $manifest[$key]['file'] ?? 'assets/main.js';
    $cssFiles = $manifest[$key]['css'] ?? [];
    echo '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    foreach ($cssFiles as $css) {
        echo '<link rel="stylesheet" href="/dist/' . htmlspecialchars($css) . '">';
    }
    echo '<title>RinnSan Web</title></head><body><div id="root"></div>';
    echo '<script type="module" src="/dist/' . htmlspecialchars($entryJs) . '"></script>';
    echo '</body></html>';
});

$router->get('/admin/suppliers', function () {
    $manifestPath = __DIR__ . '/../public/dist/manifest.json';
    $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;
    $key = isset($manifest['resources/src/main.jsx']) ? 'resources/src/main.jsx' : 'src/main.jsx';
    $entryJs = $manifest[$key]['file'] ?? 'assets/main.js';
    $cssFiles = $manifest[$key]['css'] ?? [];
    echo '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">';
    foreach ($cssFiles as $css) {
        echo '<link rel="stylesheet" href="/dist/' . htmlspecialchars($css) . '">';
    }
    echo '<title>RinnSan Web</title></head><body><div id="root"></div>';
    echo '<script type="module" src="/dist/' . htmlspecialchars($entryJs) . '"></script>';
    echo '</body></html>';
});

// Lưu ý: các route SPA khác có thể được thêm tương tự hoặc cấu hình rewrite để fallback về '/'
