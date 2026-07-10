@extends('layouts.app')
@section('title', 'Kategori Barang')
@section('content')

<div x-data="{ isOpen: false, editMode: false, formId: null, formName: '' }" class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <form action="{{ route('categories.index') }}" method="GET" class="flex flex-1 gap-2 max-w-xl">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kategori..."
                   class="flex-1 border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm rounded-xl px-4 py-2" />

            <select name="category_id" class="border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm rounded-xl px-3 py-2">
                <option value="">Semua Kategori</option>
                @foreach($categoryOptions as $option)
                    <option value="{{ $option->id }}" @selected(request('category_id') == $option->id)>{{ sprintf('KTG-%03d', $option->id) }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 rounded-xl text-sm font-medium">Cari</button>
        </form>

        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.categories.export.excel', request()->only(['q','category_id'])) }}" class="text-sm bg-green-100 hover:bg-green-200 text-green-900 px-3 py-2 rounded-xl font-medium">XLSX</a>
                <a href="{{ route('reports.categories.export.pdf', request()->only(['q','category_id'])) }}" class="text-sm bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-xl font-medium">PDF</a>
                @if(auth()->user()->hasRole('admin'))
                <button type="button" @click="isOpen = true; editMode = false; formId = null; formName = ''"
                        class="inline-flex items-center justify-center rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-600/25 hover:bg-brand-700 transition">
                    + Tambah Kategori
                </button>
                @endif
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Kode Kategori</th>
                    <th class="text-left px-5 py-3">Nama Kategori</th>
                    <th class="text-left px-5 py-3">Jumlah</th>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                        <th class="text-right px-5 py-3">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($categories as $cat)
                    <tr class="hover:bg-gray-50/70">
                        <td class="px-5 py-3 text-gray-800">{{ sprintf('KTG-%03d', $cat->id) }}</td>
                        <td class="px-5 py-3 text-gray-800">{{ $cat->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $cat->products_count }}</td>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                                    <a href="{{ route('categories.show', $cat) }}" class="text-sm text-gray-600 hover:underline">Detail</a>
                                @endif

                                @if(auth()->user()->hasRole('admin'))
                                    <button type="button" @click="isOpen = true; editMode = true; formId = {{ $cat->id }}; formName = '{{ addslashes($cat->name) }}'"
                                            class="text-brand-600 hover:text-brand-800 text-xs font-semibold">Edit</button>

                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-10 text-center text-gray-400">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $categories->links() }}</div>
    </div>

    <div x-show="isOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click.away="isOpen = false" class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-bold text-gray-900" x-text="editMode ? 'Edit Kategori' : 'Tambah Kategori'"></h2>
                    <p class="text-sm text-gray-500" x-text="editMode ? 'Ubah nama kategori.' : 'Masukkan nama kategori baru.'"></p>
                </div>
                <button type="button" @click="isOpen = false" class="text-gray-500 hover:text-gray-900 text-3xl leading-none">×</button>
            </div>

            <form method="POST" x-bind:action="editMode ? '{{ url('categories') }}/' + formId : '{{ route('categories.store') }}'" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input x-model="formName" type="text" name="name" required placeholder="Nama kategori"
                       class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm focus:border-brand-500 focus:ring-brand-500 focus:outline-none">
                <div class="flex justify-end gap-3">
                    <button type="submit" class="rounded-2xl bg-brand-600 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-700" x-text="editMode ? 'Perbarui' : 'Simpan'"></button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
