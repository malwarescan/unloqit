#!/bin/bash
# Railway start script - expands PORT variable for PHP server
# Auto-clears caches on startup to ensure fresh views

php artisan view:clear || true
php artisan config:clear || true
php artisan route:clear || true

PORT=${PORT:-8080}
php -d variables_order=EGPCS -S 0.0.0.0:$PORT -t public

