<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    protected $fillable = [
        'customer_id',
        'provider_id',
        'city_id',
        'service_id',
        'neighborhood_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'description',
        'urgency',
        'status',
        'address',
        'lat',
        'lng',
        'quoted_price',
        'final_price',
        'payment_status',
        'stripe_payment_intent_id',
        'requested_at',
        'claimed_at',
        'en_route_at',
        'arrived_at',
        'completed_at',
        'estimated_minutes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'claimed_at' => 'datetime',
        'en_route_at' => 'datetime',
        'arrived_at' => 'datetime',
        'completed_at' => 'datetime',
        'quoted_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
        'estimated_minutes' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(JobStatus::class)->orderBy('created_at', 'desc');
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(ProviderEarning::class);
    }

    public function isAvailable(): bool
    {
        return in_array($this->status, ['created', 'broadcast']);
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['claimed', 'en_route', 'arrived', 'in_progress']);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}

