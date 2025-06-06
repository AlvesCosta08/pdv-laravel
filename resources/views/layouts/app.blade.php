<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#4f46e5" />
    <meta name="description" content="Sistema de PDV profissional" />
    <meta name="author" content="Sua Empresa ou Equipe" />

    <meta property="og:title" content="{{ config('app.name') }}" />
    <meta property="og:description" content="Sistema PDV rápido e confiável." />
    <meta property="og:image" content="/logo.png" />
    <meta name="robots" content="index, follow">

    <title>{{ config('app.name', 'PDV') }}@hasSection('title') - @yield('title')@endif</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Carrega a biblioteca QRCode no head -->


    <link rel="manifest" href="/manifest.json" />
    <link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180" />

    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body x-data="{ theme: localStorage.theme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') }" x-init="$watch('theme', value => { localStorage.theme = value; document.documentElement.classList.toggle('dark', value === 'dark') })" class="bg-light text-dark min-vh-100 d-flex flex-column">

    <div id="offline-warning" class="d-none bg-warning text-white text-center py-2 px-4" role="alert">
        <i class="fas fa-wifi-slash me-2"></i> Você está offline. Algumas funcionalidades podem estar limitadas.
    </div>

    @include('layouts.navigation')

    @hasSection('header')
    <header class="bg-white sticky-top shadow-sm border-bottom">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            @yield('header')
            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-sm-flex align-items-center gap-2 text-muted small">
                    <i class="far fa-user-circle"></i>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <button class="btn btn-light" aria-label="Configurações">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
        </div>
    </header>
    @endif

    <main role="main" class="flex-grow-1 py-4" data-aos="fade-up">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer class="bg-white border-top py-2 text-muted small">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <span id="current-time"></span>
                <span class="d-none d-sm-inline-flex align-items-center gap-1">
                    <i class="fas fa-database"></i>
                    Online
                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span>
                    {{ config('app.name') }} v1.0
                </span>
                <button onclick="window.print()" class="btn btn-link text-muted">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    </footer>

    <div id="toast-container" class="position-fixed bottom-0 end-0 p-4 z-50" role="region"></div>

    <div id="global-loader" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-25 d-flex align-items-center justify-content-center d-none">
        <div class="bg-white p-4 rounded shadow-lg d-flex flex-column align-items-center gap-3">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="text-muted">Processando...</p>
        </div>
    </div>

    <script>
        AOS.init();

        function updateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString('pt-BR');
        }
        setInterval(updateTime, 1000);
        updateTime();

        window.addEventListener('online', () => {
            document.getElementById('offline-warning')?.classList.add('d-none');
        });
        window.addEventListener('offline', () => {
            document.getElementById('offline-warning')?.classList.remove('d-none');
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(console.error);
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
     <script src="https://unpkg.com/qrcode@1.5.1/build/qrcode.min.js"></script>
</body>
</html>






