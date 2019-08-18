<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index_users(Request $request) {
        $this->authorize('index_users', [User::class]);
        $search = $request->has('search') ? $request->search : "";

        $users = User::where('id', '!=', Auth::id())
            ->where('name', 'like', '%'.$search.'%')
            ->paginate(5);

        $followingsIds = User::whereHas('followers', function($query) {
            $query->where('follower_id', Auth::id());
        })->get(['id']);

        $response = ['users' => $users, 'followings_ids' => $followingsIds];
        return response($response, 200);
    }

    public function retrieve_user(User $user) {
        $this->authorize('retrieve_user', [User::class]);
        $user = User::where('id', $user->id)
                    ->withCount(['posts', 'followers', 'followings'])
                    ->first();
        return response($user, 200);
    }

    public function followUser(Request $request, User $user) {
        $this->authorize('followUser', [User::class, $user]);
        $user->followers()->attach(Auth::id());
        return response("User followed", 200);
    }

    public function unFollowUser(User $user) {
        $this->authorize('unFollowUser', [User::class, $user]);
        $user->followers()->detach(Auth::id());
        return response("User unfollowed", 200);
    }

    public function retrieve_followers(User $user) {
        $this->authorize('retrieve_followers', [User::class]);

        $followingsIds = User::whereHas('followers', function($query) {
            $query->where('follower_id', Auth::id());
        })->get(['id']);

        $followers = $user->followers()->paginate(5);
        $response = ['users' => $followers, 'followings_ids' => $followingsIds];
        return response($response, 200);
    }

    public function retrieve_followings(User $user) {
        $this->authorize('retrieve_followings', [User::class]);

        $followingsIds = User::whereHas('followers', function($query) {
            $query->where('follower_id', Auth::id());
        })->get(['id']);

        $followings = $user->followings()->paginate(5);
        $response = ['users' => $followings, 'followings_ids' => $followingsIds];

        return response($response, 200);
    }

}
