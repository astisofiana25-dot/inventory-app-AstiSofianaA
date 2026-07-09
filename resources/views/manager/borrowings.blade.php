@extends('layouts.app')
@section('title', 'Semua Peminjaman')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <!-- Title shown in topbar; removed duplicate heading here -->
    <form method="GET" class="flex items-center gap-3 mb-4">
        <div>
            <select name="status" aria-label="Status filter" style="min-width:12rem;padding:0.28rem 0.6rem;font-size:0.9rem;color:#111;background:#fff;border:1px solid #e5e7eb;border-radius:0.375rem;">
                <option value="" {{ empty($status) ? 'selected' : '' }}>Semua</option>
                <option value="dipinjam" {{ ($status ?? '') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ ($status ?? '') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="bg-brand-600 text-white rounded-md text-sm" style="min-height:2rem; padding:0.38rem 0.9rem; line-height:1.25;">Terapkan</button>
        </div>
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
