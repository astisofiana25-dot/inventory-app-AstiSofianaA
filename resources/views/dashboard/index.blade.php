@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="space-y-6">
    <div class="grid grid-cols-1 {{ auth()->user()->hasRole('admin') ? 'md:grid-cols-4 xl:grid-cols-4' : 'md:grid-cols-3 xl:grid-cols-3' }} gap-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-5 h-full flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Total Barang</span>
                <span class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l9-4 9 4M12 3v18M3 7.5v9a2 2 0 002 2h14a2 2 0 002-2v-9M3 12.5l9 4 9-4" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalBarang) }}</p>
            <p class="text-sm text-gray-500 mt-1">Jumlah stok keseluruhan</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-5 h-full flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Barang Dipinjam</span>
                <span class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 11a8 8 0 0112.9-6.3L20 8m0 0v-4h-4M20 13a8 8 0 01-12.9 6.3L4 16m0 0v4h4" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($barangDipinjam) }}</p>
            <p class="text-sm text-gray-500 mt-1">Saat ini sedang dipinjam</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-5 h-full flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Barang Tersedia</span>
                <span class="w-10 h-10 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 12a8 8 0 1116 0 8 8 0 01-16 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-gray-900">{{ number_format($barangTersedia) }}</p>
            <p class="text-sm text-gray-500 mt-1">Siap dipinjam kembali</p>
        </div>

        @if(auth()->user()->hasRole('admin'))
        <div class="bg-red-600 rounded-2xl border border-red-700 shadow-sm card-lift p-5 text-white h-full flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-white/90">Total Pengguna</span>
                <span class="w-10 h-10 rounded-2xl bg-white/10 text-white flex items-center justify-center text-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20H4v-2a4 4 0 014-4h1" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-white">{{ number_format($totalUsers) }}</p>
            <p class="text-sm text-white/80 mt-1">Jumlah pengguna terdaftar</p>
        </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-5">
        <form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 lg:grid-cols-[1fr_1fr_auto] gap-3 items-end">
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Pilih tahun</label>
                <select id="year" name="year" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500">
                    @for ($i = now()->year; $i >= now()->year - 3; $i--)
                        <option value="{{ $i }}" {{ ($selectedYear ?? now()->year) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Pilih bulan</label>
                <select id="month" name="month" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500">
                    <option value="all" {{ ($selectedMonth ?? 'all') === 'all' ? 'selected' : '' }}>Semua</option>
                    @for ($m = 1; $m <= 12; $m++)
                        @php $value = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                        @php $monthValue = ($selectedYear ?? now()->year) . '-' . $value; @endphp
                        <option value="{{ $monthValue }}" {{ ($selectedMonth ?? '') === $monthValue ? 'selected' : '' }}>{{ \Carbon\Carbon::create(2000, $m, 1)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-700">Terapkan</button>
        </form>

        <div class="mt-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">Grafik Peminjaman per Bulan</h3>
                            <p class="text-sm text-gray-500">Pilih tahun dan bulan, atau pilih Semua untuk melihat seluruh data dalam satu tahun.</p>
                        </div>
                    </div>
                    <canvas id="borrowChart" height="260"></canvas>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h4 class="font-semibold text-gray-900 mb-2">Status Peminjaman</h4>
                        <p class="text-sm text-gray-500 mb-4">Status peminjaman saat ini.</p>
                        <div class="flex items-center gap-4">
                            <div class="w-48 h-48 flex items-center justify-center">
                                <canvas id="statusDonut" class="w-full h-full"></canvas>
                            </div>
                                <div class="text-sm">
                                    <div class="flex items-start gap-2 mb-3">
                                        <span class="w-3 h-3 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></span>
                                        <div>
                                            <div class="text-gray-700">Dipinjam</div>
                                            <div class="text-gray-400 text-xs">{{ $totalDipinjam }} data</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-2 mb-3">
                                        <span class="w-3 h-3 rounded-full bg-green-500 mt-1.5 flex-shrink-0"></span>
                                        <div>
                                            <div class="text-gray-700">Dikembalikan</div>
                                            <div class="text-gray-400 text-xs">{{ $totalReturned }} data</div>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-2">
                                        <span class="w-3 h-3 rounded-full bg-amber-400 mt-1.5 flex-shrink-0"></span>
                                        <div>
                                            <div class="text-gray-700">Terlambat</div>
                                            <div class="text-gray-400 text-xs">{{ $totalLate }} data</div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h4 class="font-semibold text-gray-900 mb-2">Kategori Barang</h4>
                        <p class="text-sm text-gray-500 mb-4">Jumlah produk per kategori.</p>
                        <div class="w-full h-56 flex items-center">
                            <canvas id="categoryBar" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const chartLabels = @json($chartLabels);
    const chartData = @json($borrowingsData);

    const ctx = document.getElementById('borrowChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: chartData,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.12)',
                tension: 0.35,
                fill: true,
                pointRadius: 3,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

        // Status Donut
        const statusLabels = ['Dipinjam', 'Dikembalikan', 'Terlambat'];
        const statusData = @json([$totalDipinjam ?? 0, $totalReturned ?? 0, $totalLate ?? 0]);
        const ctxDonut = document.getElementById('statusDonut');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['#2563eb', '#16a34a', '#f59e0b'],
                    hoverOffset: 6,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        // Category Bar
        const categoryLabels = @json($categoryLabels);
        const categoryData = @json($categoryData);
        const ctxCategory = document.getElementById('categoryBar');
        new Chart(ctxCategory, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Jumlah Produk',
                    data: categoryData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
</script>
@endsection
