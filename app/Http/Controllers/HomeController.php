<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Service;
use App\Services\Schema\FAQPageSchema;
use App\Services\Schema\LocalBusinessSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $cleveland = City::where('slug', 'cleveland')->first();
        $services = Service::all();
        
        $schema = [
            OrganizationSchema::base(),
            WebPageSchema::generate(
                'Unloqit - 24/7 Locksmith Services in Cleveland, Ohio',
                route('home'),
                'Reliable 24/7 locksmith services in Cleveland. Car lockouts, rekeys, key programming, residential & commercial. Fast arrival times.'
            ),
        ];

        if ($cleveland) {
            $schema[] = LocalBusinessSchema::forCity($cleveland);
        }

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.home', [
            'city' => $cleveland,
            'services' => $services,
            'jsonld' => $jsonld,
        ]);
    }
}

