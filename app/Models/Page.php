<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'page_type',
        'short_description',
        'content',
        'meta_title',
        'meta_description',
        'featured_image',
        'banner_image',
        'status',
    ];
}
