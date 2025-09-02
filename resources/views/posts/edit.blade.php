@extends('layouts.app')

@section('title', 'Éditer : '.$post->title)

@section('content')
    <h1>Éditer</h1>

    @if ($errors->any())
        <div style="background:#fff3cd;border:1px solid #ffeeba;padding:.75rem 1rem;margin:1rem 0;">
            Merci de corriger les erreurs ci-dessous.
        </div>
    @endif

    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf @method('PUT')

        <p>
            <label for="title">Titre</label><br>
            <input id="title" type="text" name="title" value="{{ old('title', $post->title) }}" required
                @error('title') aria-invalid="true" @enderror>
            @error('title') <br><small style="color:red">{{ $message }}</small> @enderror
        </p>

        <p>
            <label for="body">Contenu</label><br>
            <textarea name="body" rows="6">{{ old('body', $post->body) }}</textarea>
            @error('body') <br><small style="color:red">{{ $message }}</small> @enderror
        </p>
        <input type="hidden" name="published" value="0">
        <p>
            <label>
                <input type="checkbox" name="published" value="1" @checked(old('published', $post->published))>
                Publié
            </label>
            @error('published') <br><small style="color:red">{{ $message }}</small> @enderror
        </p>

        <button type="submit">Mettre à jour</button>
        <a href="{{ route('posts.index') }}">Annuler</a>
    </form>
@endsection
