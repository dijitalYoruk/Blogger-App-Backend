<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Post extends Model
{
    protected $fillable = ["title", "description", "content", "user_id"];
    protected $table = "posts";

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post_images() {
        return $this->hasMany(PostImage::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'post_tags', "post_id", "tag_id");
    }
}
