<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRouteLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'order_id' => 'integer',
        'delivery_man_id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'accuracy' => 'float',
        'heading' => 'float',
        'speed' => 'float',
        'metadata' => 'array',
        'recorded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }
}
