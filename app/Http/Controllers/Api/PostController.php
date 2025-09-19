<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    // GET /api/posts → return a paginated list of posts (10 per page)
    public function index(): LengthAwarePaginator {
        return Post::latest()->paginate(10);
    }

    // GET /api/posts/{post} → return a single post as JSON
    public function show(Post $post): JsonResponse {
        return response()->json($post);
    }

    // POST /api/posts → create a new post from validated request data
    public function store(StorePostRequest $r): JsonResponse {
        $post = Post::create($r->validated());
        // Return the created post with HTTP status 201 (Created)
        return response()->json($post, 201);
    }

    // PUT/PATCH /api/posts/{post} → update an existing post with validated request data
    public function update(UpdatePostRequest $r, Post $post): JsonResponse {
        $post->update($r->validated());
        return response()->json($post);
    }

    // DELETE /api/posts/{post} → delete a post
    public function destroy(Post $post): Response {
        $post->delete();
        // Return HTTP 204 (No Content) to indicate successful deletion
        return response()->noContent();
    }
}
