<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'stripe_customer_id',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }
}

