@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <a href="{{ route('posts.index') }}">&larr; Retour</a>
    <h1>{{ $post->title }}</h1>

    @if($post->published)
        <p><em>Publié ✅</em></p>
    @else
        <p><em>Brouillon ⏳</em></p>
    @endif

    <article>
        {!! nl2br(e($post->body)) !!}
    </article>
@endsection
