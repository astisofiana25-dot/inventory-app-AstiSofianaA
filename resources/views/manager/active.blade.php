@extends('layouts.app')
@section('title', 'Peminjam Aktif')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <!-- Heading shown in topbar; removed duplicate heading here -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600">
                    <th class="py-2">Nama</th>
                    <th class="py-2">Email</th>
                    <th class="py-2">Jumlah Pinjaman</th>
                    <th class="py-2">Terakhir Pinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($active as $a)
                <tr class="border-t">
                    <td class="py-3">{{ $a->nama_peminjam }}</td>
                    <td class="py-3">-</td>
                    <td class="py-3">{{ $a->total }}</td>
                    <td class="py-3">-</td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-6 text-center text-gray-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $active->links() }}
    </div>
</div>
@endsection
