@extends('layouts.app')
@section('title', 'Catat Peminjaman')
@section('content')
<div class="w-full max-w-full bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-6"
     x-data="{
        items: [{ product_id: '', jumlah: 1 }],
        add() { this.items.push({ product_id: '', jumlah: 1 }) },
        remove(i) { this.items.splice(i, 1) }
     }">
    <form method="POST" action="{{ route('borrowings.store') }}" class="space-y-5">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Peminjam</label>
                <input type="text" name="nama_peminjam" required
                       class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon</label>
                <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required
                       class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm" placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required
                       class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Rencana Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}" required
                       class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Barang Dipinjam</label>
            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div class="flex gap-3 items-center">
                        <select :name="'items['+index+'][product_id]'" x-model="item.product_id" required
                                class="flex-1 border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm">
                            <option value="">Pilih barang</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->nama_barang }}</option>
                            @endforeach
                        </select>
                        <input type="number" min="1" :name="'items['+index+'][jumlah]'" x-model="item.jumlah" required
                               class="w-24 border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm">
                        <button type="button" @click="remove(index)" x-show="items.length > 1" class="text-brand-500 hover:text-brand-700 text-sm">✕</button>
                    </div>
                </template>
            </div>
            <button type="button" @click="add()" class="mt-3 text-sm font-semibold text-brand-600 hover:underline">+ Tambah Barang</button>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan / Digunakan Untuk Apa</label>
            <textarea name="keterangan" rows="3" placeholder="Contoh: dipakai untuk kegiatan rapat kantor"
                      class="w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm">{{ old('keterangan') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-lg shadow-brand-600/25">Simpan Peminjaman</button>
            <a href="{{ route('borrowings.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-500 hover:bg-gray-100">Batal</a>
        </div>
    </form>
</div>
@endsection
