<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getLevelAttribute()
    {
        $level = Level::where('experience', '<=', $this->exp)->orderBy('experience', 'desc')->first();
        if ($level) {
            return $level;
        }

        return (object) [
            'level' => "Cấp 0",
            'image' => 'default_image_url'
        ];
    }

    public function getNextLevelAttribute()
    {
        $level = Level::where('experience', '>', $this->exp)->orderBy('experience', 'asc')->first();
        if ($level) {
            return $level;
        }

        return null;
    }

    public function follows()
    {
        return $this->hasMany(Follow::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'user_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'user_id', 'id');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'user_id', 'id');
    }
}
