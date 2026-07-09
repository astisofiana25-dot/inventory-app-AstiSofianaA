@extends('layouts.app')
@section('title', 'Edit Barang')
@section('content')
<div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm card-lift p-6">
    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')
        @if ($errors->any())
            <div class="rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                <strong class="font-semibold">Terjadi kesalahan:</strong>
                <ul class="mt-2 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @include('products._form')
        <div class="flex gap-3 pt-2">
            <button class="bg-brand-600 hover:bg-brand-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-lg shadow-brand-600/25">Perbarui Barang</button>
            <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-500 hover:bg-gray-100">Batal</a>
        </div>
    </form>
</div>
@endsection
