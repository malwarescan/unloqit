# Railway 500 Error - Debugging Guide

## Quick Checks

### 1. Check Health Endpoint
Visit: `https://unloqit.com/health.php`

This will show:
- PHP version
- MySQL extensions status
- Storage permissions
- Environment file status

### 2. Common Causes of 500 Errors

#### Missing APP_KEY
**Symptom:** 500 error, logs show encryption errors

**Fix:**
```bash
railway ssh
php artisan key:generate --force
```

#### Database Connection Failed
**Symptom:** 500 error, logs show "could not find driver" or connection errors

**Fix:**
1. Verify DB environment variables in Railway:
   - `DB_CONNECTION=mysql`
   - `DB_HOST=your-railway-db-host`
   - `DB_PORT=3306`
   - `DB_DATABASE=unloqit`
   - `DB_USERNAME=root`
   - `DB_PASSWORD=your-password`

2. Test connection:
   ```bash
   railway ssh
   php artisan tinker
   DB::connection()->getPdo();
   ```

#### Storage Permissions
**Symptom:** 500 error, logs show permission denied

**Fix:**
```bash
railway ssh
chmod -R 775 storage bootstrap/cache
```

#### Missing Migrations
**Symptom:** 500 error when accessing database-dependent pages

**Fix:**
```bash
railway ssh
php artisan migrate --force
php artisan db:seed --force
```

#### Cache Issues
**Symptom:** 500 error after deployment

**Fix:**
```bash
railway ssh
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Step-by-Step Debugging

### Step 1: Check Railway Logs
1. Go to Railway dashboard
2. Click on your service
3. Go to "Deployments" tab
4. Click on latest deployment
5. Check "Logs" tab

Look for:
- PHP errors
- Database connection errors
- Missing file errors
- Permission errors

### Step 2: Enable Debug Mode (Temporarily)
In Railway environment variables, set:
```
APP_DEBUG=true
```

**⚠️ WARNING:** Only enable in development. Disable immediately after debugging.

This will show detailed error messages instead of generic 500.

### Step 3: Check Health Endpoint
Visit: `https://unloqit.com/health.php`

Expected output:
```json
{
    "status": "ok",
    "php_version": "8.2.x",
    "extensions": {
        "extensions": {
        "pdo": true,
        "pdo_mysql": true,
        "mysql": true,
        "mysqli": true
    }
}
```

If `status` is `error`, fix the issues listed in `errors` array.

### Step 4: Verify Environment Variables
In Railway dashboard → Environment Variables, ensure:

**Required:**
- `APP_KEY` (generate if missing)
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://unloqit.com`

**Database:**
- `DB_CONNECTION=mysql`
- `DB_HOST=...`
- `DB_PORT=3306`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`

### Step 5: Run Post-Deployment Commands
```bash
railway ssh

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed --force

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Most Likely Fixes

### Fix 1: Generate APP_KEY
```bash
railway ssh
php artisan key:generate --force
```

### Fix 2: Fix Storage Permissions
```bash
railway ssh
chmod -R 775 storage bootstrap/cache
```

### Fix 3: Run Migrations
```bash
railway ssh
php artisan migrate --force
```

### Fix 4: Clear Caches
```bash
railway ssh
php artisan optimize:clear
php artisan optimize
```

## If Still Failing

1. **Check Railway Logs** - Look for specific error messages
2. **Enable APP_DEBUG=true** - See detailed error (disable after fixing)
3. **Check health.php** - Verify extensions and permissions
4. **Verify Database Connection** - Test with `php artisan tinker`

---

**Quick Test:**
Visit `https://unloqit.com/health.php` first to see what's wrong.

