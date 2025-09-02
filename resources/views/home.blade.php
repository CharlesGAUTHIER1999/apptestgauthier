@extends("layouts.app")
@section('title', $title ?? 'Mon App')
@section('content')
    <h1>{{ $title ?? 'Accueil' }}</h1>
    <p>Bienvenue sur la page d'accueil Laravel</p>
@endsection
