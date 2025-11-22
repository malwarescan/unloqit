<?php

namespace App\Services;

use App\Models\City;
use App\Models\Neighborhood;
use App\Models\Service;
use App\Models\CityServicePage;
use Illuminate\Support\Str;

class CityRolloutService
{
    protected ContentGeneratorService $contentGenerator;

    public function __construct(ContentGeneratorService $contentGenerator)
    {
        $this->contentGenerator = $contentGenerator;
    }

    /**
     * Rollout a new city with all services and content
     */
    public function rolloutCity(array $cityData, array $neighborhoods = [], bool $generateContent = true): City
    {
        // Create city
        $city = City::create([
            'name' => $cityData['name'],
            'slug' => $cityData['slug'] ?? Str::slug($cityData['name']),
            'state' => $cityData['state'],
            'lat' => $cityData['lat'] ?? null,
            'lng' => $cityData['lng'] ?? null,
        ]);

        // Create neighborhoods
        foreach ($neighborhoods as $neighborhoodName) {
            Neighborhood::create([
                'city_id' => $city->id,
                'name' => $neighborhoodName,
                'slug' => Str::slug($neighborhoodName),
            ]);
        }

        // Create city-service pages for all services
        $services = Service::all();
        foreach ($services as $service) {
            CityServicePage::create([
                'city_id' => $city->id,
                'service_id' => $service->id,
            ]);

            // Generate content if requested
            if ($generateContent) {
                $this->contentGenerator->generateCityServiceContent($city, $service);

                // Generate neighborhood content for each neighborhood
                foreach ($city->neighborhoods as $neighborhood) {
                    $this->contentGenerator->generateNeighborhoodContent($city, $service, $neighborhood);
                }
            }
        }

        // Generate city landing page content
        if ($generateContent) {
            $this->contentGenerator->generateCityContent($city);
        }

        return $city->fresh(['neighborhoods', 'services']);
    }

    /**
     * Add a service to an existing city
     */
    public function addServiceToCity(City $city, Service $service, bool $generateContent = true): CityServicePage
    {
        $cityServicePage = CityServicePage::firstOrCreate([
            'city_id' => $city->id,
            'service_id' => $service->id,
        ]);

        if ($generateContent) {
            $this->contentGenerator->generateCityServiceContent($city, $service);

            // Generate content for all neighborhoods
            foreach ($city->neighborhoods as $neighborhood) {
                $this->contentGenerator->generateNeighborhoodContent($city, $service, $neighborhood);
            }
        }

        return $cityServicePage;
    }

    /**
     * Add neighborhoods to an existing city
     */
    public function addNeighborhoodsToCity(City $city, array $neighborhoodNames, bool $generateContent = true): array
    {
        $neighborhoods = [];
        $services = Service::all();

        foreach ($neighborhoodNames as $name) {
            $neighborhood = Neighborhood::firstOrCreate([
                'city_id' => $city->id,
                'slug' => Str::slug($name),
            ], [
                'name' => $name,
            ]);

            $neighborhoods[] = $neighborhood;

            // Generate content for all services in this neighborhood
            if ($generateContent) {
                foreach ($services as $service) {
                    $this->contentGenerator->generateNeighborhoodContent($city, $service, $neighborhood);
                }
            }
        }

        return $neighborhoods;
    }

    /**
     * Bulk rollout multiple cities
     */
    public function bulkRollout(array $citiesData): array
    {
        $results = [];

        foreach ($citiesData as $cityData) {
            try {
                $city = $this->rolloutCity(
                    $cityData,
                    $cityData['neighborhoods'] ?? [],
                    $cityData['generate_content'] ?? true
                );
                $results[] = [
                    'success' => true,
                    'city' => $city->name,
                    'slug' => $city->slug,
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'success' => false,
                    'city' => $cityData['name'] ?? 'Unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}

