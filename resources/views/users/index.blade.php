@extends('layouts.app')
@section('title', 'Daftar Pengguna')
@section('content')

<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <form action="{{ route('users.index') }}" method="GET" class="flex flex-1 gap-2 max-w-xl">
            <input
                id="q"
                name="q"
                type="text"
                value="{{ request('q') }}"
                placeholder="Cari nama, email, atau ID"
                class="flex-1 border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm rounded-xl px-4 py-2"
            />

            <select id="role" name="role" class="border-gray-300 focus:border-brand-500 focus:ring-brand-500 shadow-sm text-sm rounded-xl px-3 py-2">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @selected(request('role') == $role->id)>{{ $role->label ?? $role->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 rounded-xl text-sm font-medium">
                Cari
            </button>
        </form>

        @if(auth()->user()->hasRole('admin'))
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.users.export.excel', request()->only(['q','role'])) }}" class="text-sm bg-green-100 hover:bg-green-200 text-green-900 px-3 py-2 rounded-xl font-medium">XLSX</a>
                <a href="{{ route('reports.users.export.pdf', request()->only(['q','role'])) }}" class="text-sm bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-xl font-medium">PDF</a>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm card-lift overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Nama</th>
                    <th class="text-left px-5 py-3">ID Karyawan</th>
                    <th class="text-left px-5 py-3">Email</th>
                    <th class="text-left px-5 py-3">Role</th>
                    <th class="text-right px-5 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50/70">
                        <td class="px-5 py-3 text-gray-800">{{ $user->name }}</td>
                        <td class="px-5 py-3 text-gray-800">{{ $user->employee_id ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-800">{{ $user->email }}</td>
                        <td class="px-5 py-3 text-gray-800">{{ $user->role?->name ?? 'N/A' }}</td>
                        <td class="px-5 py-3 text-right">
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada pengguna terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">{{ $users->links() }}</div>
    </div>
</div>

@endsection
