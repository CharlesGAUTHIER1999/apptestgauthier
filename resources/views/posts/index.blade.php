@extends('layouts.app')

{{-- Set the page title --}}
@section('title', 'Posts')

@section('content')
    <h1>Posts</h1>

    {{-- Display success message if there is a session "status" --}}
    @if (session('status'))
        <div style="background:#e6ffed;border:1px solid #b7f5c8;padding:.75rem 1rem;margin:1rem 0;">
            {{ session('status') }}
        </div>
    @endif

    {{-- Link to create a new post --}}
    <p><a href="{{ route('posts.create') }}">+ Nouveau post</a></p>

    <ul>
        {{-- Loop through posts (if there are any) --}}
        @forelse ($posts as $post)
            <li>
                {{-- Link to view the post --}}
                <a href="{{ route('posts.show', $post) }}">
                    {{ $post->title }}
                </a>

                {{-- Show whether the post is published or a draft --}}
                @if($post->published)
                    <small> — Publié ✅</small>
                @else
                    <small> — Brouillon ⏳</small>
                @endif

                {{-- Link to edit the post --}}
                <small> · <a href="{{ route('posts.edit', $post) }}">Edit</a></small>

                {{-- Delete form (inline) with confirmation dialog --}}
                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer ce post ?')">
                    @csrf @method('DELETE')
                    <button type="submit">supprimer</button>
                </form>
            </li>
        @empty
            {{-- If no posts exist, show fallback message --}}
            <li>Aucun post.</li>
        @endforelse
    </ul>

    {{-- Pagination links --}}
    {{ $posts->links() }}
@endsection
