@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-6">
    @php
        $isLate = false;
        $lateDays = 0;
        if ($borrowing->tanggal_kembali) {
            $due = \Carbon\Carbon::parse($borrowing->tanggal_kembali);
            if ($borrowing->status === 'terlambat') {
                $isLate = true;
                if ($borrowing->tanggal_kembali_aktual) {
                    $lateDays = \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->diffInDays($due);
                } else {
                    $lateDays = \Carbon\Carbon::now()->diffInDays($due);
                }
            } elseif ($borrowing->status === 'dipinjam' && $due->isPast()) {
                $isLate = true;
                $lateDays = \Carbon\Carbon::now()->diffInDays($due);
            } elseif ($borrowing->status === 'dikembalikan' && $borrowing->tanggal_kembali_aktual && \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->gt($due)) {
                $isLate = true;
                $lateDays = \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->diffInDays($due);
            }
        }
    @endphp

    <div class="flex items-start justify-between mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900">{{ $borrowing->nama_peminjam }}</h2>
            <p class="text-sm text-gray-400">Dicatat oleh {{ $borrowing->processedBy->name ?? '-' }}</p>
        </div>
        @php
            $mainColor = $borrowing->status_display === 'dipinjam' ? 'brand' : ($borrowing->status_display === 'dikembalikan' ? 'green' : ($borrowing->status_display === 'terlambat' ? 'yellow' : 'gray'));
        @endphp
        <x-badge :color="$mainColor" :small="false">{{ ucfirst($borrowing->status_display) }}</x-badge>
        @if($isLate)
            <x-badge color="red" class="ml-3">Terlambat {{ $lateDays }} hari</x-badge>
        @endif
    </div>

    <dl class="grid grid-cols-2 gap-y-3 text-sm mb-6">
        <dt class="text-gray-400">Tanggal Pinjam</dt><dd class="font-medium text-gray-700">{{ $borrowing->tanggal_pinjam->translatedFormat('d M Y') }}</dd>
        <dt class="text-gray-400">Rencana Kembali</dt><dd class="font-medium text-gray-700">{{ $borrowing->tanggal_kembali?->translatedFormat('d M Y') ?? '-' }}</dd>
        <dt class="text-gray-400">Tanggal Kembali Aktual</dt><dd class="font-medium text-gray-700">{{ $borrowing->tanggal_kembali_aktual?->translatedFormat('d M Y') ?? '-' }}</dd>
        <dt class="text-gray-400">Keterangan</dt><dd class="font-medium text-gray-700">{{ $borrowing->keterangan ?? '-' }}</dd>
        <dt class="text-gray-400">Status Kondisi</dt>
        <dd class="font-medium text-gray-700">{{ $borrowing->details->pluck('kondisi_saat_kembali')->filter()->unique()->count() === 1 ? ucfirst($borrowing->details->first()->kondisi_saat_kembali) : ($borrowing->details->pluck('kondisi_saat_kembali')->filter()->isEmpty() ? '-' : 'Beragam') }}</dd>
    </dl>

    <h3 class="font-bold text-gray-900 mb-3">Barang Dipinjam</h3>
    <div class="divide-y divide-gray-100 border border-gray-100 rounded-xl overflow-hidden">
        @foreach ($borrowing->details as $detail)
            <div class="px-4 py-3 text-sm">
                <div class="mb-3">
                    <p class="text-gray-700 font-semibold">{{ $detail->product->nama_barang }}</p>
                    <p class="text-xs text-gray-400">x{{ $detail->jumlah }}</p>
                </div>

                @if(!empty($detail->photos) && is_array($detail->photos))
                <div class="grid grid-cols-2 gap-3 mb-3">
                    @foreach($detail->photos as $photo)
                        <img
                            src="{{ $photo }}"
                            alt="Foto pengembalian {{ $detail->product->nama_barang }}"
                            class="w-full h-40 object-cover rounded-lg border border-gray-200"
                        />
                    @endforeach
                </div>
            @endif

                @if(empty($detail->photos))
                    <div class="text-xs text-gray-400">Belum ada foto pengembalian.</div>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
