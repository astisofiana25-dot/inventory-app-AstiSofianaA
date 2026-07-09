@extends('layouts.app')
@section('title', 'Barang Tersedia')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <!-- Heading shown in topbar; removed duplicate heading here -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600">
                    <th class="py-2">Kode</th>
                    <th class="py-2">Nama Barang</th>
                    <th class="py-2">Kategori</th>
                    <th class="py-2">Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                <tr class="border-t">
                    <td class="py-3">{{ $p->kode_barang }}</td>
                    <td class="py-3">{{ $p->nama_barang }}</td>
                    <td class="py-3">{{ $p->category->nama ?? '-' }}</td>
                    <td class="py-3">{{ $p->stok }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-6 text-center text-gray-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
