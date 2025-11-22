<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use Illuminate\View\View;

class GuideController extends Controller
{
    public function show(string $slug): View
    {
        $guide = Guide::where('slug', $slug)->firstOrFail();

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => $guide->title, 'url' => route('guide.show', ['slug' => $guide->slug])],
        ];

        $schema = [
            OrganizationSchema::base(),
            WebPageSchema::generate(
                "{$guide->title} | Unloqit",
                route('guide.show', ['slug' => $guide->slug]),
                strip_tags(substr($guide->content, 0, 160))
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.guide', [
            'guide' => $guide,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}

