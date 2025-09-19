@extends('layouts.app')

{{-- Set the page title --}}
@section('title', 'Créer un post')

@section('content')
    <h1>Nouveau post</h1>

    {{-- If there are any validation errors, show a warning message --}}
    @if($errors->any())
        <div style="background:#fff3cd;border:1px solid #ffeeba;padding:.75rem 1rem;margin:1rem 0;">
            Merci de corriger les erreurs ci-dessous {{-- (French: "Please correct the errors below") --}}
        </div>
    @endif

    {{-- Form to create a new post --}}
    <form method="POST" action="{{ route('posts.store') }}">
        {{-- CSRF protection token --}}
        @csrf

        {{-- Title field --}}
        <p>
            <label for="title">Titre</label><br>
            <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus
                   @error('title') aria-invalid="true"@enderror>
            {{-- Display error message if validation fails for "title" --}}
            @error('title') <br><small style="color:red">{{ $message }}</small> @enderror
        </p>

        {{-- Body (content) field --}}
        <p>
            <label for="body">Contenu</label><br>
            <textarea id="body" name="body" rows="6">{{ old('body') }}</textarea>
            {{-- Display error message if validation fails for "body" --}}
            @error('body') <br><small style="color:red">{{ $message }}</small> @enderror
        </p>

        {{-- Hidden input ensures "published" is always sent (default = 0) --}}
        <input type="hidden" name="published" value="0">

        {{-- Checkbox for "published" status --}}
        <p>
            <label>
                <input type="checkbox" name="published" value="1" @checked(old('published'))>
                Publié {{-- (French: "Published") --}}
            </label>
            {{-- Display error message if validation fails for "published" --}}
            @error('published') <br><small style="color:red">{{ $message }}</small> @enderror
        </p>

        {{-- Submit and cancel buttons --}}
        <button type="submit">Enregistrer {{-- (French: "Save") --}}</button>
        <a href="{{ route('posts.index') }}">Annuler {{-- (French: "Cancel") --}}</a>
    </form>
@endsection
