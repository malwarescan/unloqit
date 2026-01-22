#!/bin/bash
# Verification script to check PHP extensions are loaded

echo "=== Checking PHP Extensions ==="
echo ""
echo "PHP Version:"
php -v
echo ""
echo "=== MySQL Extensions ==="
php -m | grep -i mysql || echo "❌ MySQL extensions NOT found"
echo ""
echo "=== PDO Extensions ==="
php -m | grep -i pdo || echo "❌ PDO extensions NOT found"
echo ""
echo "=== Required Extensions Check ==="
if php -m | grep -q "pdo_mysql"; then
    echo "✅ pdo_mysql found"
else
    echo "❌ pdo_mysql NOT found"
fi

if php -m | grep -q "^pdo$"; then
    echo "✅ pdo found"
else
    echo "❌ pdo NOT found"
fi

echo ""
echo "=== All Loaded Extensions ==="
php -m

