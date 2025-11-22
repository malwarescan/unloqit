<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProviderEarning extends Model
{
    protected $fillable = [
        'provider_id',
        'job_id',
        'amount',
        'platform_fee',
        'payout_amount',
        'status',
        'stripe_transfer_id',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'payout_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}

