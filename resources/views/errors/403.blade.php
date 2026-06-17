@extends('layouts.app')
@section('title','403 — Akses Ditolak')
@section('content')
<div class="pt-24 max-w-2xl mx-auto px-6 py-32 text-center">
  <div class="text-7xl mb-6"></div>
  <h1 class="text-4xl font-extrabold text-navy-deep mb-3">403</h1>
  <p class="text-xl text-gray-500 mb-8">{{ $exception->getMessage() ?: 'Kamu tidak memiliki akses ke halaman ini.' }}</p>
  <a href="{{ route('dashboard') }}" class="inline-block bg-gold text-navy-deep font-bold px-6 py-3 rounded-xl hover:bg-gold-light transition-all text-sm">Kembali ke Beranda</a>
</div>
@endsection
