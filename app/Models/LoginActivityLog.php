<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivityLog extends Model
{
    protected $fillable = [
        'user_type',
        'user_id',
        'name',
        'email',
        'ip_address',
        'device_type',
        'os',
        'browser',
        'user_agent',
        'logged_in_at',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
    ];
}
