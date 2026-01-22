# Railway 500 Error - IMMEDIATE FIX

## Step 1: Check Debug Endpoint

Visit: **https://unloqit.com/debug.php**

This will show you EXACTLY what's wrong.

## Step 2: Enable Error Display (Temporary)

In Railway Dashboard → Environment Variables, add:

```
APP_DEBUG=true
```

Then visit https://unloqit.com - you'll see the actual error.

**⚠️ DISABLE AFTER FIXING!**

## Step 3: Check Railway Logs

1. Railway Dashboard → Your Service
2. Click "Deployments"
3. Click latest deployment
4. Click "Logs" tab
5. Look for PHP errors

## Most Common 500 Causes

### 1. Missing APP_KEY
**Fix:**
```bash
railway ssh
php artisan key:generate --force
```

### 2. Database Connection Failed
**Check Railway Environment Variables:**
- `DB_CONNECTION=mysql`
- `DB_HOST=` (should be Railway DB host)
- `DB_PORT=3306`
- `DB_DATABASE=` (your database name)
- `DB_USERNAME=` (usually `root`)
- `DB_PASSWORD=` (Railway DB password)

**Test connection:**
```bash
railway ssh
php artisan tinker
DB::connection()->getPdo();
```

### 3. Storage Not Writable
**Fix:**
```bash
railway ssh
chmod -R 775 storage bootstrap/cache
```

### 4. Missing Vendor Files
**Fix:**
```bash
railway ssh
composer install --no-dev --optimize-autoloader
```

### 5. Cache Issues
**Fix:**
```bash
railway ssh
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Quick Fix All Script

```bash
railway ssh
bash fix-railway.sh
```

Or manually:
```bash
php artisan key:generate --force
chmod -R 775 storage bootstrap/cache
php artisan optimize:clear
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## If Still Failing

1. **Check debug.php** - Shows exact error
2. **Enable APP_DEBUG=true** - See detailed error page
3. **Check Railway logs** - Look for PHP fatal errors
4. **Verify database service is running** - Railway Dashboard → Database service

---

**START HERE:** Visit https://unloqit.com/debug.php

