@extends('layouts.app')
@section('title', 'Barang Dikembalikan')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <!-- Heading shown in topbar; removed duplicate heading here -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600">
                    <th class="py-2">Kode</th>
                    <th class="py-2">Nama Barang</th>
                    <th class="py-2">Peminjam</th>
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
                    <td class="py-3">{{ optional($b->tanggal_kembali_aktual)->format('d M Y') }}</td>
                </tr>
                @endforeach
                @empty
                <tr><td colspan="4" class="py-6 text-center text-gray-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $borrowings->links() }}
    </div>
</div>
@endsection
