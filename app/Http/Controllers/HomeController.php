<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Service;
use App\Services\Schema\FAQPageSchema;
use App\Services\Schema\LocalBusinessSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use App\Services\TitleMetaService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta
    ) {}

    public function index(): View
    {
        $cleveland = City::where('slug', 'cleveland')->first();
        $services = Service::all();
        
        $titleMeta = $this->titleMeta->forHome();
        
        $schema = [
            OrganizationSchema::base(),
            WebPageSchema::generate(
                $titleMeta['title'],
                route('home'),
                $titleMeta['meta_description']
            ),
        ];

        if ($cleveland) {
            $schema[0] = OrganizationSchema::withServiceArea($cleveland);
        }

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.home', [
            'city' => $cleveland,
            'services' => $services,
            'jsonld' => $jsonld,
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }
}

