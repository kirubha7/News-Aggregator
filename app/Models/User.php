<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\ModelTrait;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens,ModelTrait;

    public $incrementing = false; // Disable auto-increment
    protected $keyType = 'string'; // UUIDs are stored as strings

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function preferredAuthors()
    {
        return $this->hasMany(UserPreferredAuthor::class, 'user_id', 'id');
    }

    public function preferredCategories()
    {
        return $this->belongsToMany(Category::class, 'user_preferred_categories', 'user_id', 'category_id')
                    ->withPivot(['user_id', 'category_id']) ;
    }

    public function preferredSources()
    {
        return $this->belongsToMany(Source::class, 'user_preferred_sources', 'user_id', 'source_id')
                    ->withPivot(['user_id', 'source_id']);
    }

}
