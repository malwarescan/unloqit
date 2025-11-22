<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Service;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\LocalBusinessSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\ServiceSchema;
use App\Services\Schema\WebPageSchema;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityServiceController extends Controller
{
    public function showClevelandService(string $serviceSlug): View
    {
        $city = City::where('slug', 'cleveland')->firstOrFail();
        $service = Service::where('slug', $serviceSlug)->firstOrFail();
        return $this->showService($city, $service);
    }

    public function show(string $citySlug, string $serviceSlug): View
    {
        $city = City::where('slug', $citySlug)->firstOrFail();
        $service = Service::where('slug', $serviceSlug)->firstOrFail();
        return $this->showService($city, $service);
    }

    private function showService(City $city, Service $service): View
    {
        $isCleveland = $city->slug === 'cleveland';
        $cityUrl = $isCleveland ? route('cleveland.show') : route('city.show', ['city' => $city->slug]);
        $serviceUrl = $isCleveland ? route('cleveland.service.show', ['service' => $service->slug]) : route('city.service.show', ['city' => $city->slug, 'service' => $service->slug]);

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => "{$city->name} Locksmith", 'url' => $cityUrl],
            ['name' => $service->name, 'url' => $serviceUrl],
        ];

        $schema = [
            OrganizationSchema::base(),
            LocalBusinessSchema::forCity($city),
            ServiceSchema::forServiceInCity($service, $city),
            WebPageSchema::generate(
                "{$service->name} in {$city->name} | {$city->name} Locksmith | Unloqit",
                $serviceUrl,
                "Professional {$service->name} services in {$city->name}, {$city->state}. Fast, reliable, and available 24/7."
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
        ]);
    }
}

