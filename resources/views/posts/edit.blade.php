@extends('layouts.app')

{{-- Set the page title dynamically with the post title --}}
@section('title', 'Éditer : '.$post->title)

@section('content')
    <h1>Éditer</h1>

    {{-- If there are validation errors, show a warning message --}}
    @if ($errors->any())
        <div style="background:#fff3cd;border:1px solid #ffeeba;padding:.75rem 1rem;margin:1rem 0;">
            Merci de corriger les erreurs ci-dessous.
        </div>
    @endif

    {{-- Form to update an existing post --}}
    <form method="POST" action="{{ route('posts.update', $post) }}">
        {{-- CSRF protection + specify HTTP method PUT --}}
        @csrf @method('PUT')

        {{-- Title field --}}
        <p>
            <label for="title">Titre</label><br>
            <input id="title" type="text" name="title" value="{{ old('title', $post->title) }}" required
                   @error('title') aria-invalid="true" @enderror>
            {{-- Show validation error for "title" --}}
            @error('title') <br><small style="color:red">{{ $message }}</small> @enderror
        </p>

        {{-- Body (content) field --}}
        <p>
            <label for="body">Contenu</label><br>
            <textarea name="body" rows="6">{{ old('body', $post->body) }}</textarea>
            {{-- Show validation error for "body" --}}
            @error('body') <br><small style="color:red">{{ $message }}</small> @enderror
        </p>

        {{-- Hidden input ensures "published" is always sent (default = 0) --}}
        <input type="hidden" name="published" value="0">

        {{-- Checkbox for "published" status --}}
        <p>
            <label>
                <input type="checkbox" name="published" value="1" @checked(old('published', $post->published))>
                Publié
            </label>
            {{-- Show validation error for "published" --}}
            @error('published') <br><small style="color:red">{{ $message }}</small> @enderror
        </p>

        {{-- Submit and cancel buttons --}}
        <button type="submit">Mettre à jour</button>
        <a href="{{ route('posts.index') }}">Annuler</a>
    </form>
@endsection
