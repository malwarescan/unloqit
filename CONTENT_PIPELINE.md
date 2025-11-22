# Content Writing Pipeline Documentation

This document explains the programmatic SEO expansion system, city-rollout automation, and locksmith provider onboarding system.

## Overview

The content pipeline consists of three main components:

1. **Programmatic SEO Expansion System** - Automatically generates SEO-optimized content for cities, services, and neighborhoods
2. **City-Rollout Automation** - Quickly add new cities with all services and neighborhoods
3. **Locksmith Provider Onboarding** - Manage and assign locksmith providers to cities and services

## 1. Programmatic SEO Expansion System

### Content Templates

Content templates are stored in the `content_templates` table and define how content is generated. Each template includes:

- **Type**: `city`, `service`, or `neighborhood`
- **Template**: The main content with variables like `{city_name}`, `{service_name}`, etc.
- **Meta Description Template**: SEO meta description template
- **Title Template**: Page title template
- **Variables**: Available variables for the template
- **Priority**: Higher priority templates are used first

### Variables Available

**City Templates:**
- `{city_name}` - City name (e.g., "Cleveland")
- `{city_state}` - State abbreviation (e.g., "OH")
- `{city_slug}` - URL slug (e.g., "cleveland")
- `{city_full}` - Full city name (e.g., "Cleveland, OH")

**Service Templates:**
- All city variables plus:
- `{service_name}` - Service name (e.g., "Car Lockout")
- `{service_slug}` - Service slug (e.g., "car-lockout")
- `{service_description}` - Service description
- `{service_name_lower}` - Lowercase service name

**Neighborhood Templates:**
- All service variables plus:
- `{neighborhood_name}` - Neighborhood name (e.g., "Ohio City")
- `{neighborhood_slug}` - Neighborhood slug

### Generating Content

#### Via Artisan Command

```bash
# Generate content for a specific city
php artisan content:generate --city=cleveland

# Generate content for a city-service combination
php artisan content:generate --city=cleveland --service=car-lockout

# Generate content for a neighborhood
php artisan content:generate --city=cleveland --service=car-lockout --neighborhood=ohio-city

# Generate content for ALL cities (comprehensive)
php artisan content:generate --all-cities
```

#### Via Code

```php
use App\Services\ContentGeneratorService;

$contentGenerator = app(ContentGeneratorService::class);

// Generate city content
$city = City::where('slug', 'cleveland')->first();
$contentGenerator->generateCityContent($city);

// Generate service content
$service = Service::where('slug', 'car-lockout')->first();
$contentGenerator->generateCityServiceContent($city, $service);

// Generate neighborhood content
$neighborhood = Neighborhood::where('slug', 'ohio-city')->first();
$contentGenerator->generateNeighborhoodContent($city, $service, $neighborhood);
```

### Managing Content Templates

```php
use App\Models\ContentTemplate;

// Create a new template
ContentTemplate::create([
    'type' => 'city',
    'name' => 'Premium City Template',
    'template' => 'Custom content with {city_name} and {city_state}...',
    'meta_description_template' => 'Meta description with {city_name}...',
    'title_template' => '{city_name} Locksmith | Unloqit',
    'variables' => ['city_name', 'city_state'],
    'is_active' => true,
    'priority' => 20, // Higher priority = used first
]);

// List active templates by type
$templates = ContentTemplate::active()
    ->byType('city')
    ->orderedByPriority()
    ->get();
```

## 2. City-Rollout Automation

### Rolling Out a New City

#### Via Artisan Command

```bash
# Basic city rollout
php artisan city:rollout "Columbus" OH

# With neighborhoods
php artisan city:rollout "Columbus" OH --neighborhoods="Downtown" "Short North" "German Village"

# With coordinates
php artisan city:rollout "Columbus" OH --lat=39.9612 --lng=-82.9988

# Custom slug
php artisan city:rollout "Columbus" OH --slug=columbus-oh

# Skip content generation (faster, generate later)
php artisan city:rollout "Columbus" OH --no-content
```

#### Via Code

```php
use App\Services\CityRolloutService;

$rolloutService = app(CityRolloutService::class);

// Rollout a city
$city = $rolloutService->rolloutCity([
    'name' => 'Columbus',
    'state' => 'OH',
    'slug' => 'columbus',
    'lat' => 39.9612,
    'lng' => -82.9988,
], [
    'Downtown',
    'Short North',
    'German Village',
], true); // Generate content

// Add neighborhoods to existing city
$neighborhoods = $rolloutService->addNeighborhoodsToCity($city, [
    'Arena District',
    'Brewery District',
], true);

// Add a service to existing city
$rolloutService->addServiceToCity($city, $service, true);

// Bulk rollout multiple cities
$results = $rolloutService->bulkRollout([
    [
        'name' => 'Cincinnati',
        'state' => 'OH',
        'neighborhoods' => ['Over-the-Rhine', 'Mount Adams'],
        'generate_content' => true,
    ],
    [
        'name' => 'Toledo',
        'state' => 'OH',
        'neighborhoods' => ['Downtown'],
        'generate_content' => true,
    ],
]);
```

