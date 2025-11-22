<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use App\Services\TitleMetaService;
use Illuminate\View\View;

class GuideController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta
    ) {}

    public function show(string $slug): View
    {
        $guide = Guide::where('slug', $slug)->firstOrFail();

        $titleMeta = $this->titleMeta->forGuide($guide->title);

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => $guide->title, 'url' => route('guide.show', ['slug' => $guide->slug])],
        ];

        $schema = [
            OrganizationSchema::base(),
            WebPageSchema::generate(
                $titleMeta['title'],
                route('guide.show', ['slug' => $guide->slug]),
                $titleMeta['meta_description']
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.guide', [
            'guide' => $guide,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }
}

