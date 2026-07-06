<?php

/**
 * Vercel PHP entrypoint.
 * Mọi request (trừ static đã route riêng) đi qua public/index.php.
 */
require __DIR__ . '/../public/index.php';
