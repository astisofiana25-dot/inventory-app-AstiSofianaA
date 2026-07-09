<div class="relative">
    <input type="password" 
           name="{{ $name }}" 
           value="{{ old($name, $value ?? '') }}" 
           required="{{ isset($required) ? 'required' : '' }}"
           autocomplete="off" readonly
           {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:border-brand-500 focus:ring-2 focus:ring-brand-500 focus:outline-none shadow-sm pr-12 text-base']) }}
           data-password-input onfocus="this.removeAttribute('readonly');" onkeydown="this.removeAttribute('readonly');">
    <button type="button" 
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer z-10"
            data-password-toggle
            aria-label="Tampilkan atau sembunyikan password"
            tabindex="-1">
        <svg class="w-5 h-5 hidden" data-eye-open viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle>
        </svg>
        <svg class="w-5 h-5" data-eye-closed viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
            <line x1="1" y1="1" x2="23" y2="23"></line>
        </svg>
    </button>
</div>
@once
    @push('scripts')
        <script>
            function initPasswordToggles() {
                document.querySelectorAll('[data-password-toggle]').forEach(function (toggle) {
                    if (toggle.dataset.initialized === 'true') {
                        return;
                    }

                    var input = toggle.closest('div').querySelector('[data-password-input]');
                    var eyeOpen = toggle.querySelector('[data-eye-open]');
                    var eyeClosed = toggle.querySelector('[data-eye-closed]');

                    if (!input) {
                        return;
                    }

                    toggle.dataset.initialized = 'true';

                    toggle.addEventListener('click', function () {
                        var showing = input.type === 'text';
                        input.type = showing ? 'password' : 'text';
                        if (eyeOpen) {
                            eyeOpen.classList.toggle('hidden', showing);
                        }
                        if (eyeClosed) {
                            eyeClosed.classList.toggle('hidden', !showing);
                        }
                    });
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initPasswordToggles);
            } else {
                initPasswordToggles();
            }
        </script>
    @endpush
@endonce
