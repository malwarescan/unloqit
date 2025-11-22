# Title Meta Kernel Implementation

## Overview

Implemented the `title_meta_kernel` to generate SEO-optimized page titles and meta descriptions that:
1. Rank for locksmith service queries (local intent, transactional intent)
2. Signal clearly that Unloqit is an on-demand gig marketplace

## Implementation

### TitleMetaService (`app/Services/TitleMetaService.php`)

Central service that generates titles and meta descriptions following kernel principles:

- **Titles**: 50-60 characters, lead with keyword, include city, add speed/real-time angle, close with brand
- **Meta Descriptions**: 130-155 characters, start fast, mention platform aspect, include urgency modifiers

### Methods

- `forCity(City $city)` - City landing pages
- `forCityService(City $city, Service $service)` - Service pages
- `forNeighborhoodService(City $city, Service $service, Neighborhood $neighborhood)` - Hyperlocal pages
- `forHome()` - Homepage
- `forGuide(string $guideTitle)` - Guide pages
- `forFaq(string $question)` - FAQ pages
- `forProRegister()` - Pro registration
- `forProLogin()` - Pro login
- `forRequest(?City $city, ?Service $service)` - Request pages

### Controller Updates

All controllers updated to use `TitleMetaService`:

- `HomeController` - Homepage titles/metas
- `CityController` - City pages (with fallback to generated content)
- `CityServiceController` - Service pages (with fallback to generated content)
- `CityServiceNeighborhoodController` - Neighborhood pages (with fallback to generated content)
- `GuideController` - Guide pages
- `FaqController` - FAQ pages
- `RequestController` - Request pages
- `ProviderAuthController` - Pro registration/login pages

### Fallback Logic

Controllers check for `GeneratedContent` first (from content templates), then fall back to `TitleMetaService` if no generated content exists. This maintains backward compatibility while ensuring all pages have optimized titles/metas.

## Examples

### City Page
**Title:** "Locksmith in Cleveland – Fast, Verified Pros | Unloqit"  
**Meta:** "Locked out in Cleveland? Get matched with verified locksmith pros in minutes. Unloqit connects you to nearby experts who claim your job instantly."

### Service Page
**Title:** "Car Lockout in Cleveland – Fast, Verified Pros | Unloqit"  
**Meta:** "Need Car Lockout in Cleveland? Get matched with verified locksmith pros in minutes. Pros claim your job instantly. 24/7 availability."

### Neighborhood Page
**Title:** "Car Lockout in Ohio City, Cleveland – Fast Help | Unloqit"  
**Meta:** "Car Lockout in Ohio City? Matched with verified locksmith pros near you. Pros claim jobs instantly. 18-25 min response."

## Key Features

1. **SEO-First**: Primary keyword + city in every title
2. **Marketplace Identity**: "Get matched with verified pros", "Pros claim your job instantly"
3. **Urgency**: "minutes", "24/7", "instantly", "real-time"
4. **Character Limits**: Titles truncated to 60 chars, metas to 155 chars
5. **No Generic Language**: Avoids AI-sounding clichés and filler

## Testing

All pages now output optimized titles and meta descriptions. Verify by:
1. Viewing page source on any page
2. Checking `<title>` tag
3. Checking `<meta name="description">` tag

---

**Status: ✅ Fully Implemented**

