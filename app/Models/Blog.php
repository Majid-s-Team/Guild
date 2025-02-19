<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'slug',
        'user_id',
        'content',
        'meta_description',
        'meta_title',
        'meta_keywords',
        'is_active',
        'page_excerpt',
        'image',
        'status',
        'schedule_date'
    ];

}
