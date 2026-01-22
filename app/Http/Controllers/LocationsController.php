<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Services\IndexabilityGate;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use App\Services\TitleMetaService;
use Illuminate\View\View;

class LocationsController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta,
        private IndexabilityGate $indexabilityGate
    ) {}

    /**
     * Locations directory page (only shows covered cities)
     */
    public function index(): View
    {
        // Only show cities that pass indexability gate
        $cities = City::all()->filter(function ($city) {
            return $this->indexabilityGate->isCityIndexable($city);
        })->sortBy('name');

        // Group by state
        $citiesByState = $cities->groupBy('state');

        $titleMeta = $this->titleMeta->forLocationsDirectory();

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => 'Locations', 'url' => route('locations.index')],
        ];

        $schema = [
            OrganizationSchema::base(),
            WebPageSchema::generate(
                $titleMeta['title'],
                route('locations.index'),
                $titleMeta['meta_description']
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.locations', [
            'citiesByState' => $citiesByState,
            'cities' => $cities,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }
}
