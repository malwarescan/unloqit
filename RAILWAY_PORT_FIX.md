# Railway PORT Variable Fix

## Problem

PHP's built-in server doesn't expand shell variables like `${PORT:-8080}`. It treats it as a literal string, causing:

```
Invalid address: 0.0.0.0:${PORT:-8080}
```

## Solution

Use a shell script (`start.sh`) that expands the `PORT` environment variable before passing it to PHP.

### The Fix

**File: `start.sh`**
```bash
#!/bin/bash
PORT=${PORT:-8080}
php -d variables_order=EGPCS -S 0.0.0.0:$PORT -t public
```

**Updated `nixpacks.toml`:**
```toml
[phases.build]
cmds = [
  "composer install --no-dev --optimize-autoloader --no-interaction",
  "chmod +x start.sh"
]

[start]
cmd = "bash start.sh"
```

**Updated `railway.json`:**
```json
{
  "deploy": {
    "startCommand": "bash start.sh"
  }
}
```

## How It Works

1. Railway sets `PORT` environment variable (usually `8080`)
2. Shell script expands `${PORT:-8080}` to actual port number
3. PHP server starts on `0.0.0.0:8080` (or whatever PORT is set)

## Verification

After deployment, check Railway logs. You should see:
```
PHP 8.2.x Development Server (http://0.0.0.0:8080) started
```

Instead of:
```
Invalid address: 0.0.0.0:${PORT:-8080}
```

---

**Fix is pushed. Railway will redeploy automatically.**

