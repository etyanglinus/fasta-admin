<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $casts = [
        'user_id' => 'integer',
        'module_id' => 'integer',
        'group_order_id' => 'integer',
        'group_order_participant_id' => 'integer',
        'item_id' => 'integer',
        'is_guest' => 'boolean',
        'price' => 'float',
        'quantity' => 'integer',
        'add_on_ids' => 'array',
        'add_on_qtys' => 'array',
        'variation' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'module_id',
        'group_order_id',
        'group_order_participant_id',
        'participant_name',
        'item_id',
        'is_guest',
        'add_on_ids',
        'add_on_qtys',
        'item_type',
        'price',
        'quantity',
        'variation',
    ];

    public function item()
    {
        return $this->morphTo();
    }

    public function groupOrder(): BelongsTo
    {
        return $this->belongsTo(GroupOrder::class);
    }

    public function groupOrderParticipant(): BelongsTo
    {
        return $this->belongsTo(GroupOrderParticipant::class);
    }
}
