<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use App\Services\Schema\WebSiteSchema;
use App\Services\TitleMetaService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta
    ) {}

    public function index(): View
    {
        $services = Service::all();
        
        $titleMeta = $this->titleMeta->forHome();
        
        // Homepage schema: Organization + WebSite (with SearchAction) + WebPage
        // NO LocalBusiness schema on homepage
        $schema = [
            OrganizationSchema::base(),
            WebSiteSchema::withSearchAction(),
            WebPageSchema::generate(
                $titleMeta['title'],
                route('home'),
                $titleMeta['meta_description']
            ),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.home', [
            'services' => $services,
            'jsonld' => $jsonld,
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }
}

