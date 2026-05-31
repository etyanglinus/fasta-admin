<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZonePaymentGateway extends Model
{
    protected $fillable = ['zone_id', 'gateway_key', 'status'];

    protected $casts = [
        'zone_id' => 'integer',
        'status' => 'boolean',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
