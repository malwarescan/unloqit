<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\FAQPageSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use App\Services\TitleMetaService;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __construct(
        private TitleMetaService $titleMeta
    ) {}

    public function show(string $slug): View
    {
        $faq = Faq::where('slug', $slug)->firstOrFail();

        $titleMeta = $this->titleMeta->forFaq($faq->question);

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => $faq->question, 'url' => route('faq.show', ['slug' => $faq->slug])],
        ];

        $schema = [
            OrganizationSchema::base(),
            FAQPageSchema::fromSingleFaq($faq),
            WebPageSchema::generate(
                $titleMeta['title'],
                route('faq.show', ['slug' => $faq->slug]),
                $titleMeta['meta_description']
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.faq', [
            'faq' => $faq,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
            'title' => $titleMeta['title'],
            'meta_description' => $titleMeta['meta_description'],
        ]);
    }
}

