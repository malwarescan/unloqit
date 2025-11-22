# Unloqit - On-Demand Locksmith Platform

A fully server-rendered PHP 8.3 Laravel 11 website for Unloqit.com, a TaskRabbit-style on-demand locksmith platform starting in Cleveland, Ohio.

## Features

- **Perfect SEO**: Server-rendered JSON-LD, clean URLs, proper canonical tags
- **Scalable Architecture**: Designed to scale to hundreds of cities and services
- **Clean URL Structure**: SEO-friendly URLs for cities, services, and neighborhoods
- **Comprehensive Sitemaps**: XML and NDJSON sitemap formats
- **TailwindCSS**: Modern, responsive design with TailwindCSS compiled via Vite
- **Alpine.js**: Minimal JavaScript framework for interactivity

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL/MariaDB or PostgreSQL
- Redis (optional, for caching)

## Installation

1. **Clone the repository** (if applicable) or navigate to the project directory

2. **Install PHP dependencies**:
```bash
composer install
```

3. **Install Node dependencies**:
```bash
npm install
```

4. **Copy environment file**:
```bash
cp .env.example .env
```

5. **Generate application key**:
```bash
php artisan key:generate
```

6. **Configure database** in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=unloqit
DB_USERNAME=root
DB_PASSWORD=
```

7. **Run migrations**:
```bash
php artisan migrate
```

8. **Seed the database**:
```bash
php artisan db:seed
```

9. **Build assets**:
```bash
npm run build
```

For development:
```bash
npm run dev
```

## Development

Start the development server:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## URL Structure

### Root Market (Cleveland)
- `/` - Homepage
- `/cleveland-locksmith` - Primary service landing page
- `/cleveland-locksmith/{service}` - Specific service pages
- `/cleveland-locksmith/{service}/{neighborhood}` - Hyperlocal pages

### Future Cities
- `/locksmith/{city-slug}` - City landing page
- `/locksmith/{city-slug}/{service}` - Service in city
- `/locksmith/{city-slug}/{service}/{neighborhood}` - Service in neighborhood

### Content Pages
- `/guides/{topic-slug}` - Guide pages
- `/faq/{slug}` - FAQ pages

### Sitemaps
- `/sitemap.xml` - Main sitemap index
- `/sitemap-cities.xml` - Cities sitemap
- `/sitemap-services.xml` - Services sitemap
- `/sitemap-guides.xml` - Guides sitemap
- `/sitemap-faq.xml` - FAQ sitemap
- `/sitemap.ndjson` - NDJSON sitemap

## SEO Features

- Server-rendered JSON-LD structured data
- Proper canonical URLs
- Open Graph and Twitter Card meta tags
- Breadcrumb navigation with schema markup
- Clean, crawlable URL structure
- Comprehensive XML sitemaps

## Deployment

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Set `APP_URL=https://unloqit.com` in `.env`
3. Run `php artisan config:cache`
4. Run `php artisan route:cache`
5. Run `php artisan view:cache`
6. Build production assets: `npm run build`

### Nginx Configuration

Ensure all clean URLs route to `public/index.php`:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### Apache Configuration

The `.htaccess` file in `public/` handles URL rewriting automatically.

## Database Structure

- **cities**: City information (name, slug, state, coordinates)
- **neighborhoods**: Neighborhoods within cities
- **services**: Available locksmith services
- **city_service_pages**: Custom content for city-service combinations
- **guides**: Content guide pages
- **faqs**: FAQ entries

## Initial Seed Data

The seeder includes:
- Cleveland, Ohio as the initial city
- 5 neighborhoods: Ohio City, Tremont, Lakewood, Detroit-Shoreway, University Circle
- 6 services: Car Lockout, Car Key Programming, House Lockout, Rekeying, Lock Installation, Commercial Locksmith

## License

Proprietary - All rights reserved

