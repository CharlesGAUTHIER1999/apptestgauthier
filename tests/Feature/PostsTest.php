<?php

namespace Tests\Feature;

use Tests\TestCase;

class PostsTest extends TestCase
{
    public function test_shows_posts_list(): void
    {
        $this->get('/posts')
            ->assertOk()
            ->assertSee('Publié')
            ->assertSee('Brouillon');
    }

    public function test_shows_a_post(): void
    {
        $this->get('/posts/hello-laravel')
            ->assertOk()
            ->assertSee('Hello Laravel');
    }

    public function test_rejects_invalid_slug(): void
    {
        $this->get('/posts/hello-laravel!')
            ->assertNotFound();
    }

    public function test_redirects_legacy_articles(): void
    {
        $this->get('/articles')
            ->assertRedirect('/posts');
    }

    public function test_named_routes_build_expected_urls(): void
    {
        $this->assertSame(
            url('/posts/hello-laravel'),
            route('posts.show', ['slug' => 'hello-laravel'])
        );
    }
}
