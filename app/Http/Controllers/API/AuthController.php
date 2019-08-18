<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        $profile = User::where('id', Auth::id())->withCount([
            'posts',
            'followers',
            'followings'
        ])->first();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => $profile
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function retrieveCurrentUser() {
        $user = User::where('id', Auth::id())
            ->withCount(['posts', 'followers', 'followings'])
            ->first();
        return $user;
    }

    public function update_profile(Request $request) {
        $validated = $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "about" => "required|string"
        ]);

        if ($request->has('profile_image')) {
            $imageName = Auth::user()->profile_image;
            $image_path = "uploads/posts/" . $imageName;
            if (File::exists($image_path)) {
                File::delete($image_path);
            }

            $image = $request->file('profile_image');
            $profile_image_file_name = $image->hashName();
            $image->move('uploads/profiles', $profile_image_file_name);
            $validated["profile_image"] = $profile_image_file_name;
        }

        Auth::user()->update($validated);
        return response(Auth::user(), 200);
    }

    public function delete_profile_image() {
        $imageName = Auth::user()->profile_image;
        $image_path = "uploads/profiles/" . $imageName;
        if (File::exists($image_path)) {
            File::delete($image_path);
        }

        Auth::user()->update(['profile_image' => null]);
        return response("profile image deleted", 200);
    }

}
