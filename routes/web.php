<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/hello', static fn () => 'hello laravel')->name('hello');
Route::get('/hello-view', static fn () => view('home', ['title' => 'Hello view']))->name('hello.view');
Route::resource('posts', PostController::class)->only(['index', 'show']);
Route::middleware('admin')->group(function() {
    Route::resource('posts', PostController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
});
