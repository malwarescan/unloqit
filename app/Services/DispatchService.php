<?php

namespace App\Services;

use App\Models\City;
use App\Models\Job;
use App\Models\Provider;
use App\Models\ProviderAvailability;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class DispatchService
{
    /**
     * Broadcast a job to nearby available providers
     */
    public function broadcastJob(Job $job): array
    {
        $job->update(['status' => 'broadcast']);
        $this->logStatus($job, 'broadcast', 'Job broadcast to nearby providers');

        $availableProviders = $this->findAvailableProviders($job);
        
        // In a real system, this would send push notifications
        // For now, we just return the list
        return $availableProviders->map(function ($provider) {
            return [
                'id' => $provider->id,
                'name' => $provider->name,
                'rating' => $provider->rating,
                'response_time' => $provider->response_time,
                'distance' => null, // Would calculate based on lat/lng
            ];
        })->toArray();
    }

    /**
     * Find available providers for a job
     */
    public function findAvailableProviders(Job $job): \Illuminate\Database\Eloquent\Collection
    {
        return Provider::where('is_active', true)
            ->where('is_verified', true)
            ->whereHas('availability', function ($query) {
                $query->where('is_online', true)
                    ->where('last_seen_at', '>', now()->subMinutes(15))
                    ->whereColumn('active_jobs_count', '<', 'max_jobs_per_hour');
            })
            ->whereHas('cityServices', function ($query) use ($job) {
                $query->where('city_id', $job->city_id)
                    ->where('service_id', $job->service_id)
                    ->where('is_available', true);
            })
            ->with('availability')
            ->orderBy('rating', 'desc')
            ->orderBy('response_time', 'asc')
            ->get();
    }

    /**
     * Claim a job for a provider
     */
    public function claimJob(Job $job, Provider $provider): bool
    {
        if (!$job->isAvailable()) {
            return false;
        }

        if (!$provider->canAcceptJob()) {
            return false;
        }

        DB::transaction(function () use ($job, $provider) {
            $job->update([
                'status' => 'claimed',
                'provider_id' => $provider->id,
                'claimed_at' => now(),
            ]);

            $this->logStatus($job, 'claimed', "Claimed by {$provider->name}", $provider->id);

            // Update provider availability
            if ($provider->availability) {
                $provider->availability->increment('active_jobs_count');
            }
        });

        return true;
    }

    /**
     * Update job status
     */
    public function updateJobStatus(Job $job, string $status, ?string $notes = null, ?Provider $provider = null): void
    {
        $job->update(['status' => $status]);

        $timestamps = [
            'en_route' => 'en_route_at',
            'arrived' => 'arrived_at',
            'completed' => 'completed_at',
        ];

        if (isset($timestamps[$status])) {
            $job->update([$timestamps[$status] => now()]);
        }

        $this->logStatus($job, $status, $notes, $provider?->id);

        // If completed, release provider availability
        if ($status === 'completed' && $provider && $provider->availability) {
            $provider->availability->decrement('active_jobs_count');
        }
    }

    /**
     * Log job status change
     */
    protected function logStatus(Job $job, string $status, ?string $notes = null, ?int $providerId = null): void
    {
        $job->statuses()->create([
            'status' => $status,
            'notes' => $notes,
            'updated_by_provider_id' => $providerId,
        ]);
    }
}

