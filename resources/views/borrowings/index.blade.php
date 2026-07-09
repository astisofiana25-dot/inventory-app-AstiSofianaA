@extends('layouts.app')
@section('title', 'Peminjaman Barang')
@section('content')

<div class="flex items-center mb-6 flex-nowrap overflow-x-auto pb-1">
    <form method="GET" class="flex gap-2 items-center flex-auto">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama..."
               class="w-40 border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm px-3 py-1.5">
        <select name="status" class="border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm px-2 py-1.5">
            <option value="">Status</option>
            <option value="dipinjam" @selected(request('status')=='dipinjam')>Dipinjam</option>
            <option value="dikembalikan" @selected(request('status')=='dikembalikan')>Dikembalikan</option>
        </select>
        <input type="date" name="from" value="{{ request('from') }}" class="border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm px-2 py-1.5 w-32">
        <input type="date" name="to" value="{{ request('to') }}" class="border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm px-2 py-1.5 w-32">
        <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 h-10 rounded-lg text-sm font-medium whitespace-nowrap">Cari</button>
    </form>

    <div class="flex gap-2 items-center ml-6">
        @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
        <a href="{{ route('reports.borrowings.export.xlsx', request()->only(['q','status','from','to','product_id'])) }}" class="text-sm bg-green-100 hover:bg-green-200 text-green-900 px-2.5 py-1.5 rounded-lg font-medium whitespace-nowrap">XLSX</a>
        <a href="{{ route('reports.borrowings.export.pdf', request()->only(['q','status','from','to','product_id'])) }}" class="text-sm bg-red-100 hover:bg-red-200 text-red-700 px-2.5 py-1.5 rounded-lg font-medium whitespace-nowrap">PDF</a>
        @endif

        @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff'))
        <a href="{{ route('borrowings.create') }}" class="bg-brand-600 hover:bg-brand-700 text-white px-3 py-1.5 rounded-lg text-sm font-semibold shadow-lg shadow-brand-600/25 whitespace-nowrap ml-auto">
            + Catat
        </a>
        @endif
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-5 py-3">Peminjam</th>
                @if (!auth()->user()->hasRole('staff'))
                    <th class="text-left px-5 py-3">Email</th>
                @endif
                <th class="text-left px-5 py-3">Barang</th>
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
                    @if (!auth()->user()->hasRole('staff'))
                        <td class="px-5 py-3 text-gray-500">{{ $b->processedBy?->email ?? '-' }}</td>
                    @endif
                    <td class="px-5 py-3 text-gray-500">{{ $b->details->pluck('product.nama_barang')->join(', ') }}</td>
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap align-middle">{{ $b->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap align-middle">{{ $b->tanggal_kembali?->translatedFormat('d M Y') ?? '-' }}</td>
                    <td class="px-5 py-3">
                        @php
                            $statusColor = $b->status === 'dipinjam' ? 'red' : ($b->status === 'dikembalikan' ? 'green' : ($b->status === 'terlambat' ? 'yellow' : 'gray'));
                        @endphp
                        <x-badge :color="$statusColor">{{ ucfirst($b->status) }}</x-badge>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex justify-end gap-3 text-xs font-semibold">
                            <a href="{{ route('borrowings.show', $b) }}" class="text-gray-500 hover:text-gray-700">Detail</a>
                            @if (($b->status === 'dipinjam') && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('staff')))
                                <a href="{{ route('borrowings.return.form', $b) }}" class="text-brand-600 hover:text-brand-800">Kembalikan</a>
                            @endif
                            @if (auth()->user()->hasRole('admin'))
                                <form action="{{ route('borrowings.destroy', $b) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data peminjaman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada data peminjaman.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-5">{{ $borrowings->links() }}</div>
@endsection
