<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class PostImage extends Model
{
    protected $fillable = ["post_id", "image_name"];
    public $timestamps = false;
    protected $table = "post_images";

    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function deleteImage() {
        $image_path = "uploads/posts/" . $this->image_name;
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
    }
}
