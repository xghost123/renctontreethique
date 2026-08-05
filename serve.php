<?php
/**
 * Custom server launcher that ensures php.ini.dev is used
 * This wrapper ensures OpenSSL and other extensions are loaded
 */

// Ensure we're using the correct PHP INI file
if (!ini_get('extension_dir') || strpos(php_ini_loaded_file() ?: '', 'php.ini.dev') === false) {
    $ini_file = __DIR__ . DIRECTORY_SEPARATOR . 'php.ini.dev';
    if (file_exists($ini_file)) {
        // Try to reload with the custom ini
        putenv('PHPRC=' . $ini_file);
    }
}

// Verify extensions are loaded
if (!extension_loaded('openssl')) {
    error_log("WARNING: OpenSSL not loaded. Check php.ini.dev configuration.");
}

if (!extension_loaded('pdo_sqlite')) {
    error_log("WARNING: pdo_sqlite not loaded. Check php.ini.dev configuration.");
}

// Load Laravel's built-in server
require __DIR__ . '/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php';
