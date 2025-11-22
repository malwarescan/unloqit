<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderAvailability extends Model
{
    protected $table = 'provider_availability';

    protected $fillable = [
        'provider_id',
        'is_online',
        'current_lat',
        'current_lng',
        'last_seen_at',
        'max_jobs_per_hour',
        'active_jobs_count',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'current_lat' => 'decimal:8',
        'current_lng' => 'decimal:8',
        'last_seen_at' => 'datetime',
        'max_jobs_per_hour' => 'integer',
        'active_jobs_count' => 'integer',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function canAcceptJob(): bool
    {
        return $this->is_online 
            && $this->active_jobs_count < $this->max_jobs_per_hour
            && $this->last_seen_at && $this->last_seen_at->isAfter(now()->subMinutes(15));
    }
}

