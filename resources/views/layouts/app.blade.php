<!DOCTYPE HTML>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>@yield('title', 'Mon App')</title>
    </head>
    <body>
        <header>
            <nav>
                <a href="{{route('home')}}">Accueil</a>
                <a href="/hello">Hello</a>
                <a href="/hello-view">Hello View</a>
            </nav>
        </header>
        <main>
            @yield('content')
        </main>
    </body>
</html>
