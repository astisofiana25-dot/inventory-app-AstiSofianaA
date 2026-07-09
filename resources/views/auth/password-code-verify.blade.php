@extends('layouts.guest')
@section('title', 'Verifikasi Kode Reset Password')
@section('content')
<div class="bg-white rounded-3xl shadow-2xl p-8">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Kode Reset Password</h1>
        <p class="text-gray-500 text-sm">Masukkan 6 angka kode konfirmasi yang telah dikirim ke email</p>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('password.code.verify') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="email" value="{{ old('email', $email) }}">
        <div>
            <div class="flex gap-2 justify-center" id="code-inputs">
                <input type="text" maxlength="1" inputmode="numeric" class="code-input w-12 h-12 border border-gray-300 rounded-lg text-center font-bold text-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm" autofocus>
                <input type="text" maxlength="1" inputmode="numeric" class="code-input w-12 h-12 border border-gray-300 rounded-lg text-center font-bold text-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm">
                <input type="text" maxlength="1" inputmode="numeric" class="code-input w-12 h-12 border border-gray-300 rounded-lg text-center font-bold text-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm">
                <input type="text" maxlength="1" inputmode="numeric" class="code-input w-12 h-12 border border-gray-300 rounded-lg text-center font-bold text-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm">
                <input type="text" maxlength="1" inputmode="numeric" class="code-input w-12 h-12 border border-gray-300 rounded-lg text-center font-bold text-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm">
                <input type="text" maxlength="1" inputmode="numeric" class="code-input w-12 h-12 border border-gray-300 rounded-lg text-center font-bold text-lg focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm">
            </div>
            <input type="hidden" name="code" id="code-value" required>
        </div>
        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-2.5 rounded-lg shadow-lg shadow-brand-600/25 transition">
            Verifikasi Kode
        </button>
    </form>

    <p class="text-sm text-gray-500 mt-6 text-center">
        <a href="{{ route('password.request') }}" class="text-brand-600 font-semibold hover:underline">Kembali</a>
    </p>
</div>

<script>
    const codeInputs = document.querySelectorAll('.code-input');
    const codeValue = document.getElementById('code-value');

    codeInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            // Only allow digits
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            // Move to next input if digit entered
            if (e.target.value && index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
            
            // Update hidden input with all values
            updateCodeValue();
        });

        input.addEventListener('keydown', (e) => {
            // Move to previous input on backspace
            if (e.key === 'Backspace' && !input.value && index > 0) {
                codeInputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').split('');
            pasteData.forEach((digit, i) => {
                if (index + i < codeInputs.length) {
                    codeInputs[index + i].value = digit;
                }
            });
            updateCodeValue();
            if (pasteData.length > 0) {
                codeInputs[Math.min(index + pasteData.length, codeInputs.length - 1)].focus();
            }
        });
    });

    function updateCodeValue() {
        const code = Array.from(codeInputs).map(input => input.value).join('');
        codeValue.value = code;
    }
</script>
@endsection
