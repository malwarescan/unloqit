#!/bin/bash
# Production deployment cache clearing script
# Run this after deploying new code to ensure views/routes/config are fresh

echo "Clearing all Laravel caches..."

# Put site in maintenance mode (optional, uncomment if needed)
# php artisan down || true

# Clear all caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild optimized caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# If using PHP-FPM, you may need to reload it:
# sudo service php8.2-fpm reload
# OR restart your container/service

echo "Cache clearing complete!"
echo ""
echo "VERIFICATION STEPS:"
echo "1. View source of homepage - confirm 'Cleveland' is NOT in H1"
echo "2. Check nav links point to /services and /locations (not /cleveland-locksmith)"
echo "3. Test /cleveland-locksmith redirects to /locksmith/oh/cleveland (301, not 404)"
echo "4. Verify canonical URLs use www.unloqit.com"

# Take site out of maintenance mode
# php artisan up || true
