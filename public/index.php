<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// FORCE ERROR DISPLAY - Remove this in production after fixing
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Suppress PDO::MYSQL_ATTR_SSL_CA deprecation warnings (PHP 8.5+)
if (PHP_VERSION_ID >= 80500) {
    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        if ($errno === E_DEPRECATED && 
            strpos($errstr, 'PDO::MYSQL_ATTR_SSL_CA') !== false &&
            (strpos($errfile, 'vendor/laravel/framework') !== false || 
             strpos($errfile, 'config/database.php') !== false)) {
            return true;
        }
        return false;
    }, E_ALL);
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
if (!file_exists($autoload = __DIR__.'/../vendor/autoload.php')) {
    http_response_code(500);
    header('Content-Type: text/html');
    die('<h1>ERROR: vendor/autoload.php not found</h1><p>Run: composer install</p>');
}

require $autoload;

// Bootstrap Laravel and handle the request...
try {
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $app->handleRequest(Request::capture());
} catch (Throwable $e) {
    // ALWAYS SHOW ERRORS FOR NOW - Remove after fixing
    http_response_code(500);
    header('Content-Type: text/html');
    
    echo '<!DOCTYPE html><html><head><title>Laravel Error</title>';
    echo '<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}';
    echo 'pre{background:#fff;padding:15px;border:1px solid #ddd;overflow:auto;max-width:100%;}';
    echo 'h1{color:#c00;}</style></head><body>';
    echo '<h1>Laravel Bootstrap Error</h1>';
    echo '<pre>';
    echo '<strong>Error:</strong> ' . htmlspecialchars($e->getMessage()) . "\n\n";
    echo '<strong>File:</strong> ' . htmlspecialchars($e->getFile()) . "\n";
    echo '<strong>Line:</strong> ' . $e->getLine() . "\n\n";
    echo '<strong>Stack Trace:</strong>\n';
    echo htmlspecialchars($e->getTraceAsString());
    echo '</pre>';
    
    // Also log it
    error_log('Laravel Bootstrap Error: ' . $e->getMessage());
    error_log('File: ' . $e->getFile() . ':' . $e->getLine());
    error_log('Trace: ' . $e->getTraceAsString());
    
    exit;
}

