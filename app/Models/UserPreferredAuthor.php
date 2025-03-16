<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class UserPreferredAuthor extends Model
{
    use ModelTrait;

    protected $table = 'user_preferred_authors';
    public $timestamps = false;
    protected $fillable = ['user_id', 'author'];
}
