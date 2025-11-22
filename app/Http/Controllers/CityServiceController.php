<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\GeneratedContent;
use App\Models\Service;
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
        private TitleMetaService $titleMeta
    ) {}

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
            ['name' => "{$city->name} Locksmith", 'url' => $cityUrl],
            ['name' => $service->name, 'url' => $serviceUrl],
        ];

        $schema = [
            OrganizationSchema::base(),
            LocalBusinessSchema::forCity($city),
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
        ]);
    }
}

