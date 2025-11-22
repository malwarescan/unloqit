<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Neighborhood;
use App\Models\Service;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\LocalBusinessSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\ServiceSchema;
use App\Services\Schema\WebPageSchema;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityServiceNeighborhoodController extends Controller
{
    public function showClevelandServiceNeighborhood(string $serviceSlug, string $neighborhoodSlug): View
    {
        $city = City::where('slug', 'cleveland')->firstOrFail();
        $service = Service::where('slug', $serviceSlug)->firstOrFail();
        $neighborhood = Neighborhood::where('city_id', $city->id)
            ->where('slug', $neighborhoodSlug)
            ->firstOrFail();
        return $this->showServiceNeighborhood($city, $service, $neighborhood);
    }

    public function show(string $citySlug, string $serviceSlug, string $neighborhoodSlug): View
    {
        $city = City::where('slug', $citySlug)->firstOrFail();
        $service = Service::where('slug', $serviceSlug)->firstOrFail();
        $neighborhood = Neighborhood::where('city_id', $city->id)
            ->where('slug', $neighborhoodSlug)
            ->firstOrFail();
        return $this->showServiceNeighborhood($city, $service, $neighborhood);
    }

    private function showServiceNeighborhood(City $city, Service $service, Neighborhood $neighborhood): View
    {
        $isCleveland = $city->slug === 'cleveland';
        $cityUrl = $isCleveland ? route('cleveland.show') : route('city.show', ['city' => $city->slug]);
        $serviceUrl = $isCleveland ? route('cleveland.service.show', ['service' => $service->slug]) : route('city.service.show', ['city' => $city->slug, 'service' => $service->slug]);
        $neighborhoodUrl = $isCleveland ? route('cleveland.service.neighborhood.show', ['service' => $service->slug, 'neighborhood' => $neighborhood->slug]) : route('city.service.neighborhood.show', ['city' => $city->slug, 'service' => $service->slug, 'neighborhood' => $neighborhood->slug]);

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => "{$city->name} Locksmith", 'url' => $cityUrl],
            ['name' => $service->name, 'url' => $serviceUrl],
            ['name' => "{$service->name} in {$neighborhood->name}", 'url' => $neighborhoodUrl],
        ];

        $schema = [
            OrganizationSchema::base(),
            LocalBusinessSchema::forCity($city),
            ServiceSchema::forServiceInCity($service, $city),
            WebPageSchema::generate(
                "{$service->name} in {$neighborhood->name}, {$city->name} | Unloqit",
                $neighborhoodUrl,
                "Professional {$service->name} services in {$neighborhood->name}, {$city->name}, {$city->state}. Fast, reliable, and available 24/7."
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
        ]);
    }
}

