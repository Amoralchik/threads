<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Events\PostCreated;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        auth()->loginUsingId(1);
        $posts = Post::with('descendants')->orderBy('created_at', 'desc')->whereNull('parent_post_id')->get();
        return view("posts.index", compact("posts"));
    }

    public function create(Request $request)
    {
        $newPost = Post::create([
            'content' => $request->input('content'),
            'author' => $request->input('author') ?? 'Anonymous',
            'author_email' => $request->input('author_email'),
            'attached_file' => $request->input('attached_file'),
            'parent_post_id' => $request->input('parent_post_id'),
        ]);

        broadcast(new PostCreated($newPost));
        return $newPost;
    }

    public function update(Request $request,  $id)
    {
        $request->validate([
            'author' => 'nullable|string',
            'email' => 'required|email',
            'content' => 'required|string',
        ]);

        $post = Post::findOrFail($id);

        $post->update([
            'content' => $request->input('content'),
            'author' => $request->input('author') ?? 'Anonymous',
            'attached_file' => $request->input('attached_file'),
        ]);

        return $post;
    }

    public function delete(Request $request,  $id)
    {
        $post = Post::findOrFail($id);

        $post->delete();

        return response('Post deleted successfully', 200);
    }
}
