<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class UserPreference extends Model
{
    use ModelTrait;

    protected $fillable = ['user_id'];

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'user_preferred_authors', 'user_id', 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'user_preferred_categories', 'user_id', 'category_id');
    }

    public function sources()
    {
        return $this->belongsToMany(Source::class, 'user_preferred_sources', 'user_id', 'source_id');
    }
}
