<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperPost
 */
class Post extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned (fillable)
    protected $fillable = ['title', 'body', 'published', 'slug'];

    // Automatically cast "published" to a boolean when retrieved or saved
    protected $casts = ['published' => 'boolean'];

    // Booted method to handle model events (creating, updating, etc.)
    protected static function booted(): void
    {
        // Before creating a post, if no slug is provided → generate a unique one from the title
        static::creating(static function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = static::uniqueSlug($post->title);
            }
        });

        // Before updating a post, if the title changes → regenerate a unique slug
        static::updating(static function (Post $post) {
            if ($post->isDirty('title')) {
                $post->slug = static::uniqueSlug($post->title, $post->id);
            }
        });
    }

    // Generate a unique slug from a given title
    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        // Convert the title into a slug (fallback = "post" if empty)
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $i = 1;

        // Query to check if the slug already exists
        $query = static::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId); // ignore the current post when updating
        }

        // If the slug exists, keep appending "-1", "-2", etc. until it's unique
        while ($query->exists()) {
            $slug = $base . '-' . $i++;
            $query = static::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

    // Use "slug" instead of "id" for route model binding (e.g., /posts/my-post-slug)
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
