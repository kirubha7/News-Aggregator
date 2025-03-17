<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class UserPreferredSource extends Model
{
    use ModelTrait;

    protected $table = 'user_preferred_sources';
    public $timestamps = false;
    protected $fillable = ['user_id', 'source_id'];
}
