@extends('layouts.guest')
@section('title', 'Reset Password')
@section('content')
<div class="bg-white rounded-3xl shadow-2xl p-8">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Reset Password</h1>
        <p class="text-gray-500 text-sm">Buat password baru untuk akun Anda.</p>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" required readonly
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 bg-gray-50 text-gray-700 shadow-sm text-base cursor-not-allowed">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
            <x-password-input name="password" required />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
            <x-password-input name="password_confirmation" required />
        </div>
        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg shadow-lg shadow-brand-600/25 transition">
            Reset Password
        </button>
    </form>
</div>
@endsection
