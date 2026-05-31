<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'department',
        'location',
        'employment_type',
        'salary_range',
        'description',
        'requirements',
        'responsibilities',
        'status',
        'closing_date',
    ];

    protected $dates = ['closing_date'];

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
