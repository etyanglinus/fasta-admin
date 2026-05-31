<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'meta_title',
        'meta_description',
        'author',
        'views',
        'status',
        'published_at',
    ];

    protected $dates = ['published_at'];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
