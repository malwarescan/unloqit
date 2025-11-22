# Railway Asset URL Fix

## Problem

CSS/JS assets are loading with `http://` instead of `https://`, causing mixed content errors.

## Solution

Add this environment variable in Railway:

**Variable Name:** `ASSET_URL`  
**Variable Value:** `https://unloqit.com`

This forces Laravel to use HTTPS for all asset URLs.

## Steps

1. Railway Dashboard → Your App Service → Variables
2. Click "New Variable"
3. Name: `ASSET_URL`
4. Value: `https://unloqit.com`
5. Click "Add"
6. Wait for Railway to redeploy
7. Refresh the site

---

**After adding ASSET_URL, all assets will load over HTTPS.**

