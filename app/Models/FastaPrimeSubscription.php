<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastaPrimeSubscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'plan_snapshot' => 'array',
        'paid_amount' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
        'is_canceled' => 'boolean',
        'canceled_at' => 'datetime',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1)
            ->where('payment_status', 'paid')
            ->where('is_canceled', 0)
            ->where('end_date', '>=', now());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(FastaPrimePlan::class, 'plan_id');
    }
}
