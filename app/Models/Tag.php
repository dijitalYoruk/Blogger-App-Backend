<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Tag extends Model
{
    protected $fillable = ["name", "user_id"];
    protected $table = "tags";

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function posts() {
        return $this->belongsToMany(Post::class, 'post_tags', "tag_id", "post_id");
    }

}
