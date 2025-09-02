@extends('layouts.app')

@section('title', 'Posts')

@section('content')
    <h1>Posts</h1>

    @if (session('status'))
        <div style="background:#e6ffed;border:1px solid #b7f5c8;padding:.75rem 1rem;margin:1rem 0;">
            {{ session('status') }}
        </div>
    @endif

    <p><a href="{{ route('posts.create') }}">+ Nouveau post</a></p>

    <ul>
        @forelse ($posts as $post)
            <li>
                <a href="{{ route('posts.show', $post) }}">
                    {{ $post->title }}
                </a>
                @if($post->published)
                    <small> — Publié ✅</small>
                @else
                    <small> — Brouillon ⏳</small>
                @endif
                <small> · <a href="{{ route('posts.edit', $post) }}">Edit</a></small>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline" onsubmit="return confirm('Supprimer ce post ?')">
                    @csrf @method('DELETE')
                    <button type="submit">supprimer</button>
                </form>
            </li>
        @empty
            <li>Aucun post.</li>
        @endforelse
    </ul>

    {{ $posts->links() }}
@endsection
