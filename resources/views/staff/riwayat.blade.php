@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')
@section('content')

<div class="mb-6 w-full">
    <form method="GET" class="w-full grid gap-3 md:grid-cols-6 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="q">Cari Staff</label>
            <input type="text" id="q" name="q" value="{{ request('q') }}" placeholder="Nama / email"
                   class="block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm px-3 py-2 rounded-lg">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="status">Status</label>
            <select id="status" name="status" class="block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm px-3 py-2 rounded-lg">
                <option value="">Semua Status</option>
                <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="product_id">Filter Barang</label>
            <select id="product_id" name="product_id" class="block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm px-3 py-2 rounded-lg">
                <option value="">Semua Barang</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->nama_barang }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Pinjam dari</label>
            <input type="date" name="pinjam_from" value="{{ request('pinjam_from') }}" class="block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm px-3 py-2 rounded-lg">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Sampai</label>
            <input type="date" name="pinjam_to" value="{{ request('pinjam_to') }}" class="block w-full border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm px-3 py-2 rounded-lg">
        </div>
        <div class="flex justify-start md:justify-end">
            <button type="submit" class="inline-flex items-center justify-center h-12 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 rounded-lg text-sm font-semibold">Cari</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-5 py-3">Peminjam</th>
                <th class="text-left px-5 py-3">Email</th>
                <th class="text-left px-5 py-3">Barang</th>
                <th class="text-left px-5 py-3">Jumlah</th>
                <th class="text-left px-5 py-3 align-middle whitespace-nowrap">Tgl Pinjam</th>
                <th class="text-left px-5 py-3 align-middle whitespace-nowrap">Tgl Kembali</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="text-right px-5 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($borrowings as $b)
                <tr class="hover:bg-gray-50/70">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $b->nama_peminjam }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $b->processedBy?->email ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-500">
                        @foreach ($b->details as $d)
                            <div>{{ $d->product?->nama_barang ?? '-' }}</div>
                        @endforeach
                    </td>
                    <td class="px-5 py-3 text-gray-500">
                        @foreach ($b->details as $d)
                            <div>{{ $d->jumlah }}</div>
                        @endforeach
                    </td>
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap align-middle">{{ $b->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap align-middle">{{ $b->tanggal_kembali?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td class="px-5 py-3">
                        @php $scol = $b->status === 'dipinjam' ? 'red' : ($b->status === 'dikembalikan' ? 'green' : ($b->status === 'terlambat' ? 'yellow' : 'gray')); @endphp
                        <x-badge :color="$scol">{{ ucfirst($b->status) }}</x-badge>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex justify-end gap-3 text-xs font-semibold">
                            <a href="{{ route('borrowings.show', $b) }}" class="text-gray-500 hover:text-gray-700">Detail</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">Belum ada data riwayat dari akun staff.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-5 flex items-center justify-between">
    <div class="text-sm text-gray-500">Halaman {{ $borrowings->currentPage() }} dari {{ $borrowings->lastPage() }}</div>
    <div>{{ $borrowings->links() }}</div>
</div>
@endsection
