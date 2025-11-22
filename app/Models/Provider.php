<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Provider extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'bio',
        'license_number',
        'is_verified',
        'is_active',
        'rating',
        'total_jobs',
        'service_areas',
        'service_types',
        'availability',
        'response_time',
        'stripe_account_id',
        'stripe_onboarding_complete',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'rating' => 'decimal:2',
        'total_jobs' => 'integer',
        'service_areas' => 'array',
        'service_types' => 'array',
        'availability' => 'array',
        'stripe_onboarding_complete' => 'boolean',
    ];

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'provider_city_service')
            ->withPivot('service_id', 'is_available', 'base_price', 'response_time')
            ->withTimestamps();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'provider_city_service')
            ->withPivot('city_id', 'is_available', 'base_price', 'response_time')
            ->withTimestamps();
    }

    public function cityServices(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'provider_city_service')
            ->withPivot('service_id', 'is_available', 'base_price', 'response_time')
            ->withTimestamps();
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function activeJobs(): HasMany
    {
        return $this->hasMany(Job::class)->whereIn('status', ['claimed', 'en_route', 'arrived', 'in_progress']);
    }

    public function availability(): HasOne
    {
        return $this->hasOne(ProviderAvailability::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(ProviderEarning::class);
    }

    public function isOnline(): bool
    {
        return $this->availability && $this->availability->is_online;
    }

    public function canAcceptJob(): bool
    {
        return $this->is_active 
            && $this->is_verified 
            && $this->availability 
            && $this->availability->canAcceptJob();
    }
}

