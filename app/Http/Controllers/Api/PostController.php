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
    public function index(): LengthAwarePaginator {
        return Post::latest()->paginate(10);
    }

    public function show(Post $post): JsonResponse {
        return response()->json($post);
    }

    public function store(StorePostRequest $r): JsonResponse{
        $post = Post::create($r->validated());
        return response()->json($post, 201);
    }

    public function update(UpdatePostRequest $r, Post $post): JsonResponse {
        $post->update($r->validated());
        return response()->json($post);
    }

    public function destroy(Post $post): Response {
        $post->delete();
        return response()->noContent();
    }
}
