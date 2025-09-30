<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>@yield('title', 'Mon App')</title>
        {{-- CSS global --}}
        @vite('resources/css/app.css')
        {{-- CSS spécifique --}}
        @stack('styles')
        @vite('resources/js/app.js')
    </head>
    <body class="bg-gray-100 text-gray-800">
        <header class="bg-white shadow p-4">
            <nav class="space-x-4">
                <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Accueil</a>
                <a href="/hello" class="text-blue-600 hover:underline">Hello</a>
                <a href="/hello-view" class="text-blue-600 hover:underline">Hello View</a>
            </nav>
        </header>
        <main class="p-6">
            @yield('content')
        </main>
    </body>
</html>
