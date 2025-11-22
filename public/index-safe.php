<?php
/**
 * Safe bootstrap - shows errors instead of crashing
 * This helps diagnose 502 errors
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html><html><head><title>Laravel Bootstrap</title><style>body{font-family:monospace;padding:20px;background:#f5f5f5;}pre{background:#fff;padding:15px;border:1px solid #ddd;overflow:auto;}</style></head><body>";
echo "<h1>Laravel Bootstrap Test</h1>";
echo "<pre>";

try {
    // Step 1: Check autoloader
    echo "1. Checking autoloader...\n";
    $autoloadPath = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($autoloadPath)) {
        throw new Exception("❌ vendor/autoload.php NOT FOUND\nRun: composer install");
    }
    require $autoloadPath;
    echo "✅ Autoloader loaded\n\n";
    
    // Step 2: Check bootstrap
    echo "2. Loading Laravel bootstrap...\n";
    $bootstrapPath = __DIR__ . '/../bootstrap/app.php';
    if (!file_exists($bootstrapPath)) {
        throw new Exception("❌ bootstrap/app.php NOT FOUND");
    }
    
    $app = require_once $bootstrapPath;
    echo "✅ Laravel app created\n\n";
    
    // Step 3: Check environment
    echo "3. Checking environment...\n";
    $appKey = env('APP_KEY');
    if (empty($appKey)) {
        echo "⚠️  APP_KEY is NOT SET\n";
        echo "   Set in Railway Dashboard → Variables\n";
        echo "   Generate: php -r \"echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;\"\n\n";
    } else {
        echo "✅ APP_KEY is set\n\n";
    }
    
    // Step 4: Test database connection
    echo "4. Testing database connection...\n";
    try {
        $db = $app->make('db');
        $pdo = $db->connection()->getPdo();
        echo "✅ Database connection successful\n\n";
    } catch (Exception $e) {
        echo "❌ Database connection FAILED\n";
        echo "   Error: " . $e->getMessage() . "\n";
        echo "   Check Railway Environment Variables:\n";
        echo "   - DB_CONNECTION=mysql\n";
        echo "   - DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD\n\n";
    }
    
    // Step 5: Test request handling
    echo "5. Testing request handling...\n";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::capture();
    
    echo "✅ Kernel created\n";
    echo "✅ Request captured\n";
    
    $response = $kernel->handle($request);
    echo "✅ Response generated\n\n";
    
    echo "=== SUCCESS ===\n";
    echo "Laravel is working! The 502 error might be from:\n";
    echo "- Port mismatch\n";
    echo "- Railway routing issue\n";
    echo "- Missing environment variables\n";
    
    $kernel->terminate($request, $response);
    
} catch (Throwable $e) {
    echo "\n=== FATAL ERROR ===\n";
    echo "Message: " . htmlspecialchars($e->getMessage()) . "\n";
    echo "File: " . htmlspecialchars($e->getFile()) . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n";
    echo htmlspecialchars($e->getTraceAsString()) . "\n";
    
    echo "\n=== FIX THIS ERROR ===\n";
    if (strpos($e->getMessage(), 'APP_KEY') !== false) {
        echo "Set APP_KEY in Railway Dashboard → Variables\n";
    }
    if (strpos($e->getMessage(), 'database') !== false || strpos($e->getMessage(), 'DB_') !== false) {
        echo "Set database variables in Railway Dashboard → Variables\n";
    }
}

echo "</pre></body></html>";

