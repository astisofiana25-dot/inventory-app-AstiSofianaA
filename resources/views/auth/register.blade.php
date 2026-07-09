@extends('layouts.guest')
@section('title', 'Daftar')
@section('content')
<div class="bg-white rounded-3xl shadow-2xl p-8">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Buat Akun</h1>
        <p class="text-gray-500 text-sm">Daftar untuk mulai mengelola inventaris.</p>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm text-base">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm text-base">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">ID Karyawan</label>
            <input type="text" name="employee_id" value="{{ old('employee_id') }}" required
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm text-base">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
            <x-password-input name="password" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
            <x-password-input name="password_confirmation" required />
        </div>
        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg shadow-lg shadow-brand-600/25 transition">
            Daftar
        </button>
    </form>

    <p class="text-sm text-gray-500 mt-6 text-center">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-brand-600 font-semibold hover:underline">Masuk</a>
    </p>
</div>
@endsection
