<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'created_at'
    ];

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/'.$this->avatar) : null;
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
