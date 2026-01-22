<?php

namespace App\Services;

use App\Models\City;
use App\Models\Job;
use App\Models\Neighborhood;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class PageDataService
{
    public function __construct(
        private IndexabilityGate $indexabilityGate
    ) {}

    /**
     * Get live coverage data for a city
     */
    public function getCityCoverageData(City $city): array
    {
        $providerCount = $this->indexabilityGate->getCityProviderCount($city);
        
        // Get online providers right now
        $onlineProviders = Provider::where('is_active', true)
            ->where('is_verified', true)
            ->whereHas('availability', function ($query) {
                $query->where('is_online', true)
                    ->where('last_seen_at', '>', now()->subMinutes(15));
            })
            ->whereHas('cityServices', function ($query) use ($city) {
                $query->where('city_id', $city->id)
                    ->where('is_available', true);
            })
            ->count();

        // Calculate average response time from recent jobs
        $avgResponseTime = Job::where('city_id', $city->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(30))
            ->whereNotNull('requested_at')
            ->whereNotNull('arrived_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, requested_at, arrived_at)) as avg_minutes')
            ->value('avg_minutes');

        return [
            'provider_count' => $providerCount,
            'online_providers' => $onlineProviders,
            'avg_response_time_minutes' => $avgResponseTime ? round($avgResponseTime) : null,
        ];
    }

    /**
     * Get live coverage data for a city-service combination
     */
    public function getCityServiceCoverageData(City $city, Service $service): array
    {
        $providerCount = $this->indexabilityGate->getCityServiceProviderCount($city, $service);
        
        // Get online providers for this service
        $onlineProviders = Provider::where('is_active', true)
            ->where('is_verified', true)
            ->whereHas('availability', function ($query) {
                $query->where('is_online', true)
                    ->where('last_seen_at', '>', now()->subMinutes(15));
            })
            ->whereHas('cityServices', function ($query) use ($city, $service) {
                $query->where('city_id', $city->id)
                    ->where('service_id', $service->id)
                    ->where('is_available', true);
            })
            ->count();

        // Calculate average response time for this service
        $avgResponseTime = Job::where('city_id', $city->id)
            ->where('service_id', $service->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(30))
            ->whereNotNull('requested_at')
            ->whereNotNull('arrived_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, requested_at, arrived_at)) as avg_minutes')
            ->value('avg_minutes');

        return [
            'provider_count' => $providerCount,
            'online_providers' => $onlineProviders,
            'avg_response_time_minutes' => $avgResponseTime ? round($avgResponseTime) : null,
        ];
    }

    /**
     * Get recent service activity for a city-service combination
     */
    public function getCityServiceActivity(City $city, Service $service): array
    {
        $completedJobsCount = $this->indexabilityGate->getCityServiceCompletedJobsCount($city, $service, 30);
        
        // Get most common job types (if we had job types, for now use urgency)
        $urgencyDistribution = Job::where('city_id', $city->id)
            ->where('service_id', $service->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(30))
            ->select('urgency', DB::raw('count(*) as count'))
            ->groupBy('urgency')
            ->orderByDesc('count')
            ->get()
            ->pluck('count', 'urgency')
            ->toArray();

        // Get median final price (only if we have enough data)
        $medianPrice = null;
        if ($completedJobsCount >= 5) {
            $prices = Job::where('city_id', $city->id)
                ->where('service_id', $service->id)
                ->where('status', 'completed')
                ->where('completed_at', '>=', now()->subDays(90))
                ->whereNotNull('final_price')
                ->pluck('final_price')
                ->sort()
                ->values();

            if ($prices->count() > 0) {
                $middle = floor($prices->count() / 2);
                $medianPrice = $prices->count() % 2 === 0
                    ? ($prices[$middle - 1] + $prices[$middle]) / 2
                    : $prices[$middle];
            }
        }

        return [
            'completed_jobs_30_days' => $completedJobsCount,
            'urgency_distribution' => $urgencyDistribution,
            'median_price' => $medianPrice ? round($medianPrice, 2) : null,
        ];
    }

    /**
     * Get pricing range for a city-service combination
     */
    public function getCityServicePricingRange(City $city, Service $service): ?array
    {
        $prices = Job::where('city_id', $city->id)
            ->where('service_id', $service->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(90))
            ->whereNotNull('final_price')
            ->pluck('final_price')
            ->sort()
            ->values();

        if ($prices->count() < 5) {
            return null; // Not enough data
        }

        return [
            'min' => round($prices->first(), 2),
            'max' => round($prices->last(), 2),
            'median' => round($prices->median(), 2),
            'sample_size' => $prices->count(),
        ];
    }

    /**
     * Get neighborhood-specific data
     */
    public function getNeighborhoodData(City $city, Service $service, Neighborhood $neighborhood): array
    {
        $completedJobsCount = $this->indexabilityGate->getNeighborhoodCompletedJobsCount($city, $service, $neighborhood, 180);
        
        // Calculate average response time for this neighborhood
        $avgResponseTime = Job::where('city_id', $city->id)
            ->where('service_id', $service->id)
            ->where('neighborhood_id', $neighborhood->id)
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(180))
            ->whereNotNull('requested_at')
            ->whereNotNull('arrived_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, requested_at, arrived_at)) as avg_minutes')
            ->value('avg_minutes');

        return [
            'completed_jobs_180_days' => $completedJobsCount,
            'avg_response_time_minutes' => $avgResponseTime ? round($avgResponseTime) : null,
        ];
    }
}
