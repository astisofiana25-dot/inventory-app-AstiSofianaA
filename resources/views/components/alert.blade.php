@if (session('success'))
    <div class="js-auto-dismiss mb-6 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3.5 text-sm text-green-700 shadow-sm shadow-green-900/[0.03]" data-dismiss-after="5000">
        <span class="w-7 h-7 rounded-full bg-green-100 flex items-center justify-center shrink-0">✅</span>
        <span class="pt-1">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="js-auto-dismiss mb-6 flex items-start gap-3 rounded-2xl border border-brand-200 bg-brand-50 px-4 py-3.5 text-sm text-brand-700 shadow-sm shadow-brand-900/[0.03]" data-dismiss-after="5000">
        <span class="w-7 h-7 rounded-full bg-brand-100 flex items-center justify-center shrink-0">⚠️</span>
        <span class="pt-1">{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-brand-200 bg-brand-50 px-4 py-3.5 text-sm text-brand-700 shadow-sm shadow-brand-900/[0.03]">
        <ul class="list-none space-y-0.5 pl-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
