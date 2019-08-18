<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\PostImage;
use App\User;

class PostController extends Controller
{
    public function create_post(Request $request) {
        $this->authorize('create_post', [Post::class]);
        $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'content'     => 'required',
        ]);

        $post = Post::create([
            'title'       => $request->title,
            'description' => $request->description,
            'content'     => $request->content,
            'user_id'     => Auth::id()
        ]);

        $images = $request->file('post_images');

        if ($images) {
            foreach ($images as $image) {
                $prost_image_file_name = $image->hashName();
                $postImage = PostImage::create([
                    'image_name' => $prost_image_file_name,
                    'post_id' => $post->id
                ]);
                $image->move('uploads/posts', $postImage->image_name);
            }
        }

        return response($post, 201);
    }

    public function index_post(Request $request) {
        $this->authorize('index_post', [Post::class]);
        $search = $request->has('search') ? $request->search : "";

        $followingsIds = User::whereHas('followers', function($query) {
            $query->where('follower_id', Auth::id());
        })->get(['id']);

        $followingsIds->push(Auth::id());

        $posts = Post::with('owner', 'post_images')
                    ->where('title', 'like', '%'.$search.'%')
                    ->withCount('post_images')
                    ->orderBy('created_at','desc')
                    ->whereIn('user_id', $followingsIds)
                    ->paginate(5);
        return response($posts, 200);
    }

    public function auth_posts(Request $request) {
        $this->authorize('auth_posts', [Post::class]);
        $search = $request->has('search') ? $request->search : "";
        $posts = Post::where('user_id', Auth::id())
                    ->where('title', 'like', '%'.$search.'%')
                    ->with('owner', 'post_images')
                    ->withCount('post_images')
                    ->orderBy('created_at','desc')
                    ->paginate(5);
        return response($posts, 200);
    }

    public function delete_post(Post $post) {
        $this->authorize('delete_post', [Post::class, $post]);
        foreach($post->post_images as $postImage) {
            $postImage->deleteImage();
            $postImage->delete();
        }
        $post->delete();
        return response()->json('Post is deleted.', 200);
    }

    public function update_post(Request $request, Post $post) {
        $this->authorize('update_post', [Post::class, $post]);

        $validated = $request->validate([
            'title'       => 'required|string',
            'description' => 'required|string',
            'content'     => 'required|string'
        ]);

        $post->update($validated);
        $images = $request->file('post_images');

        if ($images) {
            foreach ($images as $image) {
                $prost_image_file_name = $image->hashName();
                $postImage = PostImage::create([
                    'image_name' => $prost_image_file_name,
                    'post_id' => $post->id
                ]);
                $image->move('uploads/posts', $postImage->image_name);
            }
        }

        return response($post, 200);
    }

    public function retieve_post(Post $post) {
        $this->authorize('retieve_post', [Post::class]);
        $post = Post::where('id', $post->id)
                    ->with('owner', 'post_images')
                    ->withCount('post_images')
                    ->first();
        return response($post, 200);
    }

    public function delete_post_image(PostImage $postImage) {
        $this->authorize('delete_post_image', [Post::class, $postImage]);
        $postImage->deleteImage();
        $postImage->delete();
        return response("Post Image deleted", 200);
    }

    public function retrieve_user_posts(Request $request, User $user) {
        $this->authorize('retrieve_user_posts', [Post::class]);
        $search = $request->has('search') ? $request->search : "";

        $posts = Post::where('user_id', $user->id)
                    ->where('title', 'like', '%'.$search.'%')
                    ->with('owner', 'post_images')
                    ->withCount('post_images')
                    ->orderBy('created_at','desc')
                    ->paginate(5);
        return response($posts, 200);
    }

}
