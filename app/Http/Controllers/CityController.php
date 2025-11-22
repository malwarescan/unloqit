<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\GeneratedContent;
use App\Models\Service;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\LocalBusinessSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CityController extends Controller
{
    public function showCleveland(): View
    {
        $city = City::where('slug', 'cleveland')->firstOrFail();
        return $this->showCity($city);
    }

    public function show(string $citySlug): View
    {
        $city = City::where('slug', $citySlug)->firstOrFail();
        return $this->showCity($city);
    }

    private function showCity(City $city): View
    {
        $services = Service::all();
        
        $isCleveland = $city->slug === 'cleveland';
        $cityUrl = $isCleveland ? route('cleveland.show') : route('city.show', ['city' => $city->slug]);

        // Check for generated content
        $generatedContent = GeneratedContent::where('content_type', 'city')
            ->where('city_id', $city->id)
            ->where('is_published', true)
            ->first();

        $title = $generatedContent?->title ?? "{$city->name} Locksmith | 24/7 Lockout & Car Keys | Unloqit";
        $metaDescription = $generatedContent?->meta_description ?? "Reliable 24/7 locksmith services in {$city->name}, {$city->state}. Car lockouts, rekeys, key programming, residential & commercial. Fast arrival times.";

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => "{$city->name} Locksmith", 'url' => $cityUrl],
        ];

        $schema = [
            OrganizationSchema::base(),
            LocalBusinessSchema::forCity($city),
            WebPageSchema::generate(
                $title,
                $cityUrl,
                $metaDescription
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.city', [
            'city' => $city,
            'services' => $services,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
            'cityUrl' => $cityUrl,
            'generatedContent' => $generatedContent,
        ]);
    }
}

