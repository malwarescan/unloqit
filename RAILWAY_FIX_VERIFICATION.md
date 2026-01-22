# Railway MySQL PDO Fix - Verification Steps

## Current Issue
Still seeing "could not find driver" error even after creating `nixpacks.toml`.

## Updated Fix

### 1. Updated `nixpacks.toml`
Now includes all three required packages:
```toml
[phases.setup]
aptPkgs = ["php8.2-mysql", "php8.2-pdo", "php8.2-pdo_mysql"]
```

### 2. Created `Dockerfile` as Fallback
If Nixpacks doesn't work, Railway can use Dockerfile which explicitly installs MySQL PDO.

## Verification Steps

### Step 1: Check if files are committed
```bash
git status
```

Make sure `nixpacks.toml` and `Dockerfile` are committed.

### Step 2: Push to GitHub
```bash
git add nixpacks.toml Dockerfile
git commit -m "Fix: Ensure MySQL PDO extensions are installed"
git push
```

### Step 3: Check Railway Build Logs

After Railway rebuilds, check the build logs for:
- `apt-get install php8.2-mysql`
- `apt-get install php8.2-pdo`
- `apt-get install php8.2-pdo_mysql`

If you DON'T see these, Railway isn't using `nixpacks.toml`.

### Step 4: Force Railway to Use Nixpacks

In Railway dashboard:
1. Go to your service
2. Settings → Build
3. Make sure "Builder" is set to "Nixpacks" (not Docker)
4. Redeploy

### Step 5: Alternative - Use Dockerfile

If Nixpacks still doesn't work:
1. Railway Settings → Build
2. Change "Builder" to "Dockerfile"
3. Redeploy

The `Dockerfile` explicitly installs MySQL PDO extensions.

## Debugging Commands

After deployment, SSH into Railway and run:

```bash
# Check PHP version
php -v

# Check installed extensions
php -m | grep -i mysql
php -m | grep -i pdo

# Should see:
# mysql
# mysqli
# pdo_mysql
# pdo
```

If extensions are missing, the build didn't install them.

## If Still Failing

### Option 1: Check Railway Build Logs
Look for errors during `apt-get install` phase.

### Option 2: Manual Installation Script
Add to `nixpacks.toml`:
```toml
[phases.build]
cmds = [
  "apt-get update",
  "apt-get install -y php8.2-mysql php8.2-pdo php8.2-pdo_mysql",
  "composer install --no-dev --optimize-autoloader"
]
```

### Option 3: Use Railway's Environment Variables
Some Railway setups require setting PHP version explicitly.

---

**Next Steps:**
1. Commit and push `nixpacks.toml` and `Dockerfile`
2. Check Railway build logs
3. Verify extensions are installed
4. If still failing, try Dockerfile builder

