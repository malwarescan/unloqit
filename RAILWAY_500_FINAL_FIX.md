# Railway 500 Error - FINAL FIX

## What I Just Did

1. **Forced error display** in `index.php` - Now shows exact error instead of generic 500
2. **Added environment checker** at `/check-env.php` - Shows what Railway has configured

## IMMEDIATE ACTIONS

### Step 1: Visit These URLs (After Railway Redeploys)

1. **`https://unloqit.com/check-env.php`**
   - Shows which environment variables are missing
   - Copy the exact variable names and add them to Railway

2. **`https://unloqit.com/index.php`**
   - Now shows the EXACT error message
   - This will tell you what's wrong

### Step 2: Set Missing Variables in Railway

Go to **Railway Dashboard → Your Service → Variables** and add:

#### CRITICAL - Must Have:
```
APP_KEY=base64:YOUR_GENERATED_KEY
APP_ENV=production
APP_URL=https://unloqit.com
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=unloqit
DB_USERNAME=root
DB_PASSWORD=your-password
```

#### Generate APP_KEY:
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

### Step 3: Check Railway Logs

Railway Dashboard → Service → Deployments → Latest → Logs

Look for:
- PHP fatal errors
- Missing APP_KEY warnings
- Database connection errors

## Common 500 Causes

### 1. Missing APP_KEY
**Error:** "No application encryption key has been specified"
**Fix:** Set `APP_KEY` in Railway Variables

### 2. Database Connection Failed
**Error:** "SQLSTATE[HY000] [2002] Connection refused" or similar
**Fix:** Set `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` in Railway Variables

### 3. Missing Vendor Files
**Error:** "vendor/autoload.php not found"
**Fix:** Railway should run `composer install` automatically. Check build logs.

### 4. Storage Not Writable
**Error:** "The stream or file could not be opened"
**Fix:** Railway SSH → `chmod -R 775 storage bootstrap/cache`

## After Fixing

Once the site loads:

1. **Disable error display** (edit `public/index.php` to remove forced error display)
2. **Set APP_DEBUG=false** in Railway Variables
3. **Run migrations:**
   ```bash
   railway ssh
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

**The fix is pushed. Visit `/check-env.php` to see what's missing.**

