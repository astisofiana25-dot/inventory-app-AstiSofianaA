@extends('layouts.guest')
@section('title', 'Memproses keluar')
@section('content')
<script>
    setTimeout(function () {
        window.location.href = '{{ route('login', ['from_logout' => 1]) }}';
    }, 1500);
</script>
@endsection
