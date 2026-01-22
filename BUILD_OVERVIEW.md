# Unloqit Website - Complete Build Overview

## 🏗️ Architecture Overview

**Unloqit** is a Laravel 11 (PHP 8.2+) on-demand locksmith marketplace platform, similar to TaskRabbit. It combines:
- **SEO-optimized content pages** (city, service, neighborhood combinations)
- **Marketplace functionality** (customer requests → provider matching → job dispatch)
- **Provider dashboard** (job management, earnings, availability)
- **Programmatic content generation** (template-based SEO content)

---

## 📁 Project Structure

### **Core Framework**
- **Laravel 11** - PHP framework
- **Vite** - Asset bundling (CSS/JS)
- **TailwindCSS** - Utility-first CSS framework
- **Alpine.js** - Minimal JavaScript framework for interactivity

### **Key Directories**

```
app/
├── Console/Commands/          # Artisan commands (content generation, city rollout)
├── Http/Controllers/         # Route controllers
│   ├── Auth/                 # Provider authentication
│   └── [11 controllers]      # Home, City, Service, Request, Pro Dashboard, etc.
├── Models/                   # Eloquent models (14 models)
├── Services/                 # Business logic services
│   ├── Schema/              # JSON-LD structured data generators
│   └── [5 services]         # Dispatch, Content Generation, Title/Meta, etc.
└── Providers/               # Service providers

resources/
├── views/
│   ├── layouts/app.blade.php          # Main layout with SEO sections
│   ├── pages/                         # SEO content pages
│   └── marketplace/                   # Marketplace UI (request, track, pro dashboard)
├── css/app.css                        # TailwindCSS with custom brand styling
└── js/app.js                          # Alpine.js initialization

database/migrations/                    # 18 migration files
```

---

## 🗄️ Database Schema

### **Core Entities**

1. **Cities** (`cities`)
   - `name`, `slug`, `state`, `lat`, `lng`
   - Special handling: Cleveland uses `/cleveland-locksmith` URL pattern

2. **Neighborhoods** (`neighborhoods`)
   - Belongs to cities
   - Used for hyperlocal SEO pages

3. **Services** (`services`)
   - Locksmith service types (Car Lockout, House Lockout, Rekeying, etc.)
   - 6 initial services seeded

4. **City Service Pages** (`city_service_pages`)
   - Pivot table for city-service combinations
   - Stores custom intro/pricing per combination

5. **Providers** (`providers`)
   - Service provider accounts (locksmiths)
   - Authentication via `auth:provider` guard
   - Fields: `name`, `email`, `password`, `license_number`, `is_verified`, `is_active`
   - JSON fields: `service_areas[]`, `service_types[]`

6. **Jobs** (`jobs`)
   - Customer requests → provider assignments
   - Status flow: `created` → `broadcast` → `claimed` → `en_route` → `arrived` → `in_progress` → `completed`
   - Stores customer info (name, phone, email) even if customer account deleted
   - Pricing: `quoted_price`, `final_price`, `payment_status`, `stripe_payment_intent_id`

7. **Customers** (`customers`)
   - Optional customer accounts (currently minimal usage)

8. **Job Statuses** (`job_statuses`)
   - Audit trail of job status changes
   - Tracks who updated status and when

9. **Provider Availability** (`provider_availability`)
   - Online/offline status
   - `is_online`, `last_seen_at`, `active_jobs_count`, `max_jobs_per_hour`

10. **Provider Earnings** (`provider_earnings`)
    - Tracks payouts per job
    - `payout_amount`, `status` (pending/paid), `paid_at`

11. **Generated Content** (`generated_content`)
    - Template-generated SEO content
    - Types: `city`, `service`, `neighborhood`
    - Stores: `title`, `meta_description`, `content`, `is_published`

12. **Content Templates** (`content_templates`)
    - Reusable templates for content generation
    - Variables: `{city_name}`, `{service_name}`, `{neighborhood_name}`, etc.

13. **Guides** (`guides`)
    - SEO guide pages (`/guides/{slug}`)

14. **FAQs** (`faqs`)
    - FAQ pages (`/faq/{slug}`)

---

## 🛣️ URL Structure & Routing

### **Root Market (Cleveland) - Special URLs**
```
/                                    → Homepage
/cleveland-locksmith                 → Cleveland city landing
/cleveland-locksmith/{service}       → Service in Cleveland
/cleveland-locksmith/{service}/{neighborhood} → Hyperlocal page
```

