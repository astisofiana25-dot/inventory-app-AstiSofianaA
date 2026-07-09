@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <form method="GET" class="flex flex-1 flex-wrap gap-2 items-center">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode / nama / lokasi"
               class="min-w-0 flex-1 border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm rounded-xl py-2 px-3">
        <select name="category_id" class="w-40 min-w-[8rem] border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm rounded-xl py-2 px-3">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="w-36 min-w-[9rem] border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm rounded-xl py-2 px-3">
            <option value="">Semua Status</option>
            <option value="approved" @selected(request('status') == 'approved')>Disetujui</option>
            <option value="pending" @selected(request('status') == 'pending')>Menunggu</option>
        </select>
        <select name="kondisi" class="w-40 min-w-[9rem] border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm rounded-xl py-2 px-3">
            <option value="">Semua Kondisi</option>
            <option value="baik" @selected(request('kondisi') == 'baik')>Baik</option>
            <option value="rusak_ringan" @selected(request('kondisi') == 'rusak_ringan')>Rusak Ringan</option>
            <option value="rusak_berat" @selected(request('kondisi') == 'rusak_berat')>Rusak Berat</option>
        </select>
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 rounded-xl text-sm font-medium h-11">Cari</button>
    </form>
    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff') || auth()->user()->hasRole('manager'))
        <div class="flex items-center gap-2">
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                <a href="{{ route('reports.products.export.excel', request()->only(['q','category_id','status','kondisi'])) }}" class="text-sm bg-green-100 hover:bg-green-200 text-green-900 px-3 py-2 rounded-xl font-medium">XLSX</a>
                <a href="{{ route('reports.products.export.pdf', request()->only(['q','category_id','status','kondisi'])) }}" class="text-sm bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-xl font-medium">PDF</a>
            @endif
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))
                <a href="{{ route('products.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow-lg shadow-brand-600/25 whitespace-nowrap">
                    + Tambah Barang
                </a>
            @endif
        </div>
    @endif
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-5 py-3">Barang</th>
                <th class="text-left px-5 py-3">Kode</th>
                <th class="text-left px-5 py-3">Kategori</th>
                <th class="text-left px-5 py-3">Stok</th>
                <th class="text-left px-5 py-3">Lokasi</th>
                <th class="text-left px-5 py-3">Kondisi</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($products as $product)
                <tr class="hover:bg-gray-50/70">
                    <td class="px-5 py-3 flex items-center gap-3">
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/80x80/fee2e2/dc2626?text='.substr($product->nama_barang,0,1) }}"
                             class="w-10 h-10 rounded-lg object-cover border border-gray-100" alt="{{ $product->nama_barang }}">
                        <span class="font-medium text-gray-800">{{ $product->nama_barang }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $product->kode_barang }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $product->category->name }}</td>
                    <td class="px-5 py-3">
                        <span @class(['font-semibold', 'text-brand-600' => $product->stok <= 3, 'text-gray-700' => $product->stok > 3])>{{ $product->stok }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $product->lokasi_penyimpanan ?: '-' }}</td>
                    <td class="px-5 py-3">
                        @php
                            $kondisiLabel = ['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'];
                            $kondisiColor = ['baik' => 'bg-green-50 text-green-700', 'rusak_ringan' => 'bg-yellow-50 text-yellow-700', 'rusak_berat' => 'bg-brand-50 text-brand-700'];
                        @endphp
                        @php
                            $kcolor = $product->kondisi_barang === 'baik' ? 'green' : ($product->kondisi_barang === 'rusak_ringan' ? 'yellow' : 'brand');
                        @endphp
                        <x-badge :color="$kcolor">{{ $kondisiLabel[$product->kondisi_barang] }}</x-badge>
                    </td>
                    <td class="px-5 py-3">
                        @php
                            $statusLabel = ['approved' => 'Disetujui', 'pending' => 'Menunggu'];
                            $statusColor = ['approved' => 'bg-green-50 text-green-700', 'pending' => 'bg-yellow-50 text-yellow-700'];
                        @endphp
                        @php $stcol = $product->status === 'approved' ? 'green' : 'yellow'; @endphp
                        <x-badge :color="$stcol">{{ $statusLabel[$product->status] ?? ucfirst($product->status) }}</x-badge>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('products.show', $product) }}" class="text-gray-500 hover:text-gray-700 text-xs font-semibold">Detail</a>
                            @if (auth()->user()->hasRole('admin') && $product->status === 'pending')
                                <form method="POST" action="{{ route('products.approve', $product) }}">
                                    @csrf
                                    <button type="submit" class="text-brand-600 hover:text-brand-800 text-xs font-semibold">Approve</button>
                                </form>
                            @endif
                            @if (auth()->user()->hasRole('admin'))
                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Yakin ingin menghapus barang ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">Belum ada data barang.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-5">{{ $products->links() }}</div>
@endsection
