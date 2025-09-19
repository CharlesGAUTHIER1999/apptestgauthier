<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

// Group routes under the prefix /api/v1
Route::prefix('v1')->group(function () {
    // Define a REST API resource for posts
    // This automatically generates: index, show, store, update, destroy
    // Apply rate limiting middleware: max 60 requests per minute per IP
    Route::apiResource('posts', PostController::class)
        ->middleware('throttle:60,1');
});

// Simple health check endpoint: GET /api/ping returns {"pong": true}
Route::get('/ping', static fn() => ['pong' => true]);
