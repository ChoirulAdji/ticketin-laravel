@extends('layouts.auth')
@section('title', 'TicketIn — Daftar')
@section('auth-switch')
  Sudah punya akun? <a href="{{ route('login') }}" class="text-gold font-semibold hover:underline">Masuk</a>
@endsection
@section('auth-switch-mobile')
  <a href="{{ route('login') }}" class="text-gold font-semibold text-sm">Masuk →</a>
@endsection

@push('styles')
<style>
  .strength-bar { height:4px; border-radius:99px; flex:1; background:rgba(255,255,255,.1); transition:background .4s; }
  .strength-bar.weak { background:#ef4444; }
  .strength-bar.medium { background:#f59e0b; }
  .strength-bar.strong { background:#22c55e; }
  .card-enter { animation:cardEnter .6s cubic-bezier(.34,1.56,.64,1) forwards; }
  @keyframes cardEnter { from{opacity:0;transform:translateY(30px) scale(.97);}to{opacity:1;transform:translateY(0) scale(1);} }
</style>
@endpush

@section('content')
<div class="w-full max-w-md card-enter">
  <div class="glass-card rounded-2xl p-8 md:p-10">

    <div class="text-center mb-7">
      <div class="w-14 h-14 bg-gold rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-gold/30">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-navy-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
        </svg>
      </div>
      <h1 class="text-2xl font-extrabold text-white">Buat Akun Baru</h1>
      <p class="text-white/50 text-sm mt-1">Bergabung dan temukan event favoritmu</p>
    </div>

    @if($errors->any())
    <div class="bg-red-500/20 border border-red-500 text-red-200 p-3 rounded-lg mb-5 text-sm">
      <ul class="space-y-1">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-white/70 text-xs font-semibold mb-1.5 tracking-wide uppercase">Nama Lengkap</label>
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          <input type="text" name="nama" value="{{ old('nama') }}" required
                 placeholder="Nama lengkapmu"
                 class="input-field w-full rounded-xl pl-10 pr-4 py-3 text-sm" autocomplete="name"/>
        </div>
      </div>

      <div>
        <label class="block text-white/70 text-xs font-semibold mb-1.5 tracking-wide uppercase">Email</label>
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
          <input type="email" name="email" value="{{ old('email') }}" required
                 placeholder="email@kamu.com"
                 class="input-field w-full rounded-xl pl-10 pr-4 py-3 text-sm" autocomplete="email"/>
        </div>
      </div>

      <div>
        <label class="block text-white/70 text-xs font-semibold mb-1.5 tracking-wide uppercase">No. HP</label>
        <div class="flex">
          <div class="flex items-center px-3 text-white/60 text-sm font-semibold flex-shrink-0"
               style="background:rgba(255,255,255,.06);border:1.5px solid rgba(255,255,255,.12);border-right:none;border-radius:12px 0 0 12px;">
             +62
          </div>
          <input type="tel" name="no_hp" value="{{ old('no_hp') }}"
                 placeholder="812 3456 7890"
                 class="input-field flex-1 py-3 px-4 text-sm"
                 style="border-radius:0 12px 12px 0;" autocomplete="tel"/>
        </div>
      </div>

      <div>
        <label class="block text-white/70 text-xs font-semibold mb-1.5 tracking-wide uppercase">Password</label>
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
          <input type="password" name="password" id="reg-pass" required
                 placeholder="Minimal 8 karakter"
                 class="input-field w-full rounded-xl pl-10 pr-10 py-3 text-sm"
                 autocomplete="new-password" oninput="checkStrength()"/>
          <button type="button" onclick="togglePw()" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/30 hover:text-white/60">
            <svg id="eye-s" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <svg id="eye-h" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
          </button>
        </div>
        <div class="flex gap-1 mt-2">
          <div class="strength-bar" id="bar1"></div>
          <div class="strength-bar" id="bar2"></div>
          <div class="strength-bar" id="bar3"></div>
          <div class="strength-bar" id="bar4"></div>
        </div>
      </div>

      <div>
        <label class="block text-white/70 text-xs font-semibold mb-1.5 tracking-wide uppercase">Konfirmasi Password</label>
        <div class="relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          <input type="password" name="password_confirmation" required
                 placeholder="Ulangi password"
                 class="input-field w-full rounded-xl pl-10 pr-4 py-3 text-sm"
                 autocomplete="new-password"/>
        </div>
      </div>

      <button type="submit" class="btn-login w-full rounded-xl py-3.5 text-sm tracking-wide mt-2">
         Buat Akun Sekarang
      </button>
    </form>

    {{-- Info jadi EO --}}
    <div class="mt-5 p-4 rounded-xl text-center" style="background:rgba(245,196,0,.08);border:1px solid rgba(245,196,0,.2);">
      <p class="text-white/60 text-xs">Ingin jadi Pengelola Event (EO)?</p>
      <p class="text-gold text-xs font-semibold mt-0.5">Daftar dulu, lalu ajukan verifikasi EO dari profil kamu </p>
    </div>

    <p class="text-center text-white/50 text-sm mt-5">
      Sudah punya akun?
      <a href="{{ route('login') }}" class="text-gold font-semibold hover:underline ml-1">Masuk di sini</a>
    </p>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function togglePw() {
    const inp = document.getElementById('reg-pass');
    document.getElementById('eye-s').classList.toggle('hidden');
    document.getElementById('eye-h').classList.toggle('hidden');
    inp.type = inp.type === 'password' ? 'text' : 'password';
  }
  function checkStrength() {
    const pass = document.getElementById('reg-pass').value;
    const bars = ['bar1','bar2','bar3','bar4'].map(id => document.getElementById(id));
    bars.forEach(b => b.className = 'strength-bar');
    if (pass.length > 0) bars[0].classList.add('weak');
    if (pass.length > 4) bars[1].classList.add('weak');
    if (pass.length > 6) { bars[0].className='strength-bar medium'; bars[1].className='strength-bar medium'; bars[2].classList.add('medium'); }
    if (pass.length >= 8 && /[a-zA-Z]/.test(pass) && /[0-9]/.test(pass)) bars.forEach(b => b.className='strength-bar strong');
  }
</script>
@endpush
