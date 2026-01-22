<?php

namespace App\Services\Schema;

use App\Models\City;

class LocalBusinessSchema
{
    /**
     * Generate service area schema for Organization (not fake LocalBusiness per city)
     * This represents the marketplace's service coverage area, not a physical location
     */
    public static function forServiceArea(City $city): array
    {
        return [
            '@type' => 'City',
            'name' => $city->name,
            'addressRegion' => $city->state,
            'addressCountry' => 'US',
        ];
    }

    /**
     * @deprecated Use OrganizationSchema with areaServed instead
     * This method is kept for backward compatibility but should not be used for new pages
     */
    public static function forCity(City $city): array
    {
        // Return minimal schema - we should use Organization with areaServed instead
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Unloqit',
            'url' => 'https://unloqit.com',
            'areaServed' => [
                '@type' => 'City',
                'name' => $city->name,
                'addressRegion' => $city->state,
            ],
        ];
    }
}

