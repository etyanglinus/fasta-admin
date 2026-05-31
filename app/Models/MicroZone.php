<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class MicroZone extends Model
{
    use HasSpatial;

    protected $fillable = ['zone_id', 'name', 'description', 'coordinates', 'status'];

    protected $casts = [
        'zone_id' => 'integer',
        'coordinates' => Polygon::class,
        'status' => 'boolean',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function deliverymen(): HasMany
    {
        return $this->hasMany(DeliveryMan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
