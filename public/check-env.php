<?php
/**
 * Check environment variables - shows what Railway has set
 */
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Environment Check</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .ok { color: green; }
        .missing { color: red; font-weight: bold; }
        pre { background: #fff; padding: 15px; border: 1px solid #ddd; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <h1>Railway Environment Variables Check</h1>
    <pre>
<?php
$required = [
    'APP_KEY' => 'Required - Generate with: php -r "echo \'base64:\' . base64_encode(random_bytes(32)) . PHP_EOL;"',
    'APP_ENV' => 'Required - Set to: production',
    'APP_DEBUG' => 'Optional - Set to: true (for debugging)',
    'APP_URL' => 'Required - Set to: https://unloqit.com',
    'DB_CONNECTION' => 'Required - Set to: mysql',
    'DB_HOST' => 'Required - From Railway database service',
    'DB_PORT' => 'Required - Usually: 3306',
    'DB_DATABASE' => 'Required - Database name',
    'DB_USERNAME' => 'Required - Database username',
    'DB_PASSWORD' => 'Required - Database password',
];

echo "=== REQUIRED VARIABLES ===\n\n";

foreach ($required as $var => $help) {
    $value = getenv($var);
    if ($value === false || empty($value)) {
        echo "❌ <span class='missing'>$var</span> - NOT SET\n";
        echo "   $help\n\n";
    } else {
        $display = $var === 'DB_PASSWORD' ? str_repeat('*', strlen($value)) : $value;
        echo "✅ <span class='ok'>$var</span> = $display\n\n";
    }
}

echo "\n=== ALL ENVIRONMENT VARIABLES ===\n\n";
$all = getenv();
ksort($all);
foreach ($all as $key => $value) {
    if (strpos($key, 'DB_') === 0 || strpos($key, 'APP_') === 0) {
        $display = strpos($key, 'PASSWORD') !== false ? str_repeat('*', strlen($value)) : $value;
        echo "$key = $display\n";
    }
}

echo "\n=== PHP INFO ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Loaded Extensions:\n";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    if (strpos($ext, 'mysql') !== false || strpos($ext, 'pdo') !== false) {
        echo "  ✅ $ext\n";
    }
}
?>
    </pre>
    
    <h2>Next Steps</h2>
    <ol>
        <li>Go to Railway Dashboard → Your Service → Variables</li>
        <li>Add any missing variables shown above</li>
        <li>Railway will auto-redeploy</li>
        <li>Refresh this page to verify</li>
    </ol>
</body>
</html>

