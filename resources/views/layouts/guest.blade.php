<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PDV') }}</title>

    <!-- Fonte personalizada -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts da aplicação (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="font-family: 'Figtree', sans-serif;" class="bg-light text-dark">

    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-4">
        <!-- Logo -->
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20" />
            </a>
        </div>

        <!-- Card de conteúdo -->
        <div class="w-100 mt-4 p-4 bg-white shadow rounded" style="max-width: 420px;">
            {{ $slot }}
        </div>
    </div>

    <!-- Bootstrap JS (inclui Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


