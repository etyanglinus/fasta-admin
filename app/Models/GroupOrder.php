<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'host_user_id',
        'host_guest_id',
        'store_id',
        'module_id',
        'status',
        'payment_mode',
        'expires_at',
        'placed_at',
    ];

    protected $casts = [
        'host_user_id' => 'integer',
        'host_guest_id' => 'integer',
        'store_id' => 'integer',
        'module_id' => 'integer',
        'expires_at' => 'datetime',
        'placed_at' => 'datetime',
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo(User::class, 'host_user_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(GroupOrderParticipant::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isHost(?User $user, mixed $guestId = null): bool
    {
        return ($user && $this->host_user_id === $user->id) || (!$user && $guestId && (string) $this->host_guest_id === (string) $guestId);
    }
}