### What Happens During City Rollout

1. City is created in database
2. Neighborhoods are created and linked to city
3. City-service pages are created for ALL existing services
4. Content is generated for:
   - City landing page
   - Each service page in the city
   - Each neighborhood-service combination

## 3. Locksmith Provider Onboarding

### Onboarding a Provider

#### Via Artisan Command

```bash
# Basic provider onboarding
php artisan provider:onboard "John Smith" "john@example.com"

# With phone and license
php artisan provider:onboard "John Smith" "john@example.com" \
    --phone="555-1234" \
    --license="OH-LOCK-12345"

# Assign to cities and services
php artisan provider:onboard "John Smith" "john@example.com" \
    --cities=cleveland columbus \
    --services=car-lockout house-lockout

# Mark as verified
php artisan provider:onboard "John Smith" "john@example.com" \
    --verify \
    --response-time="20-30 minutes"
```

#### Via Code

```php
use App\Services\ProviderOnboardingService;

$onboardingService = app(ProviderOnboardingService::class);

// Onboard a provider
$provider = $onboardingService->onboardProvider([
    'name' => 'John Smith',
    'email' => 'john@example.com',
    'phone' => '555-1234',
    'license_number' => 'OH-LOCK-12345',
    'is_verified' => true,
    'response_time' => '20-30 minutes',
], [1, 2], // City IDs
[1, 2, 3] // Service IDs
);

// Assign provider to specific city-service combinations
$onboardingService->assignProviderToCityServices(
    $provider,
    [1, 2], // City IDs
    [1, 2], // Service IDs
    [
        'is_available' => true,
        'base_price' => 75.00,
        'response_time' => '20-30 minutes',
    ]
);

// Update provider availability
$onboardingService->updateProviderAvailability(
    $provider,
    [1], // City IDs
    [1], // Service IDs
    false // Not available
);

// Verify a provider
$onboardingService->verifyProvider($provider);

// Get available providers for a city-service
$city = City::where('slug', 'cleveland')->first();
$service = Service::where('slug', 'car-lockout')->first();
$providers = $onboardingService->getAvailableProviders($city, $service);
```

### Provider Management

```php
use App\Models\Provider;

// Find providers
$providers = Provider::where('is_active', true)
    ->where('is_verified', true)
    ->get();

// Update provider rating
$onboardingService->updateProviderRating($provider, 4.8);

// Deactivate provider
$onboardingService->deactivateProvider($provider);
```

## Example Workflows

### Workflow 1: Add a New City

```bash
# 1. Rollout the city
php artisan city:rollout "Columbus" OH \
    --neighborhoods="Downtown" "Short North" "German Village" \
    --lat=39.9612 --lng=-82.9988

# 2. Verify content was generated
php artisan content:generate --city=columbus

# 3. Check the new pages
# Visit: /locksmith/columbus
# Visit: /locksmith/columbus/car-lockout
# Visit: /locksmith/columbus/car-lockout/downtown
```

### Workflow 2: Onboard Providers for a City

```bash
# 1. Onboard provider 1
php artisan provider:onboard "John Smith" "john@example.com" \
    --cities=columbus --services=car-lockout house-lockout \
    --verify --response-time="20-30 minutes"

# 2. Onboard provider 2
php artisan provider:onboard "Jane Doe" "jane@example.com" \
    --cities=columbus --services=rekeying lock-installation \
    --verify --response-time="15-25 minutes"
```

### Workflow 3: Regenerate All Content

```bash
# Regenerate content for all cities (useful after template updates)
php artisan content:generate --all-cities
```

## Database Structure

### Providers Table
- Basic provider information
- Verification status
- Ratings and job counts
- Service areas and types
- Availability schedule

### Content Templates Table
- Template definitions
- Variable mappings
- Priority system
- Active/inactive status

### Generated Content Table
- Stores generated content
- Links to cities, services, neighborhoods
- Tracks generation metadata
- Published/unpublished status

### Provider-City-Service Table
- Many-to-many relationship
- Availability per combination
- Pricing per combination
- Response times

## Best Practices

1. **Content Templates**: Create multiple templates with different priorities to A/B test content
2. **City Rollout**: Always include neighborhoods for better local SEO
3. **Provider Onboarding**: Verify providers before making them active
4. **Content Generation**: Regenerate content after template updates
5. **Bulk Operations**: Use bulk rollout for multiple cities at once

## Troubleshooting

### Content not generating
- Check that content templates exist and are active
- Verify city/service/neighborhood exist in database
- Check logs for errors

### Provider not showing up
- Ensure provider is verified (`is_verified = true`)
- Ensure provider is active (`is_active = true`)
- Check provider-city-service assignments

### City rollout failing
- Verify city name/state are valid
- Check for duplicate slugs
- Ensure services exist before rollout

