@extends('layouts.app')

@section('title', 'Posts')

@push('styles')
    @vite('resources/css/posts.css')
@endpush

@section('content')

    <h1 class="page-title">Posts</h1>

    {{-- Message de succès --}}
    @if (session('status'))
        <div class="alert-success">
            {{ session('status') }}
        </div>
    @endif

    <p><a href="{{ route('posts.create') }}" class="btn-new">+ Nouveau post</a></p>

    <ul class="posts-list">
        @forelse ($posts as $post)
            <li>
                <a href="{{ route('posts.show', $post) }}" class="post-title">
                    {{ $post->title }}
                </a>

                @if($post->published)
                    <span class="status published">Publié ✅</span>
                @else
                    <span class="status draft">Brouillon ⏳</span>
                @endif

                <a href="{{ route('posts.edit', $post) }}" class="btn-edit">Edit</a>

                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline-form"
                      onsubmit="return confirm('Supprimer ce post ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete">Supprimer</button>
                </form>
            </li>
        @empty
            <li>Aucun post.</li>
        @endforelse
    </ul>

    <div class="pagination">
        {{ $posts->links() }}
    </div>
@endsection

