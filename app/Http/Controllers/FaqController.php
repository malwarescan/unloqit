<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Services\Schema\BreadcrumbSchema;
use App\Services\Schema\FAQPageSchema;
use App\Services\Schema\OrganizationSchema;
use App\Services\Schema\WebPageSchema;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function show(string $slug): View
    {
        $faq = Faq::where('slug', $slug)->firstOrFail();

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('home')],
            ['name' => $faq->question, 'url' => route('faq.show', ['slug' => $faq->slug])],
        ];

        $schema = [
            OrganizationSchema::base(),
            FAQPageSchema::fromSingleFaq($faq),
            WebPageSchema::generate(
                "{$faq->question} | Unloqit FAQ",
                route('faq.show', ['slug' => $faq->slug]),
                strip_tags(substr($faq->answer, 0, 160))
            ),
            BreadcrumbSchema::generate($breadcrumbs),
        ];

        $jsonld = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return view('pages.faq', [
            'faq' => $faq,
            'jsonld' => $jsonld,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}

