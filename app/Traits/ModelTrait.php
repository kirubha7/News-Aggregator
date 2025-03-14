<?php

namespace App\Traits;
use Illuminate\Support\Str;

trait ModelTrait
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
