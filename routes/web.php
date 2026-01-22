<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CityServiceController;
use App\Http\Controllers\CityServiceNeighborhoodController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\ProDashboardController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// STEP 3: Explicit legacy URL redirects (MUST be at top, before any other routes)
// These prevent 404s and ensure proper 301 redirects
Route::permanentRedirect('/cleveland-locksmith', '/locksmith/oh/cleveland');

// Handle all Cleveland legacy subpaths
Route::get('/cleveland-locksmith/{any}', function ($any) {
    $path = ltrim($any, '/');
    // Handle service-only paths
    if (!str_contains($path, '/')) {
        return redirect()->to('/locksmith/oh/cleveland/' . $path, 301);
    }
    // Handle service/neighborhood paths
    return redirect()->to('/locksmith/oh/cleveland/' . $path, 301);
})->where('any', '.*');

// Homepage (nationwide)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Services directory
Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServicesController::class, 'show'])->name('services.show');

// Locations directory
Route::get('/locations', [LocationsController::class, 'index'])->name('locations.index');

// City pages (new canonical structure: /locksmith/{state}/{city})
Route::get('/locksmith/{state}/{city}', [CityController::class, 'show'])->name('city.show');
Route::get('/locksmith/{state}/{city}/{service}', [CityServiceController::class, 'show'])->name('city.service.show');
Route::get('/locksmith/{state}/{city}/{service}/{neighborhood}', [CityServiceNeighborhoodController::class, 'show'])->name('city.service.neighborhood.show');

// Marketplace - Customer Request Flow
Route::get('/request-locksmith', [RequestController::class, 'show'])->name('request.show');
Route::get('/request-locksmith/{city}/{service}', [RequestController::class, 'show'])->name('request.show.context');
Route::post('/request/submit', [RequestController::class, 'submit'])->name('request.submit');
Route::get('/request/track/{job}', [RequestController::class, 'track'])->name('request.track');

// Marketplace - Pro Authentication
use App\Http\Controllers\Auth\ProviderAuthController;

Route::get('/pro/register', [ProviderAuthController::class, 'showRegisterForm'])->name('pro.register');
Route::post('/pro/register', [ProviderAuthController::class, 'register'])->name('pro.register.submit');
Route::get('/pro/login', [ProviderAuthController::class, 'showLoginForm'])->name('pro.login');
Route::post('/pro/login', [ProviderAuthController::class, 'login'])->name('pro.login.submit');
Route::post('/pro/logout', [ProviderAuthController::class, 'logout'])->name('pro.logout');

// Marketplace - Pro Dashboard (requires auth)
Route::middleware('auth:provider')->group(function () {
    Route::get('/pro/dashboard', [ProDashboardController::class, 'dashboard'])->name('pro.dashboard');
Route::get('/pro/jobs', [ProDashboardController::class, 'jobs'])->name('pro.jobs');
Route::get('/pro/jobs/{job}', [ProDashboardController::class, 'showJob'])->name('pro.jobs.show');
Route::post('/pro/jobs/{job}/claim', [ProDashboardController::class, 'claimJob'])->name('pro.jobs.claim');
Route::post('/pro/jobs/{job}/status', [ProDashboardController::class, 'updateJobStatus'])->name('pro.jobs.status');
    Route::get('/pro/earnings', [ProDashboardController::class, 'earnings'])->name('pro.earnings');
    Route::post('/pro/toggle-online', [ProDashboardController::class, 'toggleOnline'])->name('pro.toggle-online');
});

// Guides and FAQ
Route::get('/guides/{slug}', [GuideController::class, 'show'])->name('guide.show');
Route::get('/faq/{slug}', [FaqController::class, 'show'])->name('faq.show');

// Sitemaps
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-services.xml', [SitemapController::class, 'services'])->name('sitemap.services');
Route::get('/sitemap-locations.xml', [SitemapController::class, 'locations'])->name('sitemap.locations');
Route::get('/sitemap-city-services.xml', [SitemapController::class, 'cityServices'])->name('sitemap.city-services');
Route::get('/sitemap-guides.xml', [SitemapController::class, 'guides'])->name('sitemap.guides');
Route::get('/sitemap-faq.xml', [SitemapController::class, 'faq'])->name('sitemap.faq');

