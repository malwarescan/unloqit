<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CityServiceController;
use App\Http\Controllers\CityServiceNeighborhoodController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProDashboardController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Root market (Cleveland) URLs
Route::get('/cleveland-locksmith', [CityController::class, 'showCleveland'])->name('cleveland.show');
Route::get('/cleveland-locksmith/{service}', [CityServiceController::class, 'showClevelandService'])->name('cleveland.service.show');
Route::get('/cleveland-locksmith/{service}/{neighborhood}', [CityServiceNeighborhoodController::class, 'showClevelandServiceNeighborhood'])->name('cleveland.service.neighborhood.show');

// Future cities
Route::get('/locksmith/{city}', [CityController::class, 'show'])->name('city.show');
Route::get('/locksmith/{city}/{service}', [CityServiceController::class, 'show'])->name('city.service.show');
Route::get('/locksmith/{city}/{service}/{neighborhood}', [CityServiceNeighborhoodController::class, 'show'])->name('city.service.neighborhood.show');

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
Route::get('/sitemap-cities.xml', [SitemapController::class, 'cities'])->name('sitemap.cities');
Route::get('/sitemap-services.xml', [SitemapController::class, 'services'])->name('sitemap.services');
Route::get('/sitemap-guides.xml', [SitemapController::class, 'guides'])->name('sitemap.guides');
Route::get('/sitemap-faq.xml', [SitemapController::class, 'faq'])->name('sitemap.faq');
Route::get('/sitemap.ndjson', [SitemapController::class, 'ndjson'])->name('sitemap.ndjson');

