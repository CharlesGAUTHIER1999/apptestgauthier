@extends('layouts.app')

@section('title', 'Créer un post')

@push('styles')
    @vite('resources/css/posts-create.css')
@endpush

@section('content')
    <h1 class="page-title">Nouveau post</h1>

    @if($errors->any())
        <div class="alert-warning">
            Merci de corriger les erreurs ci-dessous
        </div>
    @endif

    <form method="POST" action="{{ route('posts.store') }}">
        @csrf

        <div>
            <label for="title">Titre</label>
            <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus
                   @error('title') aria-invalid="true" @enderror>
            @error('title') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="body">Contenu</label>
            <textarea id="body" name="body" rows="6">{{ old('body') }}</textarea>
            @error('body') <span class="error">{{ $message }}</span> @enderror
        </div>

        <input type="hidden" name="published" value="0">

        <div>
            <label>
                <input type="checkbox" name="published" value="1" @checked(old('published'))>
                Publié
            </label>
            @error('published') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div>
            <button type="submit">Enregistrer</button>
            <a href="{{ route('posts.index') }}" class="cancel-link">Annuler</a>
        </div>
    </form>
@endsection
