<?php

namespace App\Policies;

use App\Models\Post;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function create_post(User $user) {
        return true;
    }

    public function index_post(User $user) {
        return true;
    }

    public function auth_posts() {
        return true;
    }

    public function delete_post(User $user, Post $post) {
        if ($user->id == $post->owner->id) {
            return true;
        }
        return false;
    }

    public function update_post(User $user, Post $post) {
        if ($user->id == $post->owner->id) {
            return true;
        }
        return false;
    }

    public function retieve_post(User $user) {
        return true;
    }

    public function delete_post_image(User $user, PostImage $postImage) {
        if ($user->id == $postImage->post->user_id) {
            return true;
        }
        return false;
    }

    public function retrieve_user_posts(User $user) {
        return true;
    }

}
