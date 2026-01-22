# Railway 502 Bad Gateway Fix

## Problem

502 Bad Gateway = Railway can't reach your app. The app is crashing before it can respond.

## Root Causes

1. **Missing APP_KEY** - Laravel crashes on bootstrap
2. **Database connection fails** - Laravel crashes trying to connect
3. **Port mismatch** - App listening on wrong port
4. **Conflicting start commands** - Multiple configs fighting each other

## Fix Applied

### 1. Unified Start Command

All configs now use the same command:
```bash
php -d variables_order=EGPCS -S 0.0.0.0:${PORT:-8080} -t public
```

### 2. Better Error Handling

`public/index.php` now catches bootstrap errors and shows them if `APP_DEBUG=true`.

### 3. Simplified nixpacks.toml

Removed conflicting phases, simplified to:
- Install MySQL extensions
- Run composer install
- Start PHP server

## Immediate Steps

### Step 1: Set Required Environment Variables

In Railway Dashboard → Variables, add:

```
APP_KEY=base64:YOUR_GENERATED_KEY
APP_ENV=production
APP_DEBUG=true
APP_URL=https://unloqit.com
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=unloqit
DB_USERNAME=root
DB_PASSWORD=your-password
```

**Generate APP_KEY:**
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

### Step 2: Wait for Redeploy

After pushing, Railway will redeploy automatically.

### Step 3: Test Debug Endpoints

Visit these URLs:
- `https://unloqit.com/index-safe.php` - Shows bootstrap errors
- `https://unloqit.com/test.php` - Simple PHP test
- `https://unloqit.com/debug.php` - Full diagnostic

### Step 4: Check Railway Logs

Railway Dashboard → Service → Deployments → Latest → Logs

Look for:
- PHP fatal errors
- Missing APP_KEY warnings
- Database connection errors

## Common 502 Causes

### Missing APP_KEY
**Symptom:** App crashes immediately  
**Fix:** Set `APP_KEY` in Railway Variables

### Database Connection Failed
**Symptom:** App crashes when Laravel tries to connect  
**Fix:** Set `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in Railway Variables

### Port Mismatch
**Symptom:** App starts but Railway can't reach it  
**Fix:** Ensure Railway target port matches `${PORT}` (usually 8080)

### Missing Vendor Files
**Symptom:** `vendor/autoload.php` not found  
**Fix:** Railway should run `composer install` automatically. Check build logs.

## After Fix Works

Once the site loads:

1. **Disable debug mode:**
   ```
   APP_DEBUG=false
   ```

2. **Run migrations:**
   ```bash
   railway ssh
   php artisan migrate --force
   php artisan db:seed --force
   ```

3. **Cache config:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

**The fix is pushed. Wait for Railway to redeploy, then check the debug endpoints.**

