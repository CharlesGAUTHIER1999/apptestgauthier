@extends("layouts.app")
@section('title', $title ?? 'Mon App')
@section('content')
    <h1 class="text-4xl font-bold text-blue-600">{{ $title ?? 'Accueil' }}</h1>
    <p class="text-lg text-gray-700">Bienvenue sur la page d'accueil Laravel 🎉</p>
@endsection
