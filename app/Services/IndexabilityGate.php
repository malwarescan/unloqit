<?php

namespace App\Services;

use App\Models\City;
use App\Models\Job;
use App\Models\Neighborhood;
use App\Models\Provider;
use App\Models\Service;

class IndexabilityGate
{
    /**
     * Minimum number of providers required for city page to be indexable
     */
    private const MIN_PROVIDERS_CITY = 3;

    /**
     * Minimum number of providers required for city-service page to be indexable
     */
    private const MIN_PROVIDERS_CITY_SERVICE = 2;

    /**
     * Minimum number of completed jobs required for city (alternative proof)
     */
    private const MIN_JOBS_CITY = 15;

    /**
     * Minimum number of completed jobs required for neighborhood
     */
    private const MIN_JOBS_NEIGHBORHOOD = 10;

    /**
     * Check if a city page should be indexable
     */
    public function isCityIndexable(City $city): bool
    {
        // Count verified, active providers serving this city
        $providerCount = Provider::where('is_active', true)
            ->where('is_verified', true)
            ->whereHas('cityServices', function ($query) use ($city) {
                $query->where('city_id', $city->id)
                    ->where('is_available', true);
            })
            ->count();

        if ($providerCount >= self::MIN_PROVIDERS_CITY) {
            return true;
        }

        // Alternative: Check for recent completed jobs as proof of coverage
        $completedJobsCount = Job::where('city_id', $city->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(90))
            ->count();

        return $completedJobsCount >= self::MIN_JOBS_CITY;
    }

    /**
     * Check if a city-service page should be indexable
     */
    public function isCityServiceIndexable(City $city, Service $service): bool
    {
        // Count verified, active providers offering this service in this city
        $providerCount = Provider::where('is_active', true)
            ->where('is_verified', true)
            ->whereHas('cityServices', function ($query) use ($city, $service) {
                $query->where('city_id', $city->id)
                    ->where('service_id', $service->id)
                    ->where('is_available', true);
            })
            ->count();

        if ($providerCount >= self::MIN_PROVIDERS_CITY_SERVICE) {
            return true;
        }

        // City-service pages require both city gate AND service-specific proof
        // (No alternative job count - must have providers)
        return false;
    }

    /**
     * Check if a neighborhood-service page should be indexable
     */
    public function isNeighborhoodServiceIndexable(City $city, Service $service, Neighborhood $neighborhood): bool
    {
        // First, city-service must be indexable
        if (!$this->isCityServiceIndexable($city, $service)) {
            return false;
        }

        // Check for jobs served in this neighborhood as proof
        $neighborhoodJobsCount = Job::where('city_id', $city->id)
            ->where('service_id', $service->id)
            ->where('neighborhood_id', $neighborhood->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(180))
            ->count();

        // Require at least MIN_JOBS_NEIGHBORHOOD completed jobs in this neighborhood
        return $neighborhoodJobsCount >= self::MIN_JOBS_NEIGHBORHOOD;
    }

    /**
     * Get provider count for a city
     */
    public function getCityProviderCount(City $city): int
    {
        return Provider::where('is_active', true)
            ->where('is_verified', true)
            ->whereHas('cityServices', function ($query) use ($city) {
                $query->where('city_id', $city->id)
                    ->where('is_available', true);
            })
            ->count();
    }

    /**
     * Get provider count for a city-service combination
     */
    public function getCityServiceProviderCount(City $city, Service $service): int
    {
        return Provider::where('is_active', true)
            ->where('is_verified', true)
            ->whereHas('cityServices', function ($query) use ($city, $service) {
                $query->where('city_id', $city->id)
                    ->where('service_id', $service->id)
                    ->where('is_available', true);
            })
            ->count();
    }

    /**
     * Get completed jobs count for a city-service combination (last 90 days)
     */
    public function getCityServiceCompletedJobsCount(City $city, Service $service, int $days = 90): int
    {
        return Job::where('city_id', $city->id)
            ->where('service_id', $service->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays($days))
            ->count();
    }

    /**
     * Get neighborhood completed jobs count
     */
    public function getNeighborhoodCompletedJobsCount(City $city, Service $service, Neighborhood $neighborhood, int $days = 180): int
    {
        return Job::where('city_id', $city->id)
            ->where('service_id', $service->id)
            ->where('neighborhood_id', $neighborhood->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays($days))
            ->count();
    }
}