### **Future Cities**
```
/locksmith/{city}                    → City landing
/locksmith/{city}/{service}          → Service in city
/locksmith/{city}/{service}/{neighborhood} → Hyperlocal
```

### **Marketplace Routes**
```
/request-locksmith                   → Request form (generic)
/request-locksmith/{city}/{service}  → Request form (pre-filled context)
/request/submit                      → POST: Submit job request
/request/track/{job}                 → Track job status
```

### **Provider Routes (Auth Required)**
```
/pro/register                        → Provider registration
/pro/login                           → Provider login
/pro/dashboard                       → Provider dashboard
/pro/jobs                            → Available jobs feed
/pro/jobs/{job}                      → Job details
/pro/jobs/{job}/claim                → POST: Claim job
/pro/jobs/{job}/status               → POST: Update job status
/pro/earnings                        → Earnings dashboard
/pro/toggle-online                   → POST: Toggle online status
```

### **Content Pages**
```
/guides/{slug}                       → Guide pages
/faq/{slug}                          → FAQ pages
```

### **Sitemaps**
```
/sitemap.xml                         → Main sitemap index
/sitemap-cities.xml                  → All city pages
/sitemap-services.xml                → All service pages
/sitemap-guides.xml                  → All guide pages
/sitemap-faq.xml                     → All FAQ pages
/sitemap.ndjson                      → NDJSON format sitemap
```

---

## 🎨 Frontend Architecture

