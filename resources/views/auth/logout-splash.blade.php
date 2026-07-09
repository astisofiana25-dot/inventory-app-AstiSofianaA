@extends('layouts.guest')
@section('title', 'Memproses keluar')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-sm rounded-3xl bg-white/95 px-8 py-10 text-center shadow-2xl backdrop-blur">
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-brand-100 text-brand-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 12h10m0 0l-3-3m3 3l-3 3" />
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-900">Sedang memproses keluar...</h1>
        <p class="mt-2 text-sm text-gray-500">Anda akan diarahkan ke halaman masuk sebentar lagi.</p>
        <div class="mt-6 flex justify-center">
            <div class="h-2.5 w-28 overflow-hidden rounded-full bg-gray-200">
                <div class="h-full w-1/2 animate-[loading_1.2s_ease-in-out_infinite] rounded-full bg-brand-600"></div>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(function () {
        window.location.href = '{{ route('login', ['from_logout' => 1]) }}';
    }, 1500);
</script>
@endsection
