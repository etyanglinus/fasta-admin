<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_opening_id',
        'full_name',
        'email',
        'phone',
        'resume',
        'cover_letter',
        'status',
    ];

    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }
}
