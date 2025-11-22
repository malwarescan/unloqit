<?php
/**
 * Test if assets are accessible
 */
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Asset Test</title>
</head>
<body>
    <h1>Asset Accessibility Test</h1>
    <pre>
<?php
$manifestPath = __DIR__ . '/build/manifest.json';
echo "Manifest exists: " . (file_exists($manifestPath) ? "YES" : "NO") . "\n";
if (file_exists($manifestPath)) {
    $manifest = json_decode(file_get_contents($manifestPath), true);
    echo "Manifest content:\n";
    print_r($manifest);
    
    if (isset($manifest['resources/css/app.css']['file'])) {
        $cssFile = __DIR__ . '/build/' . $manifest['resources/css/app.css']['file'];
        echo "\nCSS file path: " . $cssFile . "\n";
        echo "CSS file exists: " . (file_exists($cssFile) ? "YES" : "NO") . "\n";
        echo "CSS file URL: /build/" . $manifest['resources/css/app.css']['file'] . "\n";
    }
}

echo "\nAPP_ENV: " . getenv('APP_ENV') . "\n";
echo "APP_DEBUG: " . getenv('APP_DEBUG') . "\n";
?>
    </pre>
</body>
</html>

