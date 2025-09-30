@extends('layouts.app')

@push('styles')
    @vite('resources/css/admin.css')
@endpush

@section('content')
    <h1 class="page-title">{{ $title }}</h1>
    <p>Welcome to the admin panel 🎉</p>
@endsection
