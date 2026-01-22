#!/bin/bash
# Railway start script - expands PORT variable for PHP server
# Ensures storage directories exist and clears caches

# Create storage directories if they don't exist
mkdir -p storage/framework/views
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set permissions (Railway may need this)
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Only clear caches if Laravel is available (after composer install)
if [ -f "vendor/autoload.php" ]; then
    php artisan view:clear || true
    php artisan config:clear || true
    php artisan route:clear || true
fi

PORT=${PORT:-8080}
php -d variables_order=EGPCS -S 0.0.0.0:$PORT -t public

