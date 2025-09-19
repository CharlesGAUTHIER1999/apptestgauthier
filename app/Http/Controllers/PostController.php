<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;

class PostController extends Controller
{
    // GET /posts → Display a paginated list of posts
    public function index(): View {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    // GET /posts/create → Show the form to create a new post
    public function create(): View {
        return view('posts.create');
    }

    // POST /posts → Store a new post after validation
    public function store(StorePostRequest $request): RedirectResponse {
        Post::create($request->validated());
        // Redirect back to posts index with a success message
        return redirect()->route('posts.index')->with('status', 'Post created');
    }

    // GET /posts/{post} → Display a single post
    public function show(Post $post): View {
        return view('posts.show', compact('post'));
    }

    // GET /posts/{post}/edit → Show the form to edit an existing post
    public function edit(Post $post): View {
        return view('posts.edit', compact('post'));
    }

    // PUT/PATCH /posts/{post} → Update an existing post after validation
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse {
        $post->update($request->validated());
        // Redirect back to posts index with a success message
        return redirect()->route('posts.index')->with('status', 'Post updated');
    }

    // DELETE /posts/{post} → Delete a post
    public function destroy(Post $post): RedirectResponse {
        $post->delete();
        // Redirect back to posts index with a success message
        return redirect()->route('posts.index')->with('status', 'Post deleted');
    }
}
