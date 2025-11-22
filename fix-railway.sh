#!/bin/bash
# Railway deployment fix script
# Run this in Railway SSH: railway ssh → bash fix-railway.sh

echo "🔧 Fixing Railway deployment..."

# Generate APP_KEY if missing
echo "1. Generating APP_KEY..."
php artisan key:generate --force

# Fix storage permissions
echo "2. Fixing storage permissions..."
chmod -R 775 storage bootstrap/cache

# Clear all caches
echo "3. Clearing caches..."
php artisan optimize:clear

# Run migrations
echo "4. Running migrations..."
php artisan migrate --force

# Run seeders (now idempotent - safe to run multiple times)
echo "5. Seeding database..."
php artisan db:seed --force

# Rebuild caches
echo "6. Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verify MySQL extensions
echo "7. Verifying MySQL extensions..."
php -m | grep -i mysql
php -m | grep -i pdo

echo ""
echo "✅ All fixes applied!"
echo ""
echo "Next: Visit https://unloqit.com to verify"

