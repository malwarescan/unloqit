<?php
/**
 * Debug endpoint - shows actual error
 * Remove this file after fixing!
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain');

echo "=== UNLOQIT DEBUG ===\n\n";

// Check PHP version
echo "PHP Version: " . PHP_VERSION . "\n\n";

// Check extensions
echo "=== EXTENSIONS ===\n";
$extensions = ['pdo', 'pdo_mysql', 'mysql', 'mysqli', 'mbstring', 'openssl', 'json'];
foreach ($extensions as $ext) {
    echo (extension_loaded($ext) ? "✅" : "❌") . " $ext\n";
}

// Check .env file
echo "\n=== ENVIRONMENT ===\n";
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    echo "✅ .env exists\n";
    $env = parse_ini_file($envPath);
    echo "APP_KEY: " . (isset($env['APP_KEY']) && !empty($env['APP_KEY']) ? "SET" : "❌ MISSING") . "\n";
    echo "APP_ENV: " . ($env['APP_ENV'] ?? 'NOT SET') . "\n";
    echo "DB_CONNECTION: " . ($env['DB_CONNECTION'] ?? 'NOT SET') . "\n";
    echo "DB_HOST: " . (isset($env['DB_HOST']) ? "SET" : "❌ MISSING") . "\n";
} else {
    echo "❌ .env file NOT FOUND\n";
}

// Check storage permissions
echo "\n=== PERMISSIONS ===\n";
$storagePath = __DIR__ . '/../storage';
$bootstrapCache = __DIR__ . '/../bootstrap/cache';
echo "Storage writable: " . (is_writable($storagePath) ? "✅" : "❌") . "\n";
echo "Bootstrap cache writable: " . (is_writable($bootstrapCache) ? "✅" : "❌") . "\n";

// Try to bootstrap Laravel
echo "\n=== LARAVEL BOOTSTRAP ===\n";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "✅ Autoloader loaded\n";
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "✅ Laravel app bootstrapped\n";
    
    // Try database connection
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Kernel created\n";
    
    // Check config
    $config = $app->make('config');
    echo "APP_KEY from config: " . ($config->get('app.key') ? "SET" : "❌ MISSING") . "\n";
    echo "DB_CONNECTION from config: " . $config->get('database.default') . "\n";
    
    // Try DB connection
    try {
        $db = $app->make('db');
        $pdo = $db->connection()->getPdo();
        echo "✅ Database connection successful\n";
    } catch (Exception $e) {
        echo "❌ Database connection FAILED: " . $e->getMessage() . "\n";
    }
    
} catch (Throwable $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== END DEBUG ===\n";

