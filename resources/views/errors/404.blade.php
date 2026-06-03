@extends('layouts.app')
@section('title','404 — Halaman Tidak Ditemukan')
@section('content')
<div class="pt-24 max-w-2xl mx-auto px-6 py-32 text-center">
  <div class="text-7xl mb-6">🔍</div>
  <h1 class="text-4xl font-extrabold text-navy-deep mb-3">404</h1>
  <p class="text-xl text-gray-500 mb-8">Halaman yang kamu cari tidak ada atau sudah dipindah.</p>
  <div class="flex gap-3 justify-center">
    <a href="{{ route('dashboard') }}" class="bg-gold text-navy-deep font-bold px-6 py-3 rounded-xl hover:bg-gold-light transition-all text-sm">Beranda</a>
    <a href="{{ route('events.index') }}" class="bg-navy-mid text-white font-bold px-6 py-3 rounded-xl hover:bg-navy-deep transition-all text-sm">Lihat Event</a>
  </div>
</div>
@endsection
