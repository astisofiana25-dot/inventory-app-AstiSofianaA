<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — SIM Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {
            50:'#fef2f2',100:'#fee2e2',200:'#fecaca',300:'#fca5a5',400:'#f87171',
            500:'#ef4444',600:'#dc2626',700:'#b91c1c',800:'#991b1b',900:'#7f1d1d',950:'#450a0a'
        } }, fontFamily: { sans: ['Inter','ui-sans-serif','system-ui'] } } } }
    </script>
    <script>
        (function () {
            const path = window.location.pathname;
            const params = new URLSearchParams(window.location.search);
            const fromLogout = params.get('from_logout') === '1';

            if (path === '/logout/splash') {
                sessionStorage.removeItem('splashShown');
                document.documentElement.classList.remove('no-splash');
            } else if (fromLogout) {
                sessionStorage.setItem('splashShown', '1');
                document.documentElement.classList.add('no-splash');
            } else if (sessionStorage.getItem('splashShown')) {
                document.documentElement.classList.add('no-splash');
            } else {
                sessionStorage.setItem('splashShown', '1');
            }
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-brand-950">
<x-splash />
<div class="min-h-screen relative overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center bg-fixed" style="background-image: url('{{ asset('images/1783057450574.png') }}');"></div>
    <div class="absolute inset-0 bg-brand-700/65"></div>
    <div class="relative min-h-screen flex items-center justify-center px-6 py-8 overflow-y-auto">
        <div class="w-full max-w-md mx-auto">
            @yield('content')
        </div>
    </div>
</div>
@stack('scripts')
</body>
</html>
