# Railway 500 Error - Diagnosis

## What the Logs Show

✅ **PHP Server Running** - Port 8080, accepting connections  
❌ **Laravel Returning 500** - All requests to `/` fail  
❌ **Debug Files Missing** - `/health.php` and `/test.php` return 404  

## Root Cause

The debug files aren't deployed yet. After pushing, they'll be available.

## Immediate Steps

### 1. Wait for Deployment
After pushing, Railway will redeploy. Wait 1-2 minutes.

### 2. Check Debug Endpoints (After Deployment)

Visit these URLs:
- `https://unloqit.com/test.php` - Simple PHP test
- `https://unloqit.com/debug.php` - Full Laravel debug
- `https://unloqit.com/index-error.php` - Laravel bootstrap test

### 3. Enable APP_DEBUG

In Railway Dashboard → Environment Variables:
```
APP_DEBUG=true
```

Then visit `https://unloqit.com` - you'll see the actual error.

### 4. Most Likely Causes

Based on the 500 errors, Laravel is bootstrapping but failing. Common causes:

#### A. Missing APP_KEY
**Fix:**
```bash
railway ssh
php artisan key:generate --force
```

#### B. Database Connection Failed
**Check Railway Environment Variables:**
- `DB_CONNECTION=mysql`
- `DB_HOST=` (Railway database host)
- `DB_PORT=3306`
- `DB_DATABASE=` (database name)
- `DB_USERNAME=` (usually `root`)
- `DB_PASSWORD=` (Railway database password)

**Test:**
```bash
railway ssh
php artisan tinker
DB::connection()->getPdo();
```

#### C. Storage Not Writable
**Fix:**
```bash
railway ssh
chmod -R 775 storage bootstrap/cache
```

#### D. Missing Vendor Files
**Fix:**
```bash
railway ssh
composer install --no-dev --optimize-autoloader
```

## Quick Fix Script

After Railway redeploys, SSH in and run:

```bash
railway ssh
bash fix-railway.sh
```

Or manually:
```bash
php artisan key:generate --force
chmod -R 775 storage bootstrap/cache
php artisan optimize:clear
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Next Steps

1. ✅ **Files pushed** - Wait for Railway to redeploy
2. **Visit debug endpoints** - `https://unloqit.com/index-error.php`
3. **Enable APP_DEBUG** - See actual error
4. **Run fixes** - Based on what debug endpoints show

---

**The debug files are now pushed. Wait for Railway to redeploy, then visit the debug endpoints.**

