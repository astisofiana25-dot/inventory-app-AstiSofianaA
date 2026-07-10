@extends('layouts.app')
@section('title', 'Semua Peminjaman')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <!-- Title shown in topbar; removed duplicate heading here -->
    <form method="GET" class="flex flex-col md:flex-row md:items-center gap-3 mb-4">
        <div class="w-full md:w-auto">
            <select name="status" aria-label="Status filter" class="w-full md:w-48 border border-gray-300 rounded-md px-2.5 py-2 text-sm text-gray-900 bg-white focus:border-brand-500 focus:ring-brand-500 shadow-sm">
                <option value="" {{ empty($status) ? 'selected' : '' }}>Semua</option>
                <option value="dipinjam" {{ ($status ?? '') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ ($status ?? '') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>
        <button type="submit" class="w-full md:w-auto bg-brand-600 hover:bg-brand-700 text-white rounded-md px-3.5 py-2 text-sm font-semibold">Terapkan</button>
    </form>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600">
                    <th class="py-2">Kode</th>
                    <th class="py-2">Nama Barang</th>
                    <th class="py-2">Peminjam</th>
                    <th class="py-2">Tanggal Pinjam</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Tanggal Kembali</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                    @foreach($b->details as $detail)
                    <tr class="border-t">
                        <td class="py-3">{{ $detail->product->kode_barang ?? '-' }}</td>
                        <td class="py-3">{{ $detail->product->nama_barang ?? '-' }}</td>
                        <td class="py-3">{{ $b->nama_peminjam }}</td>
                        <td class="py-3">{{ optional($b->tanggal_pinjam)->format('d M Y') }}</td>
                        <td class="py-3">
                            @php
                                $color = $b->status_display === 'dipinjam' ? 'red' : ($b->status_display === 'dikembalikan' ? 'green' : ($b->status_display === 'terlambat' ? 'red' : 'gray'));
                            @endphp
                            <x-badge :color="$color">{{ ucfirst($b->status_display) }}</x-badge>
                        </td>
                        <td class="py-3">{{ optional($b->tanggal_kembali_aktual ?? $b->tanggal_kembali)->format('d M Y') ?? '-' }}</td>
                    </tr>
                    @endforeach
                @empty
                <tr><td colspan="6" class="py-6 text-center text-gray-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $borrowings->appends(request()->query())->links() }}
    </div>
</div>
    
@endsection
