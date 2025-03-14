<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{ModelTrait};

class Category extends Model
{
    use ModelTrait;
    protected $fillable = ['name', 'slug', 'status'];

    protected $casts = [
        'id' => 'string', // Ensure UUID is handled as a string
    ];
}
