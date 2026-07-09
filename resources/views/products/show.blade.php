@extends('layouts.app')
@section('title', 'Detail Barang')
@section('content')
<div class="mx-auto w-full max-w-4xl bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-6">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <span class="text-xs text-gray-400">{{ $product->kode_barang }}</span>
                <h2 class="text-xl font-extrabold text-gray-900 mb-3">{{ $product->nama_barang }}</h2>
                @php
                    $statusLabel = ['approved' => 'Disetujui', 'pending' => 'Menunggu'];
                    $statusColor = ['approved' => 'bg-green-50 text-green-700', 'pending' => 'bg-yellow-50 text-yellow-700'];
                @endphp
                @php $stcol = $product->status === 'approved' ? 'green' : 'yellow'; @endphp
                <x-badge :color="$stcol">{{ $statusLabel[$product->status] ?? ucfirst($product->status) }}</x-badge>
            </div>
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))
                <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center justify-center bg-brand-600 hover:bg-brand-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow-lg shadow-brand-600/25">
                    Edit Barang
                </a>
            @endif
        </div>
        <div class="flex flex-col lg:flex-row gap-6">
            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/260x260/fee2e2/dc2626?text='.substr($product->nama_barang,0,1) }}"
                 class="w-full lg:w-56 h-56 rounded-xl object-cover border border-gray-100">
            <div class="flex-1">
                <dl class="grid grid-cols-2 gap-y-3 text-sm">
                    <dt class="text-gray-400">Kategori</dt><dd class="font-medium text-gray-700">{{ $product->category->name }}</dd>
                    <dt class="text-gray-400">Stok</dt><dd class="font-medium text-gray-700">{{ $product->stok }}</dd>
                    <dt class="text-gray-400">Lokasi</dt><dd class="font-medium text-gray-700">{{ $product->lokasi_penyimpanan ?: '-' }}</dd>
                    <dt class="text-gray-400">Kondisi</dt><dd class="font-medium text-gray-700">{{ ucfirst(str_replace('_',' ',$product->kondisi_barang)) }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
