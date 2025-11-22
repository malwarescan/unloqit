# Quick Start Guide - Content Pipeline

## Quick Examples

### 1. Add a New City (Columbus, OH)

```bash
php artisan city:rollout "Columbus" OH \
    --neighborhoods="Downtown" "Short North" "German Village" \
    --lat=39.9612 --lng=-82.9988
```

This will:
- Create Columbus city entry
- Create 3 neighborhoods
- Create service pages for all 6 services
- Generate SEO content for all pages
- Make everything immediately available at `/locksmith/columbus`

### 2. Generate Content for Existing City

```bash
# Generate all content for Cleveland
php artisan content:generate --city=cleveland

# Generate content for specific service
php artisan content:generate --city=cleveland --service=car-lockout

# Generate content for all cities (comprehensive)
php artisan content:generate --all-cities
```

### 3. Onboard a Provider

```bash
php artisan provider:onboard "John Smith" "john@example.com" \
    --phone="555-1234" \
    --license="OH-LOCK-12345" \
    --cities=cleveland columbus \
    --services=car-lockout house-lockout \
    --verify \
    --response-time="20-30 minutes"
```

### 4. Test the System

```bash
# 1. Add a test city
php artisan city:rollout "Test City" OH --neighborhoods="Test Neighborhood"

# 2. Generate content
php artisan content:generate --city=test-city

# 3. Check the database
php artisan tinker
>>> App\Models\City::where('slug', 'test-city')->first();
>>> App\Models\GeneratedContent::where('city_id', 1)->get();
```

## What Gets Created

When you rollout a city, the system automatically creates:

1. **City Entry** - Basic city information
2. **Neighborhoods** - All specified neighborhoods
3. **City-Service Pages** - For all 6 services:
   - Car Lockout
   - Car Key Programming
   - House Lockout
   - Rekeying
   - Lock Installation
   - Commercial Locksmith
4. **Generated Content** - SEO-optimized content for:
   - City landing page (`/locksmith/{city}`)
   - Each service page (`/locksmith/{city}/{service}`)
   - Each neighborhood page (`/locksmith/{city}/{service}/{neighborhood}`)

## Content Templates

Default templates are seeded automatically. You can create custom templates:

```php
use App\Models\ContentTemplate;

ContentTemplate::create([
    'type' => 'city',
    'name' => 'Premium Template',
    'template' => 'Custom content with {city_name}...',
    'meta_description_template' => 'Meta: {city_name}...',
    'title_template' => '{city_name} Locksmith | Unloqit',
    'priority' => 20, // Higher = used first
    'is_active' => true,
]);
```

## Next Steps

1. **Add More Cities**: Use `city:rollout` to expand to new markets
2. **Customize Templates**: Create templates that match your brand voice
3. **Onboard Providers**: Add real locksmith providers to the system
4. **Generate Content**: Regenerate content after template updates

See `CONTENT_PIPELINE.md` for detailed documentation.

