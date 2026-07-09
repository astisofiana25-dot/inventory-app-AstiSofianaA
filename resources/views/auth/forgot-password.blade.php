@extends('layouts.guest')
@section('title', 'Lupa Password')
@section('content')
<div class="bg-white rounded-3xl shadow-2xl p-8">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Lupa Password</h1>
        <p class="text-gray-500 text-sm">Masukkan email untuk menerima kode reset password</p>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm text-base">
        </div>
        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg shadow-lg shadow-brand-600/25 transition">
            Kirim Kode
        </button>
    </form>

    <p class="text-sm text-gray-500 mt-6 text-center">
        <a href="{{ route('login') }}" class="text-brand-600 font-semibold hover:underline">Kembali</a>
    </p>
</div>
@endsection
