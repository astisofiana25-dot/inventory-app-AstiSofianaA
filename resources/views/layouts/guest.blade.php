<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — SIM Inventaris</title>
    <script>
        (function () {
            const params = new URLSearchParams(window.location.search);
            const showSplash = params.get('splash') === '1';

            if (showSplash) {
                sessionStorage.removeItem('splashShown');
            }

            window.addEventListener('DOMContentLoaded', () => {
                const loginForm = document.querySelector('form[action="{{ route('login') }}"]');
                if (loginForm) {
                    const emailField = loginForm.querySelector('input[name="email"]');
                    const passwordField = loginForm.querySelector('input[name="password"]');
                    loginForm.reset();
                    if (emailField) emailField.value = '';
                    if (passwordField) passwordField.value = '';
                    setTimeout(() => {
                        if (emailField) emailField.value = '';
                        if (passwordField) passwordField.value = '';
                    }, 50);
                }
            });

            if (sessionStorage.getItem('splashShown')) {
                document.documentElement.classList.add('no-splash');
            } else {
                sessionStorage.setItem('splashShown', '1');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { brand: {
            50:'#fef2f2',100:'#fee2e2',200:'#fecaca',300:'#fca5a5',400:'#f87171',
            500:'#ef4444',600:'#dc2626',700:'#b91c1c',800:'#991b1b',900:'#7f1d1d',950:'#450a0a'
        } }, fontFamily: { sans: ['Inter','ui-sans-serif','system-ui'] } } } }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body{
            font-family:'Inter',sans-serif;
        }
        body.bg-gradient-to-br {
            background-image: 
                linear-gradient(135deg, rgba(220, 38, 38, 0.75) 0%, rgba(185, 28, 28, 0.75) 50%, rgba(153, 27, 27, 0.75) 100%),
                url('{{ asset("images/1783057450574.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-brand-800 via-brand-700 to-brand-950">
<x-splash />
<div class="relative min-h-screen flex items-center justify-center overflow-y-auto px-4" style="padding: 2rem 0;">
    <div class="w-full max-w-sm relative z-10">
        @yield('content')
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    if (e.target.closest('[data-password-toggle]')) {
        e.preventDefault();
        const toggle = e.target.closest('[data-password-toggle]');
        const input = toggle.parentElement.querySelector('[data-password-input]');
        const eyeOpen = toggle.querySelector('[data-eye-open]');
        const eyeClosed = toggle.querySelector('[data-eye-closed]');
        
        if (input) {
            if (input.type === 'password') {
                input.type = 'text';
                if (eyeOpen) eyeOpen.classList.remove('hidden');
                if (eyeClosed) eyeClosed.classList.add('hidden');
            } else {
                input.type = 'password';
                if (eyeOpen) eyeOpen.classList.add('hidden');
                if (eyeClosed) eyeClosed.classList.remove('hidden');
            }
        }
    }
});
</script>
</body>
</html>
