@extends('layouts.app')
@section('title','500 — Server Error')
@section('content')
<div class="pt-24 max-w-2xl mx-auto px-6 py-32 text-center">
  <div class="text-7xl mb-6">️</div>
  <h1 class="text-4xl font-extrabold text-navy-deep mb-3">500</h1>
  <p class="text-xl text-gray-500 mb-8">Terjadi kesalahan server. Kami sedang memperbaikinya.</p>
  <a href="{{ route('dashboard') }}" class="inline-block bg-gold text-navy-deep font-bold px-6 py-3 rounded-xl hover:bg-gold-light transition-all text-sm">Kembali ke Beranda</a>
</div>
@endsection
