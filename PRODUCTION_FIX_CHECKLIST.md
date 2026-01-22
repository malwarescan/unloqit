# Production Fix Checklist - Zero Missteps

## ✅ Immediate Fixes Applied

### 1. Explicit Legacy URL Redirects ✅
- ✅ Added `Route::permanentRedirect('/cleveland-locksmith', '/locksmith/oh/cleveland')` at TOP of routes
- ✅ Added catch-all route for `/cleveland-locksmith/{any}` → `/locksmith/oh/cleveland/{any}` (301)
- ✅ These routes are BEFORE any other routes to prevent 404s

### 2. Canonical Host Enforcement ✅
- ✅ Created `ForceCanonicalHost` middleware
- ✅ Forces `www.unloqit.com` (redirects `unloqit.com` → `www.unloqit.com`)
- ✅ Registered as FIRST middleware (before other redirects)
- ✅ Updated all schema URLs to use `www.unloqit.com`
- ✅ Updated all sitemap URLs to use `www.unloqit.com`
- ✅ Updated robots.txt sitemap reference

### 3. Homepage Verification ✅
- ✅ Homepage view has ZERO Cleveland references
- ✅ H1 shows "24/7 Locksmith Marketplace" (not "Cleveland")
- ✅ All links point to new routes (`/services`, `/locations`, `/request-locksmith`)
- ✅ FAQ updated to remove Cleveland-specific response times

### 4. Nav/Footer Links ✅
- ✅ Nav: Services → `/services`, Locations → `/locations`
- ✅ Footer services link to `/services/{service-slug}`
- ✅ Footer locations link to `/locksmith/{state}/{city}` (only covered cities)
- ✅ View composer ensures only indexable cities appear in footer

### 5. Indexability Gate: Noindex Instead of 404 ✅
- ✅ Controllers now return 200 with `isIndexable` flag
- ✅ Views add `<meta name="robots" content="noindex,follow">` when not indexable
- ✅ Sitemaps exclude non-indexable pages
- ✅ Preserves UX and internal linking

### 6. Cache Clearing Script ✅
- ✅ Created `deploy-clear-cache.sh` with all cache clearing commands
- ✅ Clears: config, route, view, cache
- ✅ Rebuilds optimized caches
- ✅ Includes verification steps

## 🚀 Deployment Steps (DO IN ORDER)

### Step 1: Deploy Code
```bash
git add .
git commit -m "Production fixes: explicit redirects, canonical host, noindex for ineligible pages"
git push origin main
```

### Step 2: On Production Server
```bash
# SSH to production server
cd /path/to/unloqit

# Pull latest code
git pull origin main

# Run cache clearing script
bash deploy-clear-cache.sh

# If using PHP-FPM, reload it:
sudo service php8.2-fpm reload
# OR restart your container/service
```

### Step 3: Verify (Critical Checks)

#### A. Homepage HTML
```bash
curl https://www.unloqit.com/ | grep -i "cleveland"
# Should return ZERO results
```

#### B. Legacy URL Redirects (MUST be 301, never 404)
```bash
curl -I https://www.unloqit.com/cleveland-locksmith
# Should return: HTTP/1.1 301 Moved Permanently
# Location: https://www.unloqit.com/locksmith/oh/cleveland

curl -I https://www.unloqit.com/cleveland-locksmith/car-lockout
# Should return: HTTP/1.1 301 Moved Permanently
# Location: https://www.unloqit.com/locksmith/oh/cleveland/car-lockout
```

#### C. Canonical Host Redirect
```bash
curl -I http://unloqit.com/
# Should return: HTTP/1.1 301 → https://www.unloqit.com/

curl -I https://unloqit.com/
# Should return: HTTP/1.1 301 → https://www.unloqit.com/
```

#### D. Nav/Footer Links (Zero 404s)
```bash
# Test nav links
curl -I https://www.unloqit.com/services
# Should return: HTTP/1.1 200 OK

curl -I https://www.unloqit.com/locations
# Should return: HTTP/1.1 200 OK

# Test footer service links (example)
curl -I https://www.unloqit.com/services/car-lockout
# Should return: HTTP/1.1 200 OK
```