### **Styling System**
- **TailwindCSS** with custom brand configuration
- **Custom color palette**:
  - `brand-dark` (#0A1A3A) - Primary dark
  - `brand-accent` (#FF6A3A) - Orange accent
  - `brand-light` (#F4F5F7) - Light background
- **Custom fonts**: Barlow Condensed (headings), DM Sans (body)
- **Custom components**: `.industrial-bg`, `.service-card`, `.emergency-button`, `.atmospheric-surface`

### **JavaScript**
- **Alpine.js** only (minimal JS framework)
- No heavy frameworks (React/Vue)
- Server-rendered HTML with Alpine for interactivity

### **Asset Pipeline**
- **Vite** compiles `resources/css/app.css` and `resources/js/app.js`
- Output: `public/build/assets/`
- Development: `npm run dev` (HMR)
- Production: `npm run build`

---

## 🔧 Key Services

### **1. DispatchService**
**Purpose**: Job dispatch and provider matching

**Key Methods**:
- `broadcastJob(Job $job)` - Broadcasts job to available providers
- `findAvailableProviders(Job $job)` - Finds online, verified providers in service area
- `claimJob(Job $job, Provider $provider)` - Claims job for provider (with transaction)
- `updateJobStatus(...)` - Updates job status and logs to `job_statuses`

**Logic**:
- Providers must be: `is_active`, `is_verified`, `is_online`, `last_seen_at` < 15 min ago
- Must have matching `city_id` and `service_id` in `provider_city_service` pivot
- Orders by `rating` DESC, `response_time` ASC

### **2. ContentGeneratorService**
**Purpose**: Generate programmatic SEO content from templates

**Key Methods**:
- `generateCityContent(City $city)` - Generates city landing page content
- `generateCityServiceContent(City, Service)` - Generates service page content
- `generateNeighborhoodContent(City, Service, Neighborhood)` - Generates hyperlocal content

**Template System**:
- Uses `ContentTemplate` model with variable substitution
- Variables: `{city_name}`, `{service_name}`, `{neighborhood_name}`, etc.
- Falls back to default templates if none configured
- Stores in `generated_content` table

### **3. TitleMetaService**
**Purpose**: Generate SEO titles and meta descriptions

**Methods**:
- `forHome()`, `forCity()`, `forCityService()`, `forNeighborhoodService()`
- `forGuide()`, `forFaq()`, `forRequest()`, `forProRegister()`, `forProLogin()`
- Truncates titles to 60 chars, meta to 155 chars

### **4. Schema Services** (JSON-LD)
**Location**: `app/Services/Schema/`

**Classes**:
- `OrganizationSchema` - Base organization schema
- `LocalBusinessSchema` - City pages
- `ServiceSchema` - Service pages
- `BreadcrumbSchema` - Navigation breadcrumbs
- `FAQPageSchema` - FAQ pages
- `WebPageSchema` - Generic web page schema

**Usage**: All pages include appropriate JSON-LD in `<script type="application/ld+json">` tags

---

## 🎯 Controllers

### **SEO Content Controllers**
1. **HomeController** - Homepage with city/services overview
2. **CityController** - City landing pages (Cleveland special case)
3. **CityServiceController** - Service pages within cities
4. **CityServiceNeighborhoodController** - Hyperlocal service pages
5. **GuideController** - Guide content pages
6. **FaqController** - FAQ pages
7. **SitemapController** - XML/NDJSON sitemap generation

### **Marketplace Controllers**
8. **RequestController** - Customer job request flow
   - `show()` - Request form (with optional city/service context)
   - `submit()` - Creates job, broadcasts to providers
   - `track()` - Job tracking page

9. **ProDashboardController** - Provider dashboard (auth required)
   - `dashboard()` - Overview with active/available jobs
   - `jobs()` - Available jobs feed
   - `claimJob()` - Claim a job
   - `showJob()` - Job details
   - `updateJobStatus()` - Update job status (en_route, arrived, in_progress, completed)
   - `earnings()` - Earnings dashboard
   - `toggleOnline()` - Toggle online/offline status

10. **ProviderAuthController** - Provider authentication
    - `showRegisterForm()` - Registration with city/service selection
    - `register()` - Creates provider (unverified by default)
    - `showLoginForm()` - Login form
    - `login()` - Authenticates provider
    - `logout()` - Logs out provider

---

## 🔐 Authentication

### **Provider Guard**
- Custom guard: `auth:provider`
- Uses `providers` table
- Middleware: `auth:provider` on protected routes
- Registration creates unverified, inactive account
- Admin must verify before provider can accept jobs

---

## 📝 Content Generation System

### **Template-Based Content**
1. **Content Templates** stored in database
2. **Variables** replaced: `{city_name}`, `{service_name}`, etc.
3. **Generated Content** stored in `generated_content` table
4. **Controllers check** for generated content first, fallback to `TitleMetaService`

### **Artisan Command**
```bash
php artisan content:generate --city=cleveland
php artisan content:generate --city=cleveland --service=car-lockout
php artisan content:generate --city=cleveland --service=car-lockout --neighborhood=ohio-city
php artisan content:generate --all-cities  # Generate all content
```

### **Content Types**
- **City**: Landing page content
- **Service**: Service-specific content in city
- **Neighborhood**: Hyperlocal service content

---

## 🎨 Design System

### **Brand Colors**
```css
--brand-dark: #0A1A3A        (Primary dark)
--brand-dark-80: #13254E
--brand-dark-60: #203864
--brand-accent: #FF6A3A      (Orange accent)
--brand-accent-80: #E3552B
--brand-accent-60: #CC4824
--brand-light: #F4F5F7       (Background)
--brand-white: #FFFFFF
--brand-gray: #D8DDE4
```

### **Typography**
- **Headings**: Barlow Condensed (condensed, bold)
- **Body**: DM Sans (clean, readable)
- **Industrial aesthetic**: Grid patterns, bold typography, accent underlines

### **Custom Components**
- `.industrial-bg` - Dark background with grid pattern
- `.service-card` - Service listing cards with hover effects
- `.emergency-button` - Primary CTA buttons
- `.atmospheric-surface` - Light gradient backgrounds
- `.accent-underline` - Hover underline effect

---

## 🚀 Job Flow (Marketplace)

### **1. Customer Request**
```
Customer fills form → POST /request/submit
→ Job created (status: 'created')
→ DispatchService::broadcastJob()
→ Job status → 'broadcast'
→ Available providers notified (in real system: push notifications)
```

### **2. Provider Claims Job**
```
Provider views /pro/jobs
→ Clicks "Claim Job"
→ POST /pro/jobs/{job}/claim
→ DispatchService::claimJob()
→ Job status → 'claimed'
→ provider_id assigned
→ Provider availability updated (active_jobs_count++)
```

### **3. Job Execution**
```
Provider updates status:
→ 'en_route' → en_route_at timestamp
→ 'arrived' → arrived_at timestamp
→ 'in_progress' → Working
→ 'completed' → completed_at timestamp
→ Provider availability released (active_jobs_count--)
```

### **4. Customer Tracking**
```
Customer visits /request/track/{job}
→ Sees real-time status updates
→ Views job_statuses history
```

---

## 📊 SEO Features

### **Structured Data (JSON-LD)**
- Organization schema (all pages)
- LocalBusiness schema (city pages)
- Service schema (service pages)
- Breadcrumb schema (all pages except home)
- FAQPage schema (FAQ pages)
- WebPage schema (all pages)

### **Meta Tags**
- Unique titles per page (60 char limit)
- Unique meta descriptions (155 char limit)
- Canonical URLs (clean URLs only)
- Open Graph tags
- Twitter Card tags

### **Sitemaps**
- XML sitemaps (cities, services, guides, FAQs)
- NDJSON sitemap for programmatic access
- Sitemap index at `/sitemap.xml`

### **URL Structure**
- Clean, SEO-friendly URLs
- No query parameters in canonical URLs
- Hierarchical structure: city → service → neighborhood

---

## 🛠️ Development Commands

### **Content Generation**
```bash
php artisan content:generate --all-cities
php artisan content:generate --city=cleveland --service=car-lockout
```

### **City Rollout**
```bash
php artisan city:rollout columbus
```

### **Provider Onboarding**
```bash
php artisan provider:onboard
```

### **Asset Building**
```bash
npm run dev      # Development with HMR
npm run build    # Production build
```

### **Server**
```bash
php artisan serve    # Laravel dev server (port 8000)
```

---

## 📦 Dependencies

### **PHP (composer.json)**
- `laravel/framework: ^11.0`
- `laravel/tinker: ^2.9`
- Dev: `laravel/pint`, `laravel/sail`, `phpunit/phpunit`

### **JavaScript (package.json)**
- `vite: ^5.1.0`
- `laravel-vite-plugin: ^1.0`
- `tailwindcss: ^3.4.1`
- `alpinejs: ^3.13.3`
- `@tailwindcss/forms: ^0.5.7`

---

## 🔄 Data Flow Examples

### **City Page Request**
```
Route: /cleveland-locksmith
→ CityController::showCleveland()
→ Loads City, Services
→ Checks GeneratedContent (if exists, use it; else use TitleMetaService)
→ Generates JSON-LD schemas
→ Renders pages/city.blade.php
```

### **Job Request Flow**
```
Route: /request-locksmith/cleveland/car-lockout
→ RequestController::show('cleveland', 'car-lockout')
→ Pre-fills city/service in form
→ Customer submits
→ RequestController::submit()
→ Creates Job (status: 'created')
→ DispatchService::broadcastJob()
→ Job status → 'broadcast'
→ Finds available providers
→ (In production: sends push notifications)
→ Redirects to /request/track/{job}
```

---

## 🎯 Key Features

1. **SEO-First Architecture**
   - Server-rendered HTML
   - JSON-LD structured data
   - Clean URL structure
   - Comprehensive sitemaps

2. **Marketplace Functionality**
   - Real-time job dispatch
   - Provider matching algorithm
   - Job status tracking
   - Earnings management

3. **Scalable Content System**
   - Template-based content generation
   - Programmatic SEO pages
   - Easy city/service expansion

4. **Provider Management**
   - Authentication system
   - Availability tracking
   - Job claiming system
   - Earnings dashboard

5. **Modern Frontend**
   - TailwindCSS utility classes
   - Minimal JavaScript (Alpine.js)
   - Responsive design
   - Industrial aesthetic

---

## 🔐 Security Considerations

- Provider accounts require verification before activation
- Jobs store customer info even if account deleted (audit trail)
- Password hashing via Laravel's Hash facade
- CSRF protection on all forms
- Authentication middleware on protected routes

---

## 📈 Scalability

- **Database indexes** on frequently queried columns
- **Eager loading** relationships to prevent N+1 queries
- **Template system** allows rapid content generation for new cities
- **Service-based architecture** for maintainability
- **Server-rendered** (no client-side rendering overhead)

---

## 🚢 Deployment

- **Production**: Set `APP_ENV=production`, `APP_DEBUG=false`
- **Caching**: Run `php artisan config:cache`, `route:cache`, `view:cache`
- **Assets**: Run `npm run build` for production assets
- **Web Server**: Route all URLs to `public/index.php` (Nginx/Apache)

---

## 📚 Documentation Files

- `README.md` - Project overview
- `SETUP.md` - Setup instructions
- `BUILD_OVERVIEW.md` - This file (complete build context)
- Various Railway deployment docs
- Content pipeline documentation

---

## 🎨 Design Philosophy

**Industrial Aesthetic**:
- Dark backgrounds with grid patterns
- Bold, condensed typography
- Orange accent color for CTAs
- Clean, minimal UI
- Fast, professional feel

**User Experience**:
- Clear call-to-actions
- Real-time job tracking
- Transparent pricing
- Fast response times emphasized

---

This build represents a complete, production-ready marketplace platform with strong SEO foundations and scalable architecture.
