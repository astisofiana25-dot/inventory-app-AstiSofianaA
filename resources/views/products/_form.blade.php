@php
    $p = $product ?? null;
    $requireImage = request()->routeIs('products.create') && (auth()->user()?->hasRole('staff') || auth()->user()?->hasRole('admin'));
@endphp
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode Barang</label>
        <input id="kode-barang" type="text" name="kode_barang" value="{{ old('kode_barang', $p->kode_barang ?? '') }}" required
               @unless(auth()->user()->hasRole('admin')) readonly @endunless
               class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm {{ auth()->user()->hasRole('admin') ? '' : 'bg-gray-50 text-gray-700 cursor-not-allowed' }}"
               placeholder="BRG01-K001">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Barang</label>
        <input type="text" name="nama_barang" value="{{ old('nama_barang', $p->nama_barang ?? '') }}" required
               @if(isset($p) && auth()->user()->hasRole('staff')) readonly @endif
               class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm {{ (isset($p) && auth()->user()->hasRole('staff')) ? 'bg-gray-50 text-gray-700 cursor-not-allowed' : '' }}">
        @if(isset($p) && auth()->user()->hasRole('staff'))
            <p class="text-xs text-gray-400 mt-1">Nama barang tidak dapat diubah oleh akun staff.</p>
        @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
        <select id="category-select" name="category_id" required
            @if(isset($p) && auth()->user()->hasRole('staff')) disabled @endif
            class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm {{ (isset($p) && auth()->user()->hasRole('staff')) ? 'bg-gray-50 text-gray-700 cursor-not-allowed' : '' }}">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}"
                        data-category-code="{{ $cat->code }}"
                        data-category-next="{{ $cat->next_code_number }}"
                        @selected(old('category_id', $p->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
            @if(isset($p) && auth()->user()->hasRole('staff'))
                <p class="text-xs text-gray-400 mt-1">Kategori tidak dapat diubah oleh akun staff.</p>
            @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Stok</label>
        <input type="number" min="0" name="stok" value="{{ old('stok', $p->stok ?? 0) }}" required
               class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Lokasi Penyimpanan</label>
        <input type="text" name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan', $p->lokasi_penyimpanan ?? '') }}"
               class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kondisi Barang</label>
        <select name="kondisi_barang" required class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
            @foreach (['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'] as $val => $label)
                <option value="{{ $val }}" @selected(old('kondisi_barang', $p->kondisi_barang ?? 'baik') == $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1.5">
            Foto Barang
            @if($requireImage)
                <span class="text-red-500">*</span>
            @else
                <span class="text-gray-400">(opsional)</span>
            @endif
        </label>
        <input type="file" name="image" accept="image/*" {{ $requireImage ? 'required' : '' }}
               class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold hover:file:bg-brand-100">
        <p class="mt-2 text-xs text-gray-500">Maksimal 2MB. Format .jpg, .jpeg, .png.</p>
        @if ($p && $p->image)
            <img src="{{ asset('storage/'.$p->image) }}" class="mt-3 w-24 h-24 rounded-xl object-cover border border-gray-200">
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('category-select');
            const kodeInput = document.getElementById('kode-barang');

            function updateKodeBarang() {
                const selected = categorySelect.selectedOptions[0];
                if (!selected || !selected.dataset.categoryCode) {
                    return;
                }

                const nextNumber = selected.dataset.categoryNext.padStart(2, '0');
                const categoryCode = selected.dataset.categoryCode;
                const generated = `BRG${nextNumber}-${categoryCode}`;

                if (!kodeInput.value || kodeInput.value.startsWith('BRG')) {
                    kodeInput.value = generated;
                }
            }

            categorySelect.addEventListener('change', function () {
                if (!kodeInput.value || kodeInput.value.startsWith('BRG')) {
                    updateKodeBarang();
                }
            });

            // auto-fill on load if kode empty or default
            if (!kodeInput.value || kodeInput.value.startsWith('BRG')) {
                updateKodeBarang();
            }
        });
    </script>
</div>
