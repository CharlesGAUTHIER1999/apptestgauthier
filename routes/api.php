<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

Route::prefix('v1')->group(function () {
    Route::apiResource('posts', PostController::class)
    ->middleware('throttle:60,1');
});
Route::get('/ping', fn() => ['pong' => true]);
