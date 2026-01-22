<?php

namespace App\Services\Schema;

class WebSiteSchema
{
    /**
     * Generate WebSite schema with SearchAction for homepage
     */
    public static function withSearchAction(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => 'https://www.unloqit.com/#website',
            'name' => 'Unloqit',
            'url' => 'https://www.unloqit.com',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => 'https://www.unloqit.com/locations?query={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }
}
