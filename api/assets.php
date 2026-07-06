<?php

/**
 * Phục vụ file tĩnh từ public/ trên Vercel (dist, images, videos).
 * vercel-php không tự serve asset build — cần route riêng.
 */
declare(strict_types=1);

$path = $_GET['path'] ?? '';

if ($path === '' || str_contains($path, '..')) {
    http_response_code(400);
    exit('Bad request');
}

$baseDir = realpath(__DIR__ . '/../public');
$file = realpath(__DIR__ . '/../public/' . $path);

if ($baseDir === false || $file === false || !str_starts_with($file, $baseDir) || !is_file($file)) {
    http_response_code(404);
    exit('Not found');
}

$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
$mimes = [
    'js' => 'application/javascript; charset=UTF-8',
    'mjs' => 'application/javascript; charset=UTF-8',
    'css' => 'text/css; charset=UTF-8',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'webp' => 'image/webp',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'ico' => 'image/x-icon',
    'mp4' => 'video/mp4',
    'webm' => 'video/webm',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'json' => 'application/json; charset=UTF-8',
];

header('Content-Type: ' . ($mimes[$ext] ?? 'application/octet-stream'));
header('Content-Length: ' . (string) filesize($file));
readfile($file);
