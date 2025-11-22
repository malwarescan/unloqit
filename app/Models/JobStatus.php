<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobStatus extends Model
{
    protected $fillable = [
        'job_id',
        'status',
        'notes',
        'updated_by_provider_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function updatedByProvider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'updated_by_provider_id');
    }
}

