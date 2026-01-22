# Railway SSH Access Guide

## Problem: "No linked project found"

You're running commands locally, but need to run them on Railway's server.

## Solution Options

### Option 1: Link Railway Project Locally (Recommended)

```bash
# Install Railway CLI if not installed
npm i -g @railway/cli

# Login to Railway
railway login

# Link to your project
railway link

# Now you can SSH
railway ssh
```

### Option 2: Use Railway Web Interface

1. Go to Railway Dashboard
2. Click on your service
3. Click "Deployments" tab
4. Click on latest deployment
5. Click "View Logs" or "Run Command"
6. Use the web terminal to run commands

### Option 3: Use Railway Run Command Feature

1. Railway Dashboard → Your Service
2. Click "Run Command" button
3. Enter command: `php artisan key:generate --force`
4. Click "Run"

## Commands to Run in Railway

Once you have SSH access (via `railway ssh` or web terminal):

```bash
# 1. Generate APP_KEY
php artisan key:generate --force

# 2. Fix storage permissions
chmod -R 775 storage bootstrap/cache

# 3. Clear all caches
php artisan optimize:clear

# 4. Run migrations (idempotent now)
php artisan migrate --force

# 5. Run seeders (idempotent now - won't create duplicates)
php artisan db:seed --force

# 6. Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Verify extensions
php -m | grep -i mysql
php -m | grep -i pdo
```

## Quick Fix Script

Create a file `fix-railway.sh` and run it:

```bash
#!/bin/bash
php artisan key:generate --force
chmod -R 775 storage bootstrap/cache
php artisan optimize:clear
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ All fixes applied!"
```

Then in Railway:
```bash
railway ssh
bash fix-railway.sh
```

---

**Note:** Seeders are now idempotent (use `firstOrCreate`), so running them multiple times won't cause duplicate errors.

