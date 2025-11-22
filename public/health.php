<?php
/**
 * Health check endpoint for Railway
 * Use this to verify PHP is working and extensions are loaded
 */

header('Content-Type: application/json');

$health = [
    'status' => 'ok',
    'php_version' => PHP_VERSION,
    'extensions' => [],
    'errors' => [],
];

// Check MySQL extensions
$extensions = ['pdo', 'pdo_mysql', 'mysql', 'mysqli'];
foreach ($extensions as $ext) {
    $health['extensions'][$ext] = extension_loaded($ext);
    if (!extension_loaded($ext)) {
        $health['errors'][] = "Missing extension: {$ext}";
        $health['status'] = 'error';
    }
}

// Check if .env exists
$health['env_exists'] = file_exists(__DIR__ . '/../.env');

// Check storage permissions
$storagePath = __DIR__ . '/../storage';
$health['storage_writable'] = is_writable($storagePath);
if (!is_writable($storagePath)) {
    $health['errors'][] = 'Storage directory is not writable';
    $health['status'] = 'error';
}

// Check bootstrap cache
$bootstrapCache = __DIR__ . '/../bootstrap/cache';
$health['bootstrap_cache_writable'] = is_writable($bootstrapCache);
if (!is_writable($bootstrapCache)) {
    $health['errors'][] = 'Bootstrap cache directory is not writable';
    $health['status'] = 'error';
}

http_response_code($health['status'] === 'ok' ? 200 : 500);
echo json_encode($health, JSON_PRETTY_PRINT);

