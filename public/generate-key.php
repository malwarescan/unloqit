<?php
/**
 * Generate APP_KEY for Railway
 * Visit: https://unloqit.com/generate-key.php
 * Copy the output and add to Railway Environment Variables
 */

header('Content-Type: text/plain');

$key = 'base64:' . base64_encode(random_bytes(32));

echo "=== APP_KEY FOR RAILWAY ===\n\n";
echo "Copy this and add to Railway Dashboard → Variables:\n\n";
echo "APP_KEY=" . $key . "\n\n";
echo "Steps:\n";
echo "1. Copy the APP_KEY value above\n";
echo "2. Go to Railway Dashboard → Your Service → Variables\n";
echo "3. Click 'New Variable'\n";
echo "4. Name: APP_KEY\n";
echo "5. Value: Paste the key\n";
echo "6. Click 'Add'\n";
echo "7. Railway will auto-redeploy\n";

