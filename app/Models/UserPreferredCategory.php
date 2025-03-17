<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class UserPreferredCategory extends Model
{
    use ModelTrait;

    protected $table = 'user_preferred_categories';
    public $timestamps = false;
    protected $fillable = ['user_id', 'category_id'];
}
