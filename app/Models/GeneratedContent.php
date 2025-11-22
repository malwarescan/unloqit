<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedContent extends Model
{
    protected $fillable = [
        'content_type',
        'city_id',
        'service_id',
        'neighborhood_id',
        'template_id',
        'content',
        'meta_description',
        'title',
        'is_auto_generated',
        'is_published',
        'generated_at',
    ];

    protected $casts = [
        'is_auto_generated' => 'boolean',
        'is_published' => 'boolean',
        'generated_at' => 'datetime',
    ];

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

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContentTemplate::class, 'template_id');
    }
}

