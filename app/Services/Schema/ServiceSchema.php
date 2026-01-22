<?php

namespace App\Services\Schema;

use App\Models\City;
use App\Models\Service;

class ServiceSchema
{
    public static function forServiceInCity(Service $service, City $city): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => "{$service->name} in {$city->name}",
            'description' => $service->description ?? "Professional {$service->name} services in {$city->name}, {$city->state}.",
            'provider' => [
                '@id' => 'https://www.unloqit.com/#organization',
                '@type' => 'Organization',
                'name' => 'Unloqit',
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $city->name,
                'addressRegion' => $city->state,
                'addressCountry' => 'US',
            ],
            'serviceType' => $service->name,
        ];
    }
}

