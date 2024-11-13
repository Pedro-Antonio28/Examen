<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    public function index()
    {
        $posts = Post::where('published_at', '<=', now())
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create', ['post' => new Post()]);
    }

    public function store(StorePostRequest $request)
    {
        $slug = str::slug($request->title);

        $post = auth()->user()->posts()->create([
            'title' => $request->title,
            'body' => $request->body,
            'summary' => $request->summary,
            'slug' => $slug,
            'status' => $request->status,
            'reading_time' => $request->reading_time,
            'published_at' => $request->status == 'published'? now() : null,
        ]);

        return to_route('posts.index')
            ->with('status', 'Post created successfully');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        if ($post->status == 'published' || $post->status == 'archived') {
            return redirect()->route('posts.index')
                ->with('error', 'You cannot edit this post because it is published or archived.');
        }
        $publishedAt = $request->status === 'published' ? now() : null;


        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'summary' => $request->summary,
            'slug' => Str::slug($request->title),
            'status' => $request->status,
            'reading_time' => $request->reading_time,
            'published_at' => $publishedAt,
        ]);
    }

    public function destroy(Post $post)
    {
        if ($post->status != 'draft' && $post->status != 'pending') {
            return redirect()->route('posts.index')
                ->with('error', 'You can only delete posts that are in draft or pending status.');
        }
        $post->delete();

        return to_route('posts.index')
            ->with('status', 'Post deleted successfully');
    }

    public function userPosts()
    {
        $posts = auth()->user()->posts;

        return view('posts.index', compact('posts'));
    }
}
