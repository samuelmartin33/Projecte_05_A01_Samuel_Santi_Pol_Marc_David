<!DOCTYPE html>
<html lang="es" class="@yield('html-class', '')">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Token CSRF en meta tag — lo lee el JS para las peticiones AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'VIBEZ')</title>

    {{-- Google Fonts: Inter (todas las variantes de peso usadas) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Hoja de estilos principal --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- Slot para estilos adicionales específicos de cada vista --}}
    @yield('extra-css')
</head>
<body class="@yield('body-class', '')">
    @yield('content')

    {{-- Slot para scripts al final del body --}}
    @yield('scripts')
</body>
</html>
