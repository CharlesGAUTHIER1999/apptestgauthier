@extends('layouts.app')

{{-- Set the page title dynamically with the post title --}}
@section('title', $post->title)

@section('content')
    {{-- Link to go back to the posts list --}}
    <a href="{{ route('posts.index') }}">&larr; Retour</a>

    {{-- Display the post title --}}
    <h1>{{ $post->title }}</h1>

    {{-- Show published or draft status --}}
    @if($post->published)
        <p><em>Publié ✅</em></p>
    @else
        <p><em>Brouillon ⏳</em></p>
    @endif

    {{-- Display the post body with line breaks preserved --}}
    <article>
        {!! nl2br(e($post->body)) !!}
    </article>
@endsection
