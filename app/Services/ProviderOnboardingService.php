<?php

namespace App\Services;

use App\Models\City;
use App\Models\Provider;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ProviderOnboardingService
{
    /**
     * Onboard a new provider
     */
    public function onboardProvider(array $providerData, array $cityIds = [], array $serviceIds = []): Provider
    {
        $provider = Provider::create([
            'name' => $providerData['name'],
            'email' => $providerData['email'],
            'phone' => $providerData['phone'] ?? null,
            'bio' => $providerData['bio'] ?? null,
            'license_number' => $providerData['license_number'] ?? null,
            'is_verified' => $providerData['is_verified'] ?? false,
            'is_active' => $providerData['is_active'] ?? true,
            'service_areas' => $cityIds,
            'service_types' => $serviceIds,
            'availability' => $providerData['availability'] ?? null,
            'response_time' => $providerData['response_time'] ?? null,
        ]);

        // Create provider-city-service relationships
        if (!empty($cityIds) && !empty($serviceIds)) {
            $this->assignProviderToCityServices($provider, $cityIds, $serviceIds);
        }

        return $provider;
    }

    /**
     * Assign provider to city-service combinations
     */
    public function assignProviderToCityServices(Provider $provider, array $cityIds, array $serviceIds, array $options = []): void
    {
        $cities = City::whereIn('id', $cityIds)->get();
        $services = Service::whereIn('id', $serviceIds)->get();

        foreach ($cities as $city) {
            foreach ($services as $service) {
                DB::table('provider_city_service')->updateOrInsert(
                    [
                        'provider_id' => $provider->id,
                        'city_id' => $city->id,
                        'service_id' => $service->id,
                    ],
                    [
                        'is_available' => $options['is_available'] ?? true,
                        'base_price' => $options['base_price'] ?? null,
                        'response_time' => $options['response_time'] ?? $provider->response_time,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * Update provider availability
     */
    public function updateProviderAvailability(Provider $provider, array $cityIds, array $serviceIds, bool $isAvailable): void
    {
        DB::table('provider_city_service')
            ->where('provider_id', $provider->id)
            ->whereIn('city_id', $cityIds)
            ->whereIn('service_id', $serviceIds)
            ->update([
                'is_available' => $isAvailable,
                'updated_at' => now(),
            ]);
    }

    /**
     * Verify a provider
     */
    public function verifyProvider(Provider $provider): Provider
    {
        $provider->update([
            'is_verified' => true,
        ]);

        return $provider->fresh();
    }

    /**
     * Deactivate a provider
     */
    public function deactivateProvider(Provider $provider): Provider
    {
        $provider->update([
            'is_active' => false,
        ]);

        // Also mark all their assignments as unavailable
        DB::table('provider_city_service')
            ->where('provider_id', $provider->id)
            ->update(['is_available' => false]);

        return $provider->fresh();
    }

    /**
     * Update provider rating
     */
    public function updateProviderRating(Provider $provider, float $rating): Provider
    {
        $provider->update([
            'rating' => $rating,
        ]);

        return $provider->fresh();
    }

    /**
     * Get available providers for a city-service combination
     */
    public function getAvailableProviders(City $city, Service $service): \Illuminate\Database\Eloquent\Collection
    {
        return Provider::where('is_active', true)
            ->where('is_verified', true)
            ->whereHas('cityServices', function ($query) use ($city, $service) {
                $query->where('city_id', $city->id)
                    ->where('service_id', $service->id)
                    ->where('is_available', true);
            })
            ->get();
    }
}

