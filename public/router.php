<?php
/**
 * Laravel Router for PHP Built-in Server
 * This file handles routing for php -S built-in server
 * Used in Railway deployment
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Serve static files directly from public/build/
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Route everything else through index.php
require_once __DIR__ . '/index.php';
