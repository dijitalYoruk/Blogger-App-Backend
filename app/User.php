<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Models\Tag;
use App\Models\Post;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    protected $fillable = [
        'name', 'email', 'about', 'profile_image', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tags() {
        return $this->hasMany(Tag::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function followers() {
        return $this->belongsToMany(User::class, 'followers', 'leader_id', 'follower_id');
    }

    public function followings() {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'leader_id');
    }

}
