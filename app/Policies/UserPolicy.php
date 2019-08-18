<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function __construct() {
        //
    }

    public function index_users(User $user) {
        return true;
    }

    public function retrieve_user(User $user) {
        return true;
    }

    public function followUser(User $user, User $userToBeFollowed) {
        if (!$user) {
            return false;
        }
        if ($user->id == $userToBeFollowed->id) {
            return false;
        }
        return true;
    }

    public function unFollowUser(User $user, User $userToBeUnfollowed) {
        if (!$user) {
            return false;
        }
        if ($user->id == $userToBeUnfollowed->id) {
            return false;
        }
        return true;
    }

    public function retrieve_followers(User $user) {
         return true;
    }

    public function retrieve_followings(User $user) {
        return true;
    }


}
