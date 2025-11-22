# Railway Environment Variables Setup

## Problem

Railway doesn't use `.env` files - it uses environment variables directly. Laravel's `key:generate` command tries to write to `.env` which doesn't exist.

## Solution: Set APP_KEY in Railway Dashboard

### Step 1: Generate APP_KEY

Run this locally or in Railway SSH:

```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

This will output something like:
```
base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### Step 2: Add to Railway Environment Variables

1. Go to Railway Dashboard
2. Click on your service
3. Go to "Variables" tab
4. Click "New Variable"
5. Name: `APP_KEY`
6. Value: Paste the generated key (starts with `base64:`)
7. Click "Add"

### Step 3: Required Environment Variables

Add these in Railway Dashboard → Variables:

#### Application
```
APP_NAME=Unloqit
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://unloqit.com
```

#### Database (from Railway Database Service)
```
DB_CONNECTION=mysql
DB_HOST=${{MySQL.HOST}}
DB_PORT=${{MySQL.PORT}}
DB_DATABASE=${{MySQL.DATABASE}}
DB_USERNAME=${{MySQL.USER}}
DB_PASSWORD=${{MySQL.PASSWORD}}
```

**Note:** Railway provides database variables as `${{MySQL.HOST}}` etc. Check your database service for exact variable names.

#### Cache & Session
```
CACHE_DRIVER=file
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

#### Logging
```
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

### Step 4: After Setting Variables

Railway will automatically redeploy. Then:

```bash
railway ssh
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --force
```

## Quick Generate APP_KEY Command

In Railway SSH:
```bash
php -r "echo 'APP_KEY='; echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

Copy the output and add it to Railway Variables.

---

**CRITICAL:** Railway doesn't use `.env` files. All configuration must be set as environment variables in Railway Dashboard.

