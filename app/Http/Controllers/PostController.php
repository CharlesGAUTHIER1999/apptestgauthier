<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class PostController extends Controller
{
    public function index(): View {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create(): View {
        return view('posts.create');
    }

    public function store(StorePostRequest $request): RedirectResponse {
        Post::create($request->validated());
        return redirect()->route('posts.index')->with('status', 'Post created');
    }

    public function show(Post $post): View{
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post): View{
        return view('posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse {
        $post->update($request->validated());
        return redirect()->route('posts.index')->with('status', 'Post updated');
    }

    public function destroy(Post $post): RedirectResponse{
        $post->delete();
        return redirect()->route('posts.index')->with('status', 'Post deleted');
    }
}