#### E. Trailing Slash Normalization
```bash
curl -I https://www.unloqit.com/locksmith/oh/cleveland/
# Should return: HTTP/1.1 301 → /locksmith/oh/cleveland (no trailing slash)
```

#### F. Lowercase Normalization
```bash
curl -I https://www.unloqit.com/LOCKSMITH/OH/CLEVELAND
# Should return: HTTP/1.1 301 → /locksmith/oh/cleveland (lowercase)
```

#### G. Sitemaps
```bash
curl -I https://www.unloqit.com/sitemap.xml
# Should return: HTTP/1.1 200 OK

curl -I https://www.unloqit.com/sitemap-services.xml
# Should return: HTTP/1.1 200 OK

curl -I https://www.unloqit.com/sitemap-locations.xml
# Should return: HTTP/1.1 200 OK
```

#### H. Schema Validation
- Open homepage view source
- Search for "LocalBusiness" - should NOT exist
- Search for "WebSite" - should exist with SearchAction
- Search for "Organization" - should exist with @id

## 📋 Acceptance Criteria (PASS/FAIL)

- [ ] Homepage HTML contains zero city names (including Cleveland)
- [ ] No homepage nav/footer link 404s
- [ ] `/cleveland-locksmith*` is 301 to `/locksmith/oh/cleveland*` (never 404)
- [ ] Canonicals use exactly one host (`www.unloqit.com`) and one slash policy
- [ ] Sitemaps include only 200 + canonical + indexable URLs
- [ ] Homepage schema is Organization + WebSite(SearchAction) + WebPage, no LocalBusiness
- [ ] Non-indexable pages return 200 with noindex,follow (not 404)
- [ ] All redirects are single-hop (no redirect chains)

## 🔧 Files Modified

### Critical Fixes
- `routes/web.php` - Added explicit redirect routes at TOP
- `app/Http/Middleware/ForceCanonicalHost.php` - NEW - Forces www.unloqit.com
- `bootstrap/app.php` - Registered ForceCanonicalHost as first middleware
- `app/Http/Controllers/CityController.php` - Changed 404 to noindex
- `app/Http/Controllers/CityServiceController.php` - Changed 404 to noindex
- `app/Http/Controllers/CityServiceNeighborhoodController.php` - Changed 404 to noindex
- `resources/views/pages/city.blade.php` - Added noindex meta, fixed canonical
- `resources/views/pages/city-service.blade.php` - Added noindex meta, fixed canonical
- `resources/views/pages/city-service-neighborhood.blade.php` - Added noindex meta, fixed canonical

### URL Updates
- `app/Services/Schema/OrganizationSchema.php` - Updated to www.unloqit.com
- `app/Services/Schema/WebSiteSchema.php` - Updated to www.unloqit.com
- `app/Services/Schema/ServiceSchema.php` - Updated to www.unloqit.com
- `app/Http/Controllers/SitemapController.php` - Updated all URLs to www.unloqit.com
- `public/robots.txt` - Updated sitemap URL

### Deployment
- `deploy-clear-cache.sh` - NEW - Cache clearing script

## ⚠️ Important Notes

1. **View Cache**: Laravel caches compiled Blade views. After deployment, you MUST run `php artisan view:clear` or the old homepage will still be served.

2. **OPcache**: If using PHP-FPM with OPcache, you may need to reload PHP-FPM or restart the service to clear OPcache.

3. **Redirect Order**: The explicit redirect routes MUST be at the top of `routes/web.php` before any other routes, or they won't catch legacy URLs.

4. **Canonical Host**: The `ForceCanonicalHost` middleware runs FIRST (prepend) to ensure all requests hit the canonical host before any other processing.

5. **Noindex vs 404**: Ineligible pages now return 200 with noindex instead of 404. This preserves UX and allows internal linking while keeping them out of search results.

---

**Status**: All production fixes implemented. Ready for deployment and verification.
