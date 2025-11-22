<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_service_pages')
            ->withPivot('custom_intro', 'custom_pricing')
            ->withTimestamps();
    }

    public function cityServicePages(): HasMany
    {
        return $this->hasMany(CityServicePage::class);
    }
}

