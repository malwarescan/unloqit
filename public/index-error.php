<?php
/**
 * Error handler - shows errors even if Laravel fails
 * This will help diagnose the 500 error
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><title>Laravel Error Debug</title></head><body>";
echo "<h1>Laravel Bootstrap Error Debug</h1>";
echo "<pre>";

try {
    echo "Step 1: Checking autoloader...\n";
    $autoloadPath = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoloadPath)) {
        throw new Exception("vendor/autoload.php NOT FOUND - Run: composer install");
    }
    require $autoloadPath;
    echo "✅ Autoloader loaded\n\n";
    
    echo "Step 2: Checking bootstrap/app.php...\n";
    $bootstrapPath = __DIR__ . '/../bootstrap/app.php';
    if (!file_exists($bootstrapPath)) {
        throw new Exception("bootstrap/app.php NOT FOUND");
    }
    echo "✅ Bootstrap file exists\n\n";
    
    echo "Step 3: Loading Laravel app...\n";
    $app = require_once $bootstrapPath;
    echo "✅ Laravel app loaded\n\n";
    
    echo "Step 4: Checking environment...\n";
    $envPath = __DIR__ . '/../.env';
    if (file_exists($envPath)) {
        echo "✅ .env file exists\n";
        $env = parse_ini_file($envPath);
        echo "APP_KEY: " . (isset($env['APP_KEY']) && !empty($env['APP_KEY']) ? "SET" : "❌ MISSING") . "\n";
        echo "APP_ENV: " . ($env['APP_ENV'] ?? 'NOT SET') . "\n";
    } else {
        echo "❌ .env file NOT FOUND\n";
    }
    echo "\n";
    
    echo "Step 5: Creating kernel...\n";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Kernel created\n\n";
    
    echo "Step 6: Testing database connection...\n";
    try {
        $db = $app->make('db');
        $pdo = $db->connection()->getPdo();
        echo "✅ Database connection successful\n";
    } catch (Exception $e) {
        echo "❌ Database connection FAILED: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    echo "Step 7: Testing request handling...\n";
    $request = Illuminate\Http\Request::capture();
    echo "✅ Request captured\n";
    
    $response = $kernel->handle($request);
    echo "✅ Response generated\n";
    
    $kernel->terminate($request, $response);
    echo "✅ Kernel terminated\n\n";
    
    echo "=== SUCCESS ===\n";
    echo "Laravel is working! The 500 error might be from a specific route.\n";
    
} catch (Throwable $e) {
    echo "\n=== ERROR ===\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "</pre></body></html>";

