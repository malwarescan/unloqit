# Production Deployment Instructions

## ✅ Code Pushed to Git
**Commit**: `e1735f2` - Production fixes: explicit redirects, canonical host, noindex for ineligible pages

## 🚀 Deployment Steps

### Step 1: Deploy Code to Production Server

**If using Railway/Heroku/Auto-deploy:**
- Code should auto-deploy from `main` branch
- Wait for deployment to complete

**If manual deployment:**
```bash
# SSH to production server
ssh user@your-production-server

# Navigate to project directory
cd /path/to/unloqit

# Pull latest code
git pull origin main

# Install dependencies (if needed)
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### Step 2: Clear All Caches (CRITICAL)

**Run the cache clearing script:**
```bash
cd /path/to/unloqit
bash deploy-clear-cache.sh
```

**OR manually run:**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**If using PHP-FPM, reload it:**
```bash
sudo service php8.2-fpm reload
# OR
sudo systemctl reload php-fpm
# OR restart your container/service
```

### Step 3: Verify Deployment

Run these verification commands (from any machine):

#### A. Homepage - No Cleveland References
```bash
curl -s https://www.unloqit.com/ | grep -i "cleveland" | head -5
# Should return ZERO results
```

#### B. Legacy URL Redirects (MUST be 301, never 404)
```bash
# Test base redirect
curl -I https://www.unloqit.com/cleveland-locksmith
# Expected: HTTP/1.1 301 Moved Permanently
# Location: https://www.unloqit.com/locksmith/oh/cleveland

# Test service redirect
curl -I https://www.unloqit.com/cleveland-locksmith/car-lockout
# Expected: HTTP/1.1 301 Moved Permanently
# Location: https://www.unloqit.com/locksmith/oh/cleveland/car-lockout

# Test neighborhood redirect
curl -I https://www.unloqit.com/cleveland-locksmith/car-lockout/ohio-city
# Expected: HTTP/1.1 301 Moved Permanently
# Location: https://www.unloqit.com/locksmith/oh/cleveland/car-lockout/ohio-city
```

#### C. Canonical Host Redirect
```bash
# Test non-www redirect
curl -I http://unloqit.com/
# Expected: HTTP/1.1 301 → https://www.unloqit.com/

curl -I https://unloqit.com/
# Expected: HTTP/1.1 301 → https://www.unloqit.com/
```

#### D. Nav/Footer Links (Zero 404s)
```bash
# Test nav links
curl -I https://www.unloqit.com/services
# Expected: HTTP/1.1 200 OK

curl -I https://www.unloqit.com/locations
# Expected: HTTP/1.1 200 OK

# Test footer service links
curl -I https://www.unloqit.com/services/car-lockout
# Expected: HTTP/1.1 200 OK
```

#### E. Trailing Slash Normalization
```bash
curl -I https://www.unloqit.com/locksmith/oh/cleveland/
# Expected: HTTP/1.1 301 → /locksmith/oh/cleveland (no trailing slash)
```

#### F. Lowercase Normalization
```bash
curl -I https://www.unloqit.com/LOCKSMITH/OH/CLEVELAND
# Expected: HTTP/1.1 301 → /locksmith/oh/cleveland (lowercase)
```

#### G. Sitemaps
```bash
curl -I https://www.unloqit.com/sitemap.xml
# Expected: HTTP/1.1 200 OK

curl -I https://www.unloqit.com/sitemap-services.xml
# Expected: HTTP/1.1 200 OK

curl -I https://www.unloqit.com/sitemap-locations.xml
# Expected: HTTP/1.1 200 OK
```

#### H. Schema Validation
1. Open https://www.unloqit.com/ in browser
2. View page source (Ctrl+U / Cmd+U)
3. Search for "LocalBusiness" - should NOT exist
4. Search for "WebSite" - should exist with SearchAction
5. Search for "Organization" - should exist with @id: https://www.unloqit.com/#organization

## ⚠️ Critical Notes

1. **View Cache**: Laravel caches compiled Blade views. If you don't clear view cache, the old homepage will still be served even after deployment.

2. **OPcache**: If using PHP-FPM with OPcache enabled, you MUST reload PHP-FPM or restart the service, otherwise old code may still be served.

3. **Redirect Order**: The explicit redirect routes are at the TOP of `routes/web.php`. If they're not working, check that they're before any other routes.

4. **Canonical Host**: The `ForceCanonicalHost` middleware runs FIRST. If you're still seeing non-www URLs, check middleware registration order.

## 🐛 Troubleshooting

### If legacy URLs still 404:
- Check that redirect routes are at the TOP of `routes/web.php`
- Clear route cache: `php artisan route:clear && php artisan route:cache`
- Verify routes: `php artisan route:list | grep cleveland`

### If homepage still shows Cleveland:
- Clear view cache: `php artisan view:clear`
- Reload PHP-FPM or restart container
- Check that `resources/views/pages/home.blade.php` has been updated

### If canonical host not redirecting:
- Check middleware is registered in `bootstrap/app.php`
- Verify middleware runs first (prepend, not append)
- Clear config cache: `php artisan config:clear && php artisan config:cache`

## ✅ Success Criteria

After deployment, ALL of these must pass:

- [ ] Homepage HTML contains zero "Cleveland" references
- [ ] `/cleveland-locksmith` returns 301 (not 404)
- [ ] `/cleveland-locksmith/car-lockout` returns 301 (not 404)
- [ ] Nav "Services" link works (200 OK)
- [ ] Nav "Locations" link works (200 OK)
- [ ] Footer service links work (200 OK)
- [ ] Canonical host is `www.unloqit.com` (non-www redirects)
- [ ] Sitemaps are accessible (200 OK)
- [ ] Homepage schema has WebSite + SearchAction (no LocalBusiness)

---

**Ready to deploy!** Follow steps 1-3 above, then verify using the curl commands.
