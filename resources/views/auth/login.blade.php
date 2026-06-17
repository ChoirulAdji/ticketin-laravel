@extends('layouts.auth')
@section('title','TicketIn — Masuk')
@section('auth-switch')
  Belum punya akun? <a href="{{ route('register') }}" class="text-gold font-semibold hover:underline">Daftar</a>
@endsection
@section('auth-switch-mobile')
  <a href="{{ route('register') }}" class="text-gold font-semibold text-sm">Daftar Sekarang →</a>
@endsection

@section('content')
<div class="w-full max-w-md card-enter">
  <div class="glass-card rounded-2xl p-8 md:p-10">

    @if($errors->any())
      <div class="bg-red-500/20 border border-red-500 text-red-200 p-3 rounded-lg mb-6 text-sm text-center" id="error-box">
        {{ $errors->first() }}
      </div>
    @endif

    <!-- Header -->
    <div class="text-center mb-8">
      <div class="w-14 h-14 bg-gold rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-gold/30">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-navy-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
        </svg>
      </div>
      <h1 class="text-2xl font-extrabold text-white">Selamat Datang Kembali!</h1>
      <p class="text-white/50 text-sm mt-1">Masuk ke akun TicketIn kamu</p>
    </div>

    <!-- Form -->
    <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-5">
      @csrf

      <!-- Email -->
      <div>
        <label class="block text-white/70 text-xs font-semibold mb-1.5 tracking-wide uppercase">Email</label>
        <div class="input-group">
          <svg xmlns="http://www.w3.org/2000/svg" class="input-icon w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
          </svg>
          <input type="email" id="email" name="email" value="{{ old('email') }}"
                 placeholder="email@kamu.com"
                 class="input-field w-full rounded-xl pl-10 pr-4 py-3 text-sm" autocomplete="email"/>
        </div>
      </div>

      <!-- Password -->
      <div>
        <label class="block text-white/70 text-xs font-semibold mb-1.5 tracking-wide uppercase">Password</label>
        <div class="input-group">
          <svg xmlns="http://www.w3.org/2000/svg" class="input-icon w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
          <input type="password" id="password" name="password"
                 placeholder="Masukkan password"
                 class="input-field w-full rounded-xl pl-10 pr-10 py-3 text-sm" autocomplete="current-password"/>
          <button type="button" class="toggle-pass" onclick="togglePass()">
            <svg id="eye-show" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg id="eye-hide" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Remember -->
      <div class="flex items-center justify-between">
        <label class="flex items-center gap-2 cursor-pointer select-none">
          <input type="checkbox" name="remember" class="w-4 h-4 accent-gold rounded">
          <span class="text-white/60 text-sm">Ingat saya</span>
        </label>
        <a href="#" class="text-gold text-sm font-semibold hover:text-gold-light hover:underline transition-colors">Lupa password?</a>
      </div>

      <!-- Submit -->
      <button type="submit" name="login" class="btn-login w-full rounded-xl py-3.5 text-sm tracking-wide mt-2">
        <span>Masuk ke TicketIn</span>
      </button>
    </form>

    <!-- Divider -->
    <div class="flex items-center gap-3 my-6 text-white/30 text-xs">
      <div class="flex-1 h-px bg-white/10"></div>
      atau
      <div class="flex-1 h-px bg-white/10"></div>
    </div>

    <p class="text-center text-white/50 text-sm">
      Belum punya akun?
      <a href="{{ route('register') }}" class="text-gold font-semibold hover:text-gold-light hover:underline transition-colors ml-1">Daftar sekarang</a>
    </p>
  </div>
</div>

<!-- Success overlay -->
@if(session('login_success'))
<div id="success-overlay" class="success-overlay show">
  <div class="success-circle">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-navy-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
  </div>
  <h2 class="text-2xl font-bold text-white mt-4">Berhasil Masuk!</h2>
  <p class="text-white/60 text-sm">Mengalihkan ke dashboard...</p>
</div>
@endif
@endsection

@push('scripts')
<script>
  function togglePass() {
    const inp = document.getElementById('password');
    const es  = document.getElementById('eye-show');
    const eh  = document.getElementById('eye-hide');
    inp.type  = inp.type === 'password' ? 'text' : 'password';
    es.classList.toggle('hidden');
    eh.classList.toggle('hidden');
  }
</script>
@endpush
