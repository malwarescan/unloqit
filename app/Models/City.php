<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class City extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'state',
        'lat',
        'lng',
    ];

    protected $casts = [
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
    ];

    public function neighborhoods(): HasMany
    {
        return $this->hasMany(Neighborhood::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'city_service_pages')
            ->withPivot('custom_intro', 'custom_pricing')
            ->withTimestamps();
    }

    public function cityServicePages(): HasMany
    {
        return $this->hasMany(CityServicePage::class);
    }
}

