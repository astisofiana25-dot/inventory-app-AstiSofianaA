@extends('layouts.app')
@section('title', 'Kategori: ' . $category->name)
@section('content')

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-5 py-3">Kode</th>
                <th class="text-left px-5 py-3">Nama Barang</th>
                @if(auth()->user()->hasRole('admin'))
                    <th class="text-right px-5 py-3">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50/70">
                <td class="px-5 py-3">{{ $product->kode_barang }}</td>
                <td class="px-5 py-3">{{ $product->nama_barang }}</td>
                @if(auth()->user()->hasRole('admin'))
                    <td class="px-5 py-3 text-right">
                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                        </form>
                    </td>
                @endif
            </tr>
            @empty
            <tr><td colspan="{{ auth()->user()->hasRole('admin') ? 3 : 2 }}" class="px-5 py-10 text-center text-gray-400">Belum ada barang pada kategori ini.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $products->links() }}</div>
</div>

@endsection
