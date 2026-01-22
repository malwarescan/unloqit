<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use App\Services\TitleMetaService;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta
    ) {}

    /**
     * Services directory page
     */
    public function index(): View
    {
        $services = Service::all();
        $titleMeta = $this->titleMeta->forServicesDirectory();

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Services', 'url' => route('services.index')],
        ];

        $schema = [
            OrganizationSchema::base(),
            WebPageSchema::generate(
                $titleMeta['title'],
                route('services.index'),
                $titleMeta['meta_description']
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.services', [
            'services' => $services,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }

    /**
     * Individual service explainer page
     */
    public function show(string $serviceSlug): View
    {
        $service = Service::where('slug', $serviceSlug)->firstOrFail();
        $titleMeta = $this->titleMeta->forServicePage($service);

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Services', 'url' => route('services.index')],
            ['name' => $service->name, 'url' => route('services.show', ['service' => $service->slug])],
        ];

        $schema = [
            OrganizationSchema::base(),
            WebPageSchema::generate(
                $titleMeta['title'],
                route('services.show', ['service' => $service->slug]),
                $titleMeta['meta_description']
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.service', [
            'service' => $service,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }
}
