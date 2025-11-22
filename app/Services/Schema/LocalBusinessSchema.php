<?php

namespace App\Services\Schema;

use App\Models\City;

class LocalBusinessSchema
{
    public static function forCity(City $city): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            '@id' => 'https://unloqit.com/#localbusiness',
            'name' => "Unloqit - {$city->name} Locksmith",
            'image' => 'https://unloqit.com/unloqit-logo.png',
            'description' => "24/7 locksmith services in {$city->name}, {$city->state}. Emergency lockout service, car keys, rekeying, and more.",
            'url' => 'https://unloqit.com',
            'telephone' => '+1-XXX-XXX-XXXX',
            'priceRange' => '$$',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $city->name,
                'addressRegion' => $city->state,
                'addressCountry' => 'US',
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $city->name,
            ],
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'Sunday',
                ],
                'opens' => '00:00',
                'closes' => '23:59',
            ],
        ];

        if ($city->lat && $city->lng) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $city->lat,
                'longitude' => (float) $city->lng,
            ];
        }

        return $schema;
    }
}

