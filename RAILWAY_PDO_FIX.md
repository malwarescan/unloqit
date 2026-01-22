# Railway MySQL PDO Extension Fix - SIMPLIFIED

## Problem

Deployment failing with:
```
PDOException: could not find driver
```

**Root Cause:** Nixpacks auto-detects PHP but doesn't install `pdo_mysql` extension that Laravel requires.

## Solution - ONE FILE

### File: `nixpacks.toml` (in repo root)

```toml
[phases.setup]
aptPkgs = ["php8.2-mysql"]

[phases.build]
cmds = ["composer install --no-dev --optimize-autoloader"]

[start]
cmd = "php -d variables_order=EGPCS -S 0.0.0.0:${PORT} -t public"
```

## Why This Works

1. **`aptPkgs = ["php8.2-mysql"]`** - Installs MySQL extension (contains `pdo_mysql`)
2. **`composer install`** - Installs Laravel dependencies
3. **PHP built-in server** - Starts Laravel correctly on Railway's PORT

## Deployment Steps

### 1. Commit and Push

```bash
git add nixpacks.toml
git commit -m "Fix: Add MySQL PDO extension for Railway"
git push
```

### 2. Railway Auto-Redeploys

Railway will:
- Detect `nixpacks.toml`
- Install `php8.2-mysql` during build
- Run composer install
- Start Laravel with PHP built-in server

### 3. REQUIRED: Run Migrations After Deploy

**Option A: Railway CLI**
```bash
railway ssh
php artisan migrate --force
php artisan db:seed --force
```

**Option B: Railway UI**
- Go to your service
- Click "Run Command"
- Run: `php artisan migrate --force`
- Run: `php artisan db:seed --force`

## Verification

After deployment, verify extensions are loaded:

```bash
php -m | grep -i mysql
php -m | grep -i pdo
```

**Expected output:**
```
mysql
mysqli
pdo_mysql
pdo
```

## Environment Variables

Make sure these are set in Railway:

```env
APP_KEY=base64:your-app-key-here
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=mysql
DB_HOST=your-railway-db-host
DB_PORT=3306
DB_DATABASE=unloqit
DB_USERNAME=root
DB_PASSWORD=your-password
```

## Troubleshooting

### Still seeing "could not find driver"
- Check Railway build logs - should see `apt-get install php8.2-mysql`
- Verify `nixpacks.toml` is committed and pushed
- Rebuild deployment (not just redeploy)

### App won't start
- Check `APP_KEY` is set
- Verify `APP_URL` matches Railway domain
- Check logs: Railway dashboard → Deployments → View Logs

### Migrations fail
- Verify database environment variables are correct
- Check database service is running
- Test connection: `php artisan tinker` → `DB::connection()->getPdo()`

---

**Status:** ✅ Fixed - Simple `nixpacks.toml` created

**Next:** Push to Git → Railway auto-redeploys → Run migrations → Done!
