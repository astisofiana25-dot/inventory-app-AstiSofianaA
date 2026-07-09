@extends('layouts.app')
@section('title', 'Form Pengembalian')
@section('content')

<form method="POST" action="{{ route('borrowings.return', $borrowing) }}" enctype="multipart/form-data" id="return-form">
    @csrf

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift overflow-hidden p-5">
        @if ($borrowing->details->count() === 1)
            <h2 class="text-lg font-semibold mb-4">{{ $borrowing->details->first()->product->nama_barang }}</h2>
        @else
            <h2 class="text-lg font-semibold mb-4">Pengembalian Barang</h2>
        @endif

        @foreach ($borrowing->details as $i => $detail)
            <div class="mb-6 border p-4 rounded-lg w-full">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-shrink-0 w-full sm:w-56">
                        @if(!empty($detail->product->image))
                            <img src="{{ $detail->product->image }}" alt="{{ $detail->product->nama_barang }}" class="w-full h-64 object-cover rounded-lg shadow-sm bg-gray-100" />
                        @else
                            <div class="w-full h-64 rounded-lg bg-gray-100 flex items-center justify-center shadow-sm">
                                <span class="text-6xl font-extrabold text-gray-500">{{ strtoupper(substr($detail->product->nama_barang,0,1)) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        <div class="space-y-4 w-full">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Tanggal Pengembalian</label>
                                <input type="date" name="tanggal_kembali_aktual" value="{{ old('tanggal_kembali_aktual', now()->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded px-3 py-2 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-black" required>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Kondisi</label>
                                <select name="items[{{ $i }}][kondisi]" class="mt-1 block w-full border-gray-300 rounded px-3 py-2 text-base bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-black" data-index="{{ $i }}">
                                    <option value="baik">Baik</option>
                                    <option value="rusak ringan">Rusak Ringan</option>
                                    <option value="rusak berat">Rusak Berat</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Keterangan (wajib jika rusak)</label>
                                <textarea name="items[{{ $i }}][keterangan]" class="mt-1 block w-full border-gray-300 rounded px-3 py-2 text-base bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-black" rows="3" data-index="{{ $i }}" placeholder="Tambahkan keterangan jika barang rusak..."></textarea>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Foto (min 1, max 3)</label>
                                <input type="file" name="items[{{ $i }}][photos][]" accept="image/*" multiple class="mt-1 block w-full border-gray-200 rounded px-3 py-2 text-base bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-black" data-index="{{ $i }}">
                                <p class="text-xs text-gray-400 mt-1">Unggah hingga 3 foto. Minimal 1 foto per barang.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="flex justify-end gap-3">
            <a href="{{ route('borrowings.show', $borrowing) }}" class="px-4 py-2 rounded-lg border">Batal</a>
            <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-lg">Simpan & Kembalikan</button>
        </div>
    </div>
</form>

<script>
document.getElementById('return-form').addEventListener('submit', function (e) {
    const form = e.target;
    const errors = [];

    // For each detail, validate photos count and keterangan when damaged
    form.querySelectorAll('select[name^="items"]').forEach(function (select) {
        const idx = select.dataset.index;
        const kondisi = select.value;
        const fileInput = form.querySelector('input[name="items['+idx+'][photos][]"]');
        const keterangan = form.querySelector('textarea[name="items['+idx+'][keterangan]"]');

        if (!fileInput) return;
        const files = fileInput.files || [];
        if (files.length < 1) {
            errors.push('Setiap barang harus mengunggah minimal 1 foto.');
        }
        if (files.length > 3) {
            errors.push('Maksimal 3 foto per barang.');
        }
        if ((kondisi === 'rusak ringan' || kondisi === 'rusak berat') && (!keterangan.value || !keterangan.value.trim())) {
            errors.push('Keterangan wajib untuk kondisi rusak pada salah satu barang.');
        }
    });

    if (errors.length) {
        e.preventDefault();
        alert(errors.join('\n'));
        return false;
    }
});

// Limit files to 3 on file inputs
document.querySelectorAll('input[type="file"]').forEach(function (input) {
    input.addEventListener('change', function () {
        if (this.files.length > 3) {
            alert('Maksimal 3 foto.');
            this.value = null;
        }
    });
});
</script>

@endsection
