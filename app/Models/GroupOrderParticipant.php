<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupOrderParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_order_id',
        'user_id',
        'guest_id',
        'name',
        'is_host',
    ];

    protected $casts = [
        'group_order_id' => 'integer',
        'user_id' => 'integer',
        'guest_id' => 'integer',
        'is_host' => 'boolean',
    ];

    public function groupOrder(): BelongsTo
    {
        return $this->belongsTo(GroupOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
