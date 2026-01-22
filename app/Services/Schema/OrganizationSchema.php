<?php

namespace App\Services\Schema;

use App\Models\City;

class OrganizationSchema
{
    /**
     * Base organization schema with stable @id
     */
    public static function base(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => 'https://unloqit.com/#organization',
            'name' => 'Unloqit',
            'url' => 'https://unloqit.com',
            'logo' => 'https://unloqit.com/unloqit-logo.png',
            'description' => 'On-demand locksmith marketplace connecting customers with verified locksmith professionals. 24/7 emergency locksmith services, car lockouts, key programming, and more.',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'Customer Service',
                'areaServed' => 'US',
            ],
            'sameAs' => [],
        ];
    }

    /**
     * Organization schema with service area for a specific city
     */
    public static function withServiceArea(City $city): array
    {
        $base = self::base();
        $base['areaServed'] = [
            '@type' => 'City',
            'name' => $city->name,
            'addressRegion' => $city->state,
            'addressCountry' => 'US',
        ];
        return $base;
    }
}

