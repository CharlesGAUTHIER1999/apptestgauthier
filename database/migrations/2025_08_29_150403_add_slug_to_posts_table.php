<?php

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        Post::whereNull('slug')->orderBy('id')->each(function (Post $post) {
            $base = Str::slug($post->title) ?: 'post-' . $post->id;
            $slug = $base;
            $i = 1;
            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $post->slug = $slug;
            $post->save();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropUnique(['slug']);
        });
    }
};
