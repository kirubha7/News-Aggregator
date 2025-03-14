<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTrait;

class Source extends Model
{
    use ModelTrait;

    protected $fillable = [
        'name', 'url', 'api_url', 'api_key', 'api_secret', 'status'
    ];

    protected $casts = [
        'id' => 'string', // Ensure UUID is handled as a string
    ];
}
