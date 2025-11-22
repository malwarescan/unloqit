# EXACT Variables to Add to Railway

## Step 1: Generate APP_KEY

Run this locally:
```bash
php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

Copy the output (starts with `base64:`)

## Step 2: Get Database Variables from Railway

1. Go to Railway Dashboard
2. Click on your **MySQL database service** (not the app service)
3. Click **"Variables"** tab
4. Copy these values:
   - `MYSQLHOST` → This is your `DB_HOST`
   - `MYSQLPORT` → This is your `DB_PORT` (usually 3306)
   - `MYSQLDATABASE` → This is your `DB_DATABASE`
   - `MYSQLUSER` → This is your `DB_USERNAME`
   - `MYSQLPASSWORD` → This is your `DB_PASSWORD`

## Step 3: Add These EXACT Variables

Go to **Railway Dashboard → Your App Service → Variables → New Variable**

Add these ONE BY ONE:

### Variable 1:
**Name:** `APP_KEY`  
**Value:** `base64:YOUR_GENERATED_KEY_HERE` (paste the key from Step 1)

### Variable 2:
**Name:** `APP_ENV`  
**Value:** `production`

### Variable 3:
**Name:** `APP_DEBUG`  
**Value:** `true` (change to `false` later after it works)

### Variable 4:
**Name:** `APP_URL`  
**Value:** `https://unloqit.com`

### Variable 5:
**Name:** `DB_CONNECTION`  
**Value:** `mysql`

### Variable 6:
**Name:** `DB_HOST`  
**Value:** `YOUR_MYSQLHOST_FROM_STEP_2`

### Variable 7:
**Name:** `DB_PORT`  
**Value:** `3306` (or whatever MYSQLPORT was)

### Variable 8:
**Name:** `DB_DATABASE`  
**Value:** `YOUR_MYSQLDATABASE_FROM_STEP_2`

### Variable 9:
**Name:** `DB_USERNAME`  
**Value:** `YOUR_MYSQLUSER_FROM_STEP_2`

### Variable 10:
**Name:** `DB_PASSWORD`  
**Value:** `YOUR_MYSQLPASSWORD_FROM_STEP_2`

## Step 4: Save and Wait

Railway will auto-redeploy. Wait 1-2 minutes.

## Step 5: Test

Visit: `https://unloqit.com/check-env.php`

All variables should show ✅ (green checkmarks)

---

## Quick Copy-Paste Template

If Railway database variables are named differently, use this template:

```
APP_KEY=base64:PASTE_YOUR_GENERATED_KEY
APP_ENV=production
APP_DEBUG=true
APP_URL=https://unloqit.com
DB_CONNECTION=mysql
DB_HOST=PASTE_FROM_DATABASE_SERVICE
DB_PORT=3306
DB_DATABASE=PASTE_FROM_DATABASE_SERVICE
DB_USERNAME=PASTE_FROM_DATABASE_SERVICE
DB_PASSWORD=PASTE_FROM_DATABASE_SERVICE
```

Replace `PASTE_FROM_DATABASE_SERVICE` with actual values from your MySQL service variables.

