<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreVisitLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'visit_date' => 'date',
        'visit_count' => 'integer',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
