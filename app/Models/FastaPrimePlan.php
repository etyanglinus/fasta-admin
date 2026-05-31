<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastaPrimePlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'float',
        'validity_days' => 'integer',
        'free_delivery' => 'boolean',
        'free_delivery_limit' => 'float',
        'max_free_deliveries' => 'integer',
        'features' => 'array',
        'status' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(FastaPrimeSubscription::class, 'plan_id');
    }

    public function activeSubscriptions()
    {
        return $this->subscriptions()->active();
    }
}
