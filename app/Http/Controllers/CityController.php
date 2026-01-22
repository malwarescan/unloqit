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
use App\Services\Schema\WebPageSchema;
use App\Services\TitleMetaService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CityController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta,
        private IndexabilityGate $indexabilityGate,
        private PageDataService $pageData
    ) {}

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
        // Check indexability - return 404 if not indexable
        if (!$this->indexabilityGate->isCityIndexable($city)) {
            abort(404);
        }

        $services = Service::all();
        
        $isCleveland = $city->slug === 'cleveland';
        $cityUrl = $isCleveland ? route('cleveland.show') : route('city.show', ['city' => $city->slug]);

        // Get real coverage data
        $coverageData = $this->pageData->getCityCoverageData($city);

        // Check for generated content first, fallback to TitleMetaService
        $generatedContent = GeneratedContent::where('content_type', 'city')
            ->where('city_id', $city->id)
            ->where('is_published', true)
            ->first();

        $titleMeta = $generatedContent 
            ? ['title' => $generatedContent->title, 'meta_description' => $generatedContent->meta_description]
            : $this->titleMeta->forCity($city);

        $title = $titleMeta['title'];
        $metaDescription = $titleMeta['meta_description'];

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => "{$city->name} Locksmith", 'url' => $cityUrl],
        ];

        $schema = [
            OrganizationSchema::withServiceArea($city),
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
            'title' => $title,
            'meta_description' => $metaDescription,
            'coverageData' => $coverageData,
            'isIndexable' => true,
        ]);
    }
}

