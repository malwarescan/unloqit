# Unloqit Setup Guide

## Quick Start

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Configure Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   
   Edit `.env` and set your database credentials:
   ```
   DB_DATABASE=unloqit
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

3. **Setup Database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Build Assets**
   ```bash
   npm run build
   ```
   
   For development:
   ```bash
   npm run dev
   ```

5. **Start Server**
   ```bash
   php artisan serve
   ```

## Project Structure

### Controllers
- `HomeController` - Homepage
- `CityController` - City landing pages
- `CityServiceController` - Service pages within cities
- `CityServiceNeighborhoodController` - Hyperlocal service pages
- `GuideController` - Guide content pages
- `FaqController` - FAQ pages
- `SitemapController` - Sitemap generation

### Models
- `City` - Cities table
- `Neighborhood` - Neighborhoods within cities
- `Service` - Available locksmith services
- `CityServicePage` - Custom content for city-service combinations
- `Guide` - Content guides
- `Faq` - FAQ entries

### Schema Services
All JSON-LD schema classes are in `app/Services/Schema/`:
- `OrganizationSchema` - Base organization schema
- `LocalBusinessSchema` - Local business schema for cities
- `ServiceSchema` - Service schema
- `BreadcrumbSchema` - Breadcrumb navigation schema
- `FAQPageSchema` - FAQ page schema
- `WebPageSchema` - Web page schema

### Views
- `layouts/app.blade.php` - Main layout with SEO sections
- `pages/home.blade.php` - Homepage
- `pages/city.blade.php` - City landing page
- `pages/city-service.blade.php` - Service page
- `pages/city-service-neighborhood.blade.php` - Hyperlocal page
- `pages/guide.blade.php` - Guide page
- `pages/faq.blade.php` - FAQ page

## URL Patterns

### Cleveland (Root Market)
- `/` → Homepage
- `/cleveland-locksmith` → Cleveland landing page
- `/cleveland-locksmith/car-lockout` → Car lockout service
- `/cleveland-locksmith/car-lockout/ohio-city` → Car lockout in Ohio City

### Future Cities
- `/locksmith/columbus` → Columbus landing page
- `/locksmith/columbus/car-lockout` → Car lockout in Columbus
- `/locksmith/columbus/car-lockout/downtown` → Car lockout in Downtown Columbus

### Content
- `/guides/how-to-change-locks` → Guide page
- `/faq/how-much-does-locksmith-cost` → FAQ page

## SEO Features

### JSON-LD Structured Data
Every page includes appropriate JSON-LD schemas:
- Organization schema (all pages)
- LocalBusiness schema (city pages)
- Service schema (service pages)
- Breadcrumb schema (all pages except home)
- FAQPage schema (FAQ pages)
- WebPage schema (all pages)

### Meta Tags
- Title tags (handcrafted per page)
- Meta descriptions (unique per page)
- Canonical URLs (clean URLs only)
- Open Graph tags
- Twitter Card tags

### Sitemaps
- `/sitemap.xml` - Main sitemap index
- `/sitemap-cities.xml` - All city pages
- `/sitemap-services.xml` - All service pages
- `/sitemap-guides.xml` - All guide pages
- `/sitemap-faq.xml` - All FAQ pages
- `/sitemap.ndjson` - NDJSON format sitemap

## Adding New Cities

1. Add city to database:
   ```php
   City::create([
       'name' => 'Columbus',
       'slug' => 'columbus',
       'state' => 'OH',
       'lat' => 39.9612,
       'lng' => -82.9988,
   ]);
   ```

2. Add neighborhoods:
   ```php
   Neighborhood::create([
       'city_id' => $city->id,
       'name' => 'Downtown',
       'slug' => 'downtown',
   ]);
   ```

3. Create city-service pages:
   ```php
   foreach (Service::all() as $service) {
       CityServicePage::create([
           'city_id' => $city->id,
           'service_id' => $service->id,
       ]);
   }
   ```

4. URLs will automatically work:
   - `/locksmith/columbus`
   - `/locksmith/columbus/car-lockout`
   - `/locksmith/columbus/car-lockout/downtown`

## Performance Optimization

- All pages are server-rendered (SSR)
- TailwindCSS compiled to single CSS file
- Minimal JavaScript (Alpine.js only)
- Image lazy loading via `loading="lazy"`
- Cache HTML responses for anonymous users (implement caching middleware)

## Deployment Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_URL=https://unloqit.com` in `.env`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build` for production assets
- [ ] Configure web server (Nginx/Apache) to route all URLs to `public/index.php`
- [ ] Ensure `robots.txt` is accessible
- [ ] Verify sitemaps are accessible
- [ ] Test all URL patterns
- [ ] Verify JSON-LD schemas are valid

## Notes

- Cleveland uses special URL pattern `/cleveland-locksmith` instead of `/locksmith/cleveland`
- All other cities use `/locksmith/{city-slug}` pattern
- Breadcrumbs automatically adjust URLs based on city
- Canonical URLs always point to clean URLs (no query parameters)

