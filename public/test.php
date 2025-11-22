<?php
/**
 * Simple test - doesn't require Laravel
 * Visit: https://unloqit.com/test.php
 */

header('Content-Type: text/plain');

echo "PHP is working!\n";
echo "Version: " . PHP_VERSION . "\n\n";

echo "MySQL Extensions:\n";
echo "pdo: " . (extension_loaded('pdo') ? "✅" : "❌") . "\n";
echo "pdo_mysql: " . (extension_loaded('pdo_mysql') ? "✅" : "❌") . "\n";
echo "mysql: " . (extension_loaded('mysql') ? "✅" : "❌") . "\n";
echo "mysqli: " . (extension_loaded('mysqli') ? "✅" : "❌") . "\n";

echo "\nIf you see this, PHP is working.\n";
echo "If debug.php shows errors, that's where Laravel is failing.\n";

