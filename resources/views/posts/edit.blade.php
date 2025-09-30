@extends('layouts.app')

{{-- Set the page title dynamically with the post title --}}
@section('title', 'Éditer : '.$post->title)

@push('styles')
    @vite('resources/css/posts-edit.css')
@endpush

@section('content')
    <h1 class="page-title">Éditer</h1>

    {{-- If there are validation errors, show a warning message --}}
    @if ($errors->any())
        <div class="alert-warning">
            Merci de corriger les erreurs ci-dessous.
        </div>
    @endif

    {{-- Form to update an existing post --}}
    <form method="POST" action="{{ route('posts.update', $post) }}">
        {{-- CSRF protection + specify HTTP method PUT --}}
        @csrf @method('PUT')

        {{-- Title field --}}
        <div>
            <label for="title">Titre</label>
            <input id="title" type="text" name="title" value="{{ old('title', $post->title) }}" required
                   @error('title') aria-invalid="true" @enderror>
            @error('title') <span class="error">{{ $message }}</span> @enderror
        </div>

        {{-- Body (content) field --}}
        <div>
            <label for="body">Contenu</label>
            <textarea id="body" name="body" rows="6">{{ old('body', $post->body) }}</textarea>
            @error('body') <span class="error">{{ $message }}</span> @enderror
        </div>

        {{-- Hidden input ensures "published" is always sent (default = 0) --}}
        <input type="hidden" name="published" value="0">

        {{-- Checkbox for "published" status --}}
        <div>
            <label>
                <input type="checkbox" name="published" value="1" @checked(old('published', $post->published))>
                Publié
            </label>
            @error('published') <span class="error">{{ $message }}</span> @enderror
        </div>

        {{-- Submit and cancel buttons --}}
        <div>
            <button type="submit">Mettre à jour</button>
            <a href="{{ route('posts.index') }}" class="cancel-link">Annuler</a>
        </div>
    </form>
@endsection
