@extends('layouts.app')
@section('title', 'Notifikasi')
@section('content')

<div class="space-y-4">
    <div class="flex items-center justify-end">
        <form method="POST" action="{{ route('notifications.read_all') }}">
            @csrf
            <button type="submit" class="text-sm text-brand-600 hover:text-brand-700 font-semibold">Tandai Semua Dibaca</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @forelse ($notifications as $notification)
            <div class="px-5 py-4 {{ $notification->read_at ? 'bg-white' : 'bg-brand-50' }} border-b border-gray-100">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $notification->title }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $notification->message }}</p>
                        {{-- notification data hidden from UI --}}
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400">{{ $notification->created_at->translatedFormat('d M Y H:i') }}</p>
                        @unless($notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                <button type="submit" class="mt-2 text-xs text-brand-600 font-semibold">Tandai dibaca</button>
                            </form>
                        @endunless
                    </div>
                </div>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-gray-500">Belum ada notifikasi.</div>
        @endforelse
    </div>

    <div>{{ $notifications->links() }}</div>
</div>

@endsection
