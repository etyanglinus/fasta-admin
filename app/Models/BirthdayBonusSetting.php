<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BirthdayBonusSetting extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'boolean',
        'bonus_amount' => 'float',
        'minimum_order_value' => 'float',
        'validity_days' => 'integer',
        'module_id' => 'integer',
    ];
}
