<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;

// Home page route → handled by PageController@home
Route::get('/', [PageController::class, 'home'])->name('home');
// Simple route that just returns plain text "hello laravel"
Route::get('/hello', static fn () => 'hello laravel')->name('hello');
// Route that returns the "home" view with a custom title
Route::get('/hello-view', static fn () => view('home', ['title' => 'Hello view']))->name('hello.view');

// Resource routes for posts (CRUD)
// Only generate the following routes: index, show, create, store, edit, update, destroy
Route::resource('posts', PostController::class)
    ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);

// Protected road by EnsureAdmin
Route::middleware('admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
});
