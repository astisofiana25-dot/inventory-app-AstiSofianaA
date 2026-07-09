<!DOCTYPE html>
<html lang="id" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', initialized: false, theme: localStorage.getItem('theme') || 'light' }" x-init="sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true'; initialized = true; theme = localStorage.getItem('theme') || 'light'; document.body.classList.remove('alpine-loading'); document.documentElement.classList.toggle('sidebar-collapsed', sidebarCollapsed); document.documentElement.classList.toggle('sidebar-expanded', !sidebarCollapsed); document.documentElement.classList.toggle('theme-dark', theme === 'dark'); document.documentElement.classList.toggle('theme-light', theme === 'light');" x-effect="localStorage.setItem('sidebarCollapsed', sidebarCollapsed); document.documentElement.classList.toggle('sidebar-collapsed', sidebarCollapsed); document.documentElement.classList.toggle('sidebar-expanded', !sidebarCollapsed); localStorage.setItem('theme', theme); document.documentElement.classList.toggle('theme-dark', theme === 'dark'); document.documentElement.classList.toggle('theme-light', theme === 'light');" @sidebarCollapsed.window="sidebarCollapsed = $event.detail; localStorage.setItem('sidebarCollapsed', $event.detail); document.documentElement.classList.toggle('sidebar-collapsed', $event.detail); document.documentElement.classList.toggle('sidebar-expanded', !$event.detail)">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SIM Inventaris</title>
    <script>
        (function () {
            if (sessionStorage.getItem('splashShown')) {
                document.documentElement.classList.add('no-splash');
            } else {
                sessionStorage.setItem('splashShown', '1');
            }
        })();
        try {
            var collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            document.documentElement.classList.add(collapsed ? 'sidebar-collapsed' : 'sidebar-expanded');
        } catch (e) {
            document.documentElement.classList.add('sidebar-expanded');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5',
                            400: '#f87171', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c',
                            800: '#991b1b', 900: '#7f1d1d', 950: '#450a0a',
                        }
                    },
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    @stack('scripts')
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-transition] {
            transition-property: opacity, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 400ms;
        }
        [x-cloak] { display: none !important; }
        /* Sidebar smooth width transition */
        aside {
            transition: none;
            overflow: hidden;
            border-right: 1px solid transparent;
            will-change: width, transform;
            backface-visibility: hidden;
        }
        aside.sidebar-ready {
            transition: width 500ms cubic-bezier(0.4, 0, 0.2, 1), box-shadow 500ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        aside.sidebar-loading {
            transition: none;
        }
        .content-ready {
            transition: margin-left 500ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .content-loading {
            transition: none;
        }
        nav {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        nav::-webkit-scrollbar {
            width: 0;
            height: 0;
        }
        aside nav a span,
        aside nav button span,
        aside > div span {
            display: inline-block;
            transition: opacity 200ms ease, transform 200ms ease;
            transform-origin: left center;
            backface-visibility: hidden;
        }
        /* Main content smooth margin transition */
        .content-ready {
            transition: margin-left 500ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        .content-loading {
            transition: none;
        }
        body.alpine-loading .flex-1 {
            transition: none !important;
        }
        body.alpine-loading [x-cloak] {
            transition: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
        }
        html.sidebar-collapsed .sidebar-brand,
        html.sidebar-collapsed .sidebar-text,
        html.sidebar-collapsed .sidebar-user-info {
            display: none !important;
        }
        html.sidebar-expanded .sidebar-brand,
        html.sidebar-expanded .sidebar-text,
        html.sidebar-expanded .sidebar-user-info {
            display: inline-flex !important;
            flex-direction: column;
            align-items: flex-start;
        }
        html.sidebar-expanded .sidebar-toggle-icon,
        html.sidebar-collapsed .sidebar-toggle-icon {
            opacity: 1;
            visibility: visible;
        }
        html.theme-dark body {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
        }
        html.theme-dark .content-wrapper {
            background-color: #0f172a;
            color: #e2e8f0;
        }
        html.theme-dark .content-wrapper .bg-white {
            background-color: #0f172a !important;
            border-color: #334155 !important;
        }
        html.theme-dark .content-wrapper .bg-gray-50 {
            background-color: #0f172a !important;
        }
        html.theme-dark .content-wrapper .bg-gray-100 {
            background-color: #16253a !important;
        }
        html.theme-dark .content-wrapper .border-gray-100,
        html.theme-dark .content-wrapper .border-gray-200,
        html.theme-dark .content-wrapper .border-gray-300 {
            border-color: #334155 !important;
        }
        html.theme-dark .content-wrapper .text-gray-900,
        html.theme-dark .content-wrapper .text-gray-800 {
            color: #f8fafc !important;
        }
        html.theme-dark .content-wrapper .text-gray-700 {
            color: #cbd5e1 !important;
        }
        html.theme-dark .content-wrapper .text-gray-500,
        html.theme-dark .content-wrapper .text-gray-400,
        html.theme-dark .content-wrapper .text-gray-600 {
            color: #94a3b8 !important;
        }
        html.theme-dark .content-wrapper .bg-white\/85 {
            background-color: rgba(15, 23, 42, 0.95) !important;
        }
        html.theme-dark .content-wrapper .bg-white\/10 {
            background-color: rgba(255, 255, 255, 0.06) !important;
        }
        html.theme-dark .content-wrapper .bg-white\/95,
        html.theme-dark .content-wrapper .bg-white\/80,
        html.theme-dark .content-wrapper .bg-white\/70 {
            background-color: #0f172a !important;
        }
        html.theme-dark .content-wrapper .bg-gray-200 {
            background-color: #15223b !important;
        }
        html.theme-dark .content-wrapper input,
        html.theme-dark .content-wrapper select,
        html.theme-dark .content-wrapper textarea {
            background-color: #15223b !important;
            color: #e2e8f0 !important;
            border: 1px solid #475569 !important;
            box-shadow: none !important;
            background-clip: padding-box !important;
        }
        html.theme-dark .content-wrapper button:not([class*='bg-']):not([class*='text-']) {
            background-color: #15223b !important;
            color: #e2e8f0 !important;
            border: 1px solid #475569 !important;
            box-shadow: none !important;
            background-clip: padding-box !important;
        }
        html.theme-dark .content-wrapper input::placeholder,
        html.theme-dark .content-wrapper textarea::placeholder {
            color: #94a3b8 !important;
        }
        .content-wrapper select {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-position: calc(100% - 1rem) calc(1.2em), calc(100% - 0.75rem) calc(1.2em);
            background-size: 0.45em 0.45em, 0.45em 0.45em;
            background-repeat: no-repeat;
            padding-right: 2.5rem !important;
        }
        html.theme-light .content-wrapper select {
            color: #111827 !important;
            background-color: #ffffff !important;
            border-color: #d1d5db !important;
            background-image: linear-gradient(45deg, transparent 50%, #6b7280 50%), linear-gradient(135deg, #6b7280 50%, transparent 50%);
        }
        html.theme-dark .content-wrapper select {
            color: #e2e8f0 !important;
            background-color: #15223b !important;
            border-color: #475569 !important;
            background-image: linear-gradient(45deg, transparent 50%, #94a3b8 50%), linear-gradient(135deg, #94a3b8 50%, transparent 50%);
        }
        html.theme-dark .content-wrapper .hover\:bg-gray-50:hover {
            background-color: #172843 !important;
        }
        html.theme-dark .content-wrapper tr.hover\:bg-gray-50\/70:hover,
        html.theme-dark .content-wrapper tr.hover\:bg-gray-50:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }
        html.theme-dark .content-wrapper tr:hover td,
        html.theme-dark .content-wrapper tr:hover td span,
        html.theme-dark .content-wrapper tr:hover td a {
            color: #f8fafc !important;
        }
        html.theme-dark .content-wrapper tr:hover td .text-gray-500 {
            color: #cbd5e1 !important;
        }
        html.theme-dark .content-wrapper .hover\:bg-white\/10:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }
        html.theme-dark .content-wrapper .bg-slate-100 {
            background-color: #0f172a !important;
        }
        html.theme-dark .content-wrapper .bg-slate-50 {
            background-color: #0f172a !important;
        }
        html.theme-dark .content-wrapper .badge-date {
            background-color: rgba(248, 113, 113, 0.18) !important;
            color: #fee2e2 !important;
            border-color: rgba(248, 113, 113, 0.35) !important;
        }
        html.theme-dark .content-wrapper .badge-date .w-1\.5 {
            background-color: #f87171 !important;
        }
        html.theme-dark .content-wrapper header {
            background-color: rgba(17, 24, 39, 0.92) !important;
            border-color: #475569 !important;
        }
        html.theme-dark .content-wrapper .bg-brand-50 {
            background-color: rgba(254, 226, 226, 0.92) !important;
            border-color: rgba(248, 113, 113, 0.45) !important;
        }
        html.theme-dark .content-wrapper .bg-brand-50.text-brand-700,
        html.theme-dark .content-wrapper .bg-brand-50.text-brand-800,
        html.theme-dark .content-wrapper .bg-brand-50.text-brand-900 {
            color: #b91c1c !important;
        }
        html.theme-dark .content-wrapper .bg-brand-50.text-brand-700 {
            background-color: rgba(254, 226, 226, 0.92) !important;
            border-color: rgba(248, 113, 113, 0.55) !important;
            color: #b91c1c !important;
        }
        html.theme-dark .content-wrapper .bg-white\/15 {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }
        html.theme-dark .content-wrapper .bg-red-50 {
            background-color: rgba(248, 113, 113, 0.18) !important;
        }
        html.theme-dark .content-wrapper .text-red-600,
        html.theme-dark .content-wrapper .text-red-700 {
            color: #f87171 !important;
        }
        html.theme-dark .content-wrapper .bg-green-50 {
            background-color: rgba(16, 185, 129, 0.18) !important;
        }
        html.theme-dark .content-wrapper .text-green-700,
        html.theme-dark .content-wrapper .text-green-600 {
            color: #86efac !important;
        }
        html.theme-dark .content-wrapper .bg-blue-50 {
            background-color: rgba(59, 130, 246, 0.18) !important;
        }
        html.theme-dark .content-wrapper .text-blue-600,
        html.theme-dark .content-wrapper .text-blue-700 {
            color: #93c5fd !important;
        }
        html.theme-dark .content-wrapper .bg-yellow-50 {
            background-color: rgba(245, 158, 11, 0.18) !important;
        }
        html.theme-dark .content-wrapper .text-yellow-700,
        html.theme-dark .content-wrapper .text-yellow-600 {
            color: #fde68a !important;
        }
        html.theme-dark .content-wrapper .bg-white\/20 {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        html.theme-dark .content-wrapper .border-white\/10 {
            border-color: rgba(148, 163, 184, 0.2) !important;
        }
        html.sidebar-collapsed aside,
        html.sidebar-collapsed .sidebar-loading,
        html.sidebar-collapsed .sidebar-ready {
            width: 5rem !important;
            min-width: 5rem !important;
            max-width: 5rem !important;
        }
        html.sidebar-expanded aside,
        html.sidebar-expanded .sidebar-loading,
        html.sidebar-expanded .sidebar-ready {
            width: 14rem !important;
        }
        html.sidebar-collapsed .content-wrapper {
            margin-left: 5rem !important;
        }
        html.sidebar-expanded .content-wrapper {
            margin-left: 14rem !important;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased alpine-loading">
<x-splash />
<div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', initialized ? 'sidebar-ready' : 'sidebar-loading']"
            class="fixed z-30 inset-y-0 left-0 bg-gradient-to-b from-brand-800 via-brand-700 to-brand-900 text-white transform lg:translate-x-0 lg:fixed lg:inset-y-0 lg:left-0 shadow-2xl shadow-brand-950/40"
        >
        <div class="flex items-center justify-between gap-3 px-6 py-5 border-b border-white/10">
            <div class="sidebar-brand flex items-center gap-3 min-w-0">
                <div>
                    <p class="font-extrabold leading-tight tracking-tight">Telkomsel</p>
                    <p class="text-[11px] text-brand-200 tracking-wide">SIM Inventaris</p>
                </div>
            </div>
            <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition flex-shrink-0" title="Collapse/Expand">
                <svg class="w-5 h-5 sidebar-collapse-icon transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
        </div>
        <nav class="px-3 py-4 space-y-1 text-sm overflow-y-auto flex-1">
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('staff'))
            <a href="{{ route('dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'nav-active bg-white/15 font-semibold shadow-inner' : 'hover:bg-white/10 text-brand-50/90' }}" :title="sidebarCollapsed ? 'Dashboard' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="sidebar-text whitespace-nowrap">Dashboard</span>
            </a>
            @if (auth()->user()->hasRole('admin'))
            <a href="{{ route('users.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('users.*') ? 'nav-active bg-white/15 font-semibold shadow-inner' : 'hover:bg-white/10 text-brand-50/90' }}" :title="sidebarCollapsed ? 'Pengguna' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1M15 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span class="sidebar-text whitespace-nowrap">Pengguna</span>
            </a>
            @endif
            @endif
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff') || auth()->user()->hasRole('manager'))
            <a href="{{ route('products.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('products.*') ? 'nav-active bg-white/15 font-semibold shadow-inner' : 'hover:bg-white/10 text-brand-50/90' }}" :title="sidebarCollapsed ? 'Data Barang' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                <span class="sidebar-text whitespace-nowrap">Data Barang</span>
            </a>
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
            <a href="{{ route('categories.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('categories.*') ? 'nav-active bg-white/15 font-semibold shadow-inner' : 'hover:bg-white/10 text-brand-50/90' }}" :title="sidebarCollapsed ? 'Kategori' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                <span class="sidebar-text whitespace-nowrap">Kategori</span>
            </a>
            @endif
            <a href="{{ route('borrowings.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('borrowings.*') ? 'nav-active bg-white/15 font-semibold shadow-inner' : 'hover:bg-white/10 text-brand-50/90' }}" :title="sidebarCollapsed ? (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') ? 'Peminjaman Staff' : 'Peminjaman') : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                <span class="sidebar-text whitespace-nowrap">{{ auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') ? 'Peminjaman Staff' : 'Peminjaman' }}</span>
            </a>
            @if (auth()->user()->hasRole('staff'))
            <a href="{{ route('staff.riwayat') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('staff.riwayat') ? 'nav-active bg-white/15 font-semibold shadow-inner' : 'hover:bg-white/10 text-brand-50/90' }}" :title="sidebarCollapsed ? 'Riwayat Staff' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="sidebar-text whitespace-nowrap">Riwayat Staff</span>
            </a>
            @endif
            @endif
        </nav>
        <div class="absolute bottom-0 inset-x-0 p-4 border-t border-white/10 bg-brand-900/30">
            <a href="{{ route('profile.edit') }}" class="block rounded-2xl px-3 py-2 transition hover:bg-white/10" :class="sidebarCollapsed ? 'justify-center' : ''">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full overflow-hidden ring-2 ring-white/20 flex items-center justify-center bg-white/15 flex-shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Foto Profil" class="w-full h-full object-cover" />
                        @else
                            <span class="font-semibold text-sm text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="sidebar-user-info text-xs leading-tight min-w-0">
                        <p class="font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-brand-200 capitalize truncate">{{ auth()->user()->role->name ?? '-' }}</p>
                    </div>
                </div>
            </a>
            <div class="mt-2"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button translate="no" class="w-full text-left text-xs bg-white/10 hover:bg-white/20 transition rounded-lg px-3 py-2 flex items-center gap-2 notranslate" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    <span translate="no" class="sidebar-text whitespace-nowrap notranslate">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 content-wrapper" :class="[initialized ? 'content-ready' : 'content-loading', sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-56', theme === 'dark' ? 'bg-slate-950 text-slate-100' : 'bg-gray-50 text-gray-800']">
        <!-- Topbar -->
        <header x-data="{ notifOpen: false }" class="sticky top-0 z-20 bg-white/85 backdrop-blur-md border-b border-gray-200/80 px-4 lg:px-8 py-4 flex items-center justify-between shadow-sm shadow-gray-900/[0.02]">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden w-9 h-9 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center">☰</button>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 leading-tight">@yield('title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-400 hidden sm:block">Telkomsel · SIM Inventaris</p>
                </div>
            </div>
            <div class="flex items-center gap-3 relative">
                @php
                    $notifications = auth()->check() ? \App\Models\Notification::where('user_id', auth()->id())->latest()->take(5)->get() : collect();
                    $unreadNotifications = $notifications->whereNull('read_at')->count();
                @endphp
                <button @click="notifOpen = !notifOpen" type="button" class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                    @if($unreadNotifications > 0)
                        <span class="absolute top-0 right-0 inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-red-600 px-1.5 text-[10px] font-semibold text-white">{{ $unreadNotifications }}</span>
                    @endif
                </button>
                <button @click="theme = theme === 'light' ? 'dark' : 'light'" type="button" class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition" :class="theme === 'dark' ? 'bg-gray-900 text-white border-gray-700 hover:bg-gray-800' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'" title="Toggle theme">
                    <svg x-show="theme === 'light'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M7.05 7.05L5.636 5.636m12.728 0l-1.414 1.414M7.05 16.95l-1.414 1.414M12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
                    <svg x-show="theme === 'dark'" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7 7 0 109.79 9.79z"/></svg>
                </button>
                <div x-show="notifOpen" x-transition.opacity x-cloak @click.outside="notifOpen = false" class="absolute right-0 top-full mt-2 w-96 bg-white border border-gray-200 rounded-2xl shadow-xl overflow-hidden z-50 origin-top-right">
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-end">
                        <a href="{{ route('notifications.read_all') }}" class="text-xs text-brand-600 hover:text-brand-700" onclick="event.preventDefault(); document.getElementById('mark-all-form').submit();">Tandai semua</a>
                    </div>
                    <form id="mark-all-form" method="POST" action="{{ route('notifications.read_all') }}" class="hidden">@csrf</form>
                    <div class="max-h-72 overflow-y-auto">
                        @forelse($notifications as $notification)
                            <div class="px-4 py-3 {{ $notification->read_at ? 'bg-white' : 'bg-brand-50' }} border-b border-gray-100">
                                <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold {{ $notification->read_at ? 'text-gray-900' : 'text-brand-900' }}">{{ $notification->title }}</p>
                                            <p class="text-xs mt-1 {{ $notification->read_at ? 'text-gray-500' : 'text-brand-700' }}">{{ Str::limit($notification->message, 80) }}</p>
                                        </div>
                                        <span class="text-[11px] uppercase tracking-[0.15em] font-semibold {{ $notification->read_at ? 'text-gray-400' : 'text-brand-700' }}">{{ $notification->read_at ? 'Dibaca' : 'Baru' }}</span>
                                    </div>
                                    <p class="text-[11px] {{ $notification->read_at ? 'text-gray-400' : 'text-brand-700' }} mt-2">{{ $notification->created_at->translatedFormat('d M Y H:i') }}</p>
                            </div>
                        @empty
                            <div class="px-4 py-5 text-center text-sm text-gray-500">Tidak ada notifikasi.</div>
                        @endforelse
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right">
                        <a href="{{ route('notifications.index') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">Lihat semua</a>
                    </div>
                </div>
                <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-medium text-brand-700 bg-brand-50 px-3 py-1.5 rounded-full border border-brand-100 badge-date">
                    <span class="w-1.5 h-1.5 rounded-full bg-brand-600 animate-pulse"></span> {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </header>

        <main class="flex-1 p-4 lg:p-8">
            <x-alert />
            @yield('content')
        </main>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.js-auto-dismiss').forEach(function (alert) {
            const timeout = parseInt(alert.dataset.dismissAfter, 10) || 5000;
            setTimeout(function () {
                alert.style.transition = 'opacity 300ms ease, transform 300ms ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function () {
                    alert.remove();
                }, 300);
            }, timeout);
        });
    });
</script>
</body>
</html>
