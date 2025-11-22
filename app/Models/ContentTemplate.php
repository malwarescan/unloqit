<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTemplate extends Model
{
    protected $fillable = [
        'type',
        'name',
        'template',
        'variables',
        'meta_description_template',
        'title_template',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrderedByPriority($query)
    {
        return $query->orderBy('priority', 'desc')->orderBy('id', 'asc');
    }
}

