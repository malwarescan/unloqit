<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\GeneratedContent;
use App\Models\Neighborhood;
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

class CityServiceNeighborhoodController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta,
        private IndexabilityGate $indexabilityGate,
        private PageDataService $pageData
    ) {}

    /**
     * Show neighborhood-service page using new canonical structure: /locksmith/{state}/{city}/{service}/{neighborhood}
     */
    public function show(string $state, string $citySlug, string $serviceSlug, string $neighborhoodSlug): View
    {
        // Normalize state to uppercase for lookup
        $stateUpper = strtoupper($state);
        
        $city = City::where('slug', $citySlug)
            ->where('state', $stateUpper)
            ->firstOrFail();
        $service = Service::where('slug', $serviceSlug)->firstOrFail();
        $neighborhood = Neighborhood::where('city_id', $city->id)
            ->where('slug', $neighborhoodSlug)
            ->firstOrFail();
        
        return $this->showServiceNeighborhood($city, $service, $neighborhood);
    }

    private function showServiceNeighborhood(City $city, Service $service, Neighborhood $neighborhood): View
    {
        // Check indexability - return 200 with noindex if not indexable
        $isIndexable = $this->indexabilityGate->isNeighborhoodServiceIndexable($city, $service, $neighborhood);

        $cityUrl = route('city.show', ['state' => strtolower($city->state), 'city' => $city->slug]);
        $serviceUrl = route('city.service.show', ['state' => strtolower($city->state), 'city' => $city->slug, 'service' => $service->slug]);
        $neighborhoodUrl = route('city.service.neighborhood.show', ['state' => strtolower($city->state), 'city' => $city->slug, 'service' => $service->slug, 'neighborhood' => $neighborhood->slug]);

        // Get neighborhood-specific data
        $neighborhoodData = $this->pageData->getNeighborhoodData($city, $service, $neighborhood);

        // Check for generated content first, fallback to TitleMetaService
        $generatedContent = GeneratedContent::where('content_type', 'neighborhood')
            ->where('city_id', $city->id)
            ->where('service_id', $service->id)
            ->where('neighborhood_id', $neighborhood->id)
            ->where('is_published', true)
            ->first();

        $titleMeta = $generatedContent 
            ? ['title' => $generatedContent->title, 'meta_description' => $generatedContent->meta_description]
            : $this->titleMeta->forNeighborhoodService($city, $service, $neighborhood);

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Locations', 'url' => route('locations.index')],
            ['name' => "{$city->name} Locksmith", 'url' => $cityUrl],
            ['name' => $service->name, 'url' => $serviceUrl],
            ['name' => "{$service->name} in {$neighborhood->name}", 'url' => $neighborhoodUrl],
        ];

        $schema = [
            OrganizationSchema::withServiceArea($city),
            ServiceSchema::forServiceInCity($service, $city),
            WebPageSchema::generate(
                $titleMeta['title'],
                $neighborhoodUrl,
                $titleMeta['meta_description']
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.city-service-neighborhood', [
            'city' => $city,
            'service' => $service,
            'neighborhood' => $neighborhood,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
            'neighborhoodUrl' => $neighborhoodUrl,
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
            'neighborhoodData' => $neighborhoodData,
            'isIndexable' => $isIndexable,
        ]);
    }
}

