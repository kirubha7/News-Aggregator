<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserPreference extends Model
{
    use ModelTrait,HasUuids;

    protected $fillable = ['user_id'];

    public function authors()
    {
        return $this->hasMany(UserPreferredAuthor::class, 'user_id', 'id');
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
