<?php

namespace App\Services\Schema;

class OrganizationSchema
{
    public static function base(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Unloqit',
            'url' => 'https://unloqit.com',
            'logo' => 'https://unloqit.com/unloqit-logo.png',
            'description' => 'On-demand locksmith services in Cleveland, Ohio. 24/7 emergency locksmith, car lockouts, key programming, and more.',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Cleveland',
                'addressRegion' => 'OH',
                'addressCountry' => 'US',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Service',
                'areaServed' => 'US',
            ],
            'sameAs' => [],
        ];
    }
}

