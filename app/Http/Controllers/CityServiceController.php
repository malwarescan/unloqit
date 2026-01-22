<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\GeneratedContent;
use App\Models\Service;
use App\Services\IndexabilityGate;
use App\Services\PageDataService;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\LocalBusinessSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\ServiceSchema;
use App\Services\Schema\WebPageSchema;
use App\Services\TitleMetaService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityServiceController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta,
        private IndexabilityGate $indexabilityGate,
        private PageDataService $pageData
    ) {}

    /**
     * Show city-service page using new canonical structure: /locksmith/{state}/{city}/{service}
     */
    public function show(string $state, string $citySlug, string $serviceSlug): View
    {
        // Normalize state to uppercase for lookup
        $stateUpper = strtoupper($state);
        
        $city = City::where('slug', $citySlug)
            ->where('state', $stateUpper)
            ->firstOrFail();
        $service = Service::where('slug', $serviceSlug)->firstOrFail();
        
        return $this->showService($city, $service);
    }

    private function showService(City $city, Service $service): View
    {
        // Check indexability - return 200 with noindex if not indexable
        $isIndexable = $this->indexabilityGate->isCityServiceIndexable($city, $service);

        $cityUrl = route('city.show', ['state' => strtolower($city->state), 'city' => $city->slug]);
        $serviceUrl = route('city.service.show', ['state' => strtolower($city->state), 'city' => $city->slug, 'service' => $service->slug]);

        // Get real data modules
        $coverageData = $this->pageData->getCityServiceCoverageData($city, $service);
        $activityData = $this->pageData->getCityServiceActivity($city, $service);
        $pricingRange = $this->pageData->getCityServicePricingRange($city, $service);

        // Check for generated content first, fallback to TitleMetaService
        $generatedContent = GeneratedContent::where('content_type', 'service')
            ->where('city_id', $city->id)
            ->where('service_id', $service->id)
            ->where('is_published', true)
            ->first();

        $titleMeta = $generatedContent 
            ? ['title' => $generatedContent->title, 'meta_description' => $generatedContent->meta_description]
            : $this->titleMeta->forCityService($city, $service);

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Locations', 'url' => route('locations.index')],
            ['name' => "{$city->name} Locksmith", 'url' => $cityUrl],
            ['name' => $service->name, 'url' => $serviceUrl],
        ];

        $schema = [
            OrganizationSchema::withServiceArea($city),
            ServiceSchema::forServiceInCity($service, $city),
            WebPageSchema::generate(
                $titleMeta['title'],
                $serviceUrl,
                $titleMeta['meta_description']
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.city-service', [
            'city' => $city,
            'service' => $service,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
            'serviceUrl' => $serviceUrl,
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
            'coverageData' => $coverageData,
            'activityData' => $activityData,
            'pricingRange' => $pricingRange,
            'isIndexable' => $isIndexable,
        ]);
    }
}

