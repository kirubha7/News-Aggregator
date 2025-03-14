<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Article extends Model
{
    use ModelTrait;

    protected $fillable = [
        'id',
        'source_id',
        'category_id',
        'title',
        'description',
        'url',
        'published_at',
        'content',
        'author',
        'status',
        'view_count',
        'url',
        'image_url'
    ];

}
