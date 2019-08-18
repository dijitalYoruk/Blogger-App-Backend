<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller {

    public function create_tag(Request $request) {
        $request->validate(['name' => 'required|string']);

        $tag = Tag::create([
            'name' => $request->name,
            'user_id' => Auth::id()
        ]);

        return response($tag, 201);
    }

    public function index_tag() {
        $tags = Tag::paginate(10);
        return response($tags, 200);
    }

    public function user_tags() {
        $tags = Tag::where('user_id', Auth::id())->paginate(10);
        return response($tags, 200);
    }

    public function delete_tag(Tag $tag) {
        $tag->delete();
        return response()->json('Tag is deleted.', 200);
    }

    public function update_tag(Request $request, Tag $tag) {
        $validated = $request->validate(['name' => 'required|string']);
        $tag->update($validated);
        return response($tag, 200);
    }

}
