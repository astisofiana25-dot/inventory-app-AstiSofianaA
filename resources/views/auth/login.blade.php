@extends('layouts.guest')
@section('title', 'Masuk')
@section('content')
<div class="bg-white rounded-3xl shadow-2xl p-8">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Selamat Datang 👋</h1>
        <p class="text-gray-500 text-sm">Masuk untuk mengakses SIM Inventaris.</p>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" autocomplete="off">
        @csrf
        <input type="text" name="fake_username" autocomplete="username" style="position:absolute;opacity:0;width:0;height:0;border:0;padding:0;margin:0;" tabindex="-1" />
        <input type="password" name="fake_password" autocomplete="new-password" style="position:absolute;opacity:0;width:0;height:0;border:0;padding:0;margin:0;" tabindex="-1" />
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                 <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="off" readonly
                     class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm text-base" placeholder="nama@gmail.com"
                   onfocus="this.removeAttribute('readonly');" onkeydown="this.removeAttribute('readonly');">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <x-password-input name="password" required />
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                Ingat saya
            </label>
            <a href="{{ route('password.request') }}" class="text-brand-600 font-medium hover:underline">Lupa password?</a>
        </div>
        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg shadow-lg shadow-brand-600/25 transition">
            Masuk
        </button>
    </form>

    <p class="text-sm text-gray-500 mt-6 text-center">
        Belum punya akun? <a href="{{ route('register') }}" class="text-brand-600 font-semibold hover:underline">Daftar</a>
    </p>
</div>
@endsection
