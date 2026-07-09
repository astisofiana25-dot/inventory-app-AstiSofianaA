@extends('layouts.app')
@section('title', 'Manager')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <!-- Heading shown in topbar; removed duplicate heading here -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-500">Barang Tersedia</p>
            <p class="text-2xl font-bold">{{ $counts['available'] ?? 0 }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-500">Barang Dipinjam</p>
            <p class="text-2xl font-bold">{{ $counts['borrowed'] ?? 0 }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-500">Barang Dikembalikan</p>
            <p class="text-2xl font-bold">{{ $counts['returned'] ?? 0 }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-500">Barang Terlambat</p>
            <p class="text-2xl font-bold">{{ $counts['late'] ?? 0 }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-500">Peminjam Aktif</p>
            <p class="text-2xl font-bold">{{ $counts['active_borrowers'] ?? 0 }}</p>
        </div>
    </div>
</div>
@endsection
