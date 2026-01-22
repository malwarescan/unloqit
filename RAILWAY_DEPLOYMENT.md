# Railway Deployment Guide for Unloqit

## PHP MySQL PDO Extension Fix

### ✅ Fixed: `nixpacks.toml` Created

The `nixpacks.toml` file now installs required PHP MySQL extensions:
- `php8.2-mysql`
- `php8.2-pdo`
- `php8.2-pdo_mysql`

### Verify Extensions After Deploy

Run this in Railway's CLI to verify:
```bash
php -m | grep -i mysql
php -m | grep -i pdo
```

You should see:
- `pdo_mysql`
- `pdo`

---

## Quick Setup

### 1. Generate Service Domain

When Railway asks for the port:
- **Enter: `8080`** (or leave default)
- Railway will automatically map the PORT environment variable to this port

### 2. Environment Variables

Set these in Railway's environment variables:

```env
APP_NAME=Unloqit
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app

DB_CONNECTION=mysql
DB_HOST=your-railway-db-host
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=your-railway-db-password

CACHE_DRIVER=file
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Add these for production
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

### 3. Database Setup

1. Add a MySQL database service in Railway
2. Copy the connection details to your environment variables
3. Run migrations:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

### 4. Build & Deploy

Railway will automatically:
- Detect Laravel (via `composer.json`)
- Install dependencies
- Run the build command from `railway.json`
- Start the app using the Procfile

### 5. Post-Deployment

After first deploy, run these commands in Railway's CLI:

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Port Configuration

- **Target Port:** `8080` (or Railway's default)
- **Laravel listens on:** `${PORT}` environment variable (Railway sets this automatically)
- The `Procfile` handles port mapping: `--port=${PORT:-8000}`

## Troubleshooting

### App won't start
- Check logs in Railway dashboard
- Verify PORT environment variable is set
- Ensure database connection is correct

### PDOException: could not find driver
- ✅ **FIXED:** `nixpacks.toml` now installs `php8.2-mysql` and `php8.2-pdo_mysql`
- Verify extensions: `php -m | grep -i mysql`
- Should see `pdo_mysql` and `pdo` in output
- Rebuild deployment if extensions missing

### Database connection errors
- Verify DB credentials match Railway database service
- Check DB_HOST includes port if needed
- Ensure database service is running

### 500 errors
- Check `APP_DEBUG=true` temporarily to see errors
- Verify `APP_KEY` is set
- Check file permissions on `storage/` and `bootstrap/cache/`

## Files Created

- `Procfile` - Tells Railway how to start the app
- `railway.json` - Railway-specific build configuration
- `.gitignore` - Already excludes sensitive files

## Notes

- Railway automatically provides a `PORT` environment variable
- Laravel will listen on whatever port Railway assigns
- The `Procfile` uses `${PORT:-8000}` as fallback
- Make sure `APP_URL` matches your Railway domain

