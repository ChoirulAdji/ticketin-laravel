<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'TicketIn — Temukan Event Terbaik')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            navy: { deep: '#001840', mid: '#102A71' },
            gold: { DEFAULT: '#F5C400', light: '#FFDC5F', badge: '#FFDF00' }
          },
          fontFamily: { poppins: ['Poppins', 'sans-serif'] }
        }
      }
    }
  </script>
  <style>
    * { font-family: 'Poppins', sans-serif; }
    .event-card { transition: transform 0.3s ease, box-shadow 0.3s ease; display: flex; flex-direction: column; text-decoration: none; }
    .event-card:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 20px 40px rgba(0,24,64,0.15); }
    a.event-card { display: flex !important; flex-direction: column !important; }
    .nav-link { position: relative; }
    .nav-link::after { content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px; background: #F5C400; transition: width 0.3s ease; }
    .nav-link:hover::after { width: 100%; }
    #mobile-menu { max-height: 0; overflow: hidden; transition: max-height 0.4s ease; }
    #mobile-menu.open { max-height: 400px; }
    .dot.active { background-color: #F5C400; transform: scale(1.3); }
    .dot { transition: all 0.3s; }
    .slider-track { display: flex; transition: transform 0.6s cubic-bezier(0.77,0,0.175,1); }
    .slide { min-width: 100%; position: relative; }
    @keyframes badgePulse { 0%,100%{box-shadow:0 0 0 0 rgba(245,196,0,0.5);}50%{box-shadow:0 0 0 6px rgba(245,196,0,0);} }
    .badge-popular { animation: badgePulse 2s infinite; }
    .footer-link { transition: color 0.2s, padding-left 0.2s; }
    .footer-link:hover { color: #F5C400; padding-left: 4px; }
    .fade-in { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
    .fade-in.visible { opacity: 1; transform: none; }
    @stack('styles')
  </style>
  @stack('styles')
</head>
<body class="bg-gray-50 font-poppins">

  <!-- NAVBAR -->
  <header class="fixed top-0 left-0 w-full bg-navy-mid text-white shadow-lg z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

      <!-- Logo -->
      <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
        <div class="w-8 h-8 bg-gold rounded-lg flex items-center justify-center group-hover:bg-gold-light transition-all duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-navy-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
          </svg>
        </div>
        <span class="text-xl font-bold tracking-tight">TicketIn</span>
      </a>

      <!-- Desktop Nav -->
      <nav class="hidden md:flex gap-8">
        <a href="{{ route('dashboard') }}" class="nav-link hover:text-gold transition-colors duration-200 font-medium">Beranda</a>
        <a href="{{ route('events.index') }}" class="nav-link hover:text-gold transition-colors duration-200 font-medium">Event</a>
        <a href="{{ route('tentang') }}" class="nav-link hover:text-gold transition-colors duration-200 font-medium">Tentang</a>
        <a href="{{ route('hubungi') }}" class="nav-link hover:text-gold transition-colors duration-200 font-medium">Hubungi Kami</a>
      </nav>

      <!-- Actions -->
      <div class="flex items-center gap-4">
@auth
          <div class="hidden sm:block relative" id="user-dropdown-wrap">
            <button onclick="toggleDropdown()" class="flex items-center gap-2.5 bg-white/10 hover:bg-white/20 border border-white/10 px-3 py-1.5 rounded-full transition-colors duration-300 shadow-sm">
              <img src="{{ auth()->user()->avatar_url }}" class="w-7 h-7 rounded-full object-cover shadow-sm" alt="Avatar">
              <span class="text-sm font-semibold tracking-wide">{{ auth()->user()->nama_panggilan }}</span>
              <svg class="w-3.5 h-3.5 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div id="user-dropdown" class="hidden absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
              <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-mid transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profil Saya
              </a>
              @if(auth()->user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                👑 Admin Panel
              </a>
              @elseif(auth()->user()->isPengelola())
              <a href="{{ route('pengelola.dashboard') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-navy-mid transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Dashboard EO
              </a>
              @elseif(!auth()->user()->eoApplication)
              <a href="{{ route('eo.daftar') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-gold hover:bg-yellow-50 transition-colors font-semibold">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                🎭 Daftar Jadi EO
              </a>
              @else
              <a href="{{ route('eo.status') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-yellow-600 hover:bg-yellow-50 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                ⏳ Status Pengajuan EO
              </a>
              @endif
              <div class="border-t border-gray-100"></div>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition-colors">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                  Keluar
                </button>
              </form>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}" class="hidden sm:block">
            <button class="bg-gold text-navy-deep px-6 py-2 rounded-lg font-bold hover:bg-gold-light transition-all duration-300 hover:shadow-lg hover:shadow-gold/30">
              Masuk
            </button>
          </a>
        @endauth

        <!-- Hamburger -->
        <button id="menu-btn" class="md:hidden p-1" aria-label="Menu">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="bg-navy-mid border-t border-white/10">
      <nav class="flex flex-col px-6 py-4 gap-3">
        <a href="{{ route('dashboard') }}" class="text-gold font-medium">Beranda</a>
        <a href="{{ route('events.index') }}" class="hover:text-gold transition font-medium">Event</a>
        <a href="{{ route('tentang') }}" class="hover:text-gold transition font-medium">Tentang</a>
        <a href="{{ route('hubungi') }}" class="hover:text-gold transition font-medium">Hubungi Kami</a>
        @auth
          <hr class="border-white/10 my-2">
          <a href="{{ route('profile.index') }}" class="hover:text-gold transition font-medium">Profil Saya ({{ auth()->user()->nama_panggilan }})</a>
          @if(auth()->user()->isPengelola())
            <a href="{{ route('pengelola.dashboard') }}" class="hover:text-gold transition font-medium">Dashboard Pengelola</a>
          @endif
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-400 hover:text-red-300 transition font-medium">Keluar</button>
          </form>
        @else
          <hr class="border-white/10 my-2">
          <a href="{{ route('login') }}" class="text-gold hover:text-gold-light transition font-bold">Masuk / Daftar</a>
        @endauth
      </nav>
    </div>
  </header>


  <!-- TOAST NOTIFICATION -->
  @if(session('success') || session('error'))
  <div id="toast-notif"
       class="fixed top-24 right-6 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-sm font-semibold transition-all duration-500"
       style="min-width:280px; max-width:400px;
              background: {{ session('success') ? '#f0fdf4' : '#fef2f2' }};
              border: 1.5px solid {{ session('success') ? '#86efac' : '#fca5a5' }};
              color: {{ session('success') ? '#15803d' : '#dc2626' }};">
    <span class="text-xl flex-shrink-0">{{ session('success') ? '✅' : '❌' }}</span>
    <span class="flex-1">{{ session('success') ?? session('error') }}</span>
    <button onclick="tutupToast()" class="ml-2 opacity-50 hover:opacity-100 transition-opacity flex-shrink-0">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>
  <script>
    function tutupToast() {
      const t = document.getElementById('toast-notif');
      if (t) { t.style.opacity = '0'; t.style.transform = 'translateX(100%)'; setTimeout(() => t.remove(), 500); }
    }
    // Auto hide setelah 5 detik
    setTimeout(tutupToast, 5000);
  </script>
  @endif

  <!-- MAIN CONTENT -->
  <main>
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer id="kontak" class="bg-navy-deep text-white mt-10">
    <div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-10">

      <!-- Col 1: Brand + CS -->
      <div>
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 bg-gold rounded-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-navy-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
          </div>
          <span class="text-xl font-bold">TicketIn</span>
        </div>
        <p class="text-white/60 text-sm mb-5 leading-relaxed">Platform tiket event terpercaya untuk menemukan dan menikmati berbagai event terbaik di Indonesia.</p>
        <h4 class="font-semibold text-gold mb-3 text-sm uppercase tracking-wider">Customer Service</h4>
        <ul class="space-y-2 text-sm text-white/70">
          <li><a href="mailto:cs@ticketin.com" class="footer-link hover:text-gold">cs@ticketin.com</a></li>
          <li><a href="mailto:marketing@ticketin.com" class="footer-link hover:text-gold">marketing@ticketin.com</a></li>
          <li><a href="mailto:partnership@ticketin.com" class="footer-link hover:text-gold">partnership@ticketin.com</a></li>
        </ul>
      </div>

      <!-- Col 2: Sosial Media -->
      <div>
        <h4 class="font-semibold text-gold mb-4 text-sm uppercase tracking-wider">Sosial Media</h4>
        <ul class="space-y-3 text-sm">
          <li>
            <a href="#" class="flex items-center gap-3 text-white/70 footer-link group">
              <span class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-gold/20 transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              </span>
              Instagram @ticketin.id
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-3 text-white/70 footer-link group">
              <span class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-gold/20 transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
              </span>
              WhatsApp +62 812 3456 7890
            </a>
          </li>
          <li>
            <a href="#" class="flex items-center gap-3 text-white/70 footer-link group">
              <span class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-gold/20 transition-all">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </span>
              Facebook TicketIn Indonesia
            </a>
          </li>
        </ul>
      </div>

      <!-- Col 3: Pembayaran -->
      <div>
        <h4 class="font-semibold text-gold mb-4 text-sm uppercase tracking-wider">Metode Pembayaran</h4>
        <div class="flex flex-wrap items-center gap-4">
          <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" class="h-8 bg-white p-1 rounded" alt="BCA">
          <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" class="h-8 bg-white p-1 rounded" alt="Mandiri">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Bank_Negara_Indonesia_logo_%282004%29.svg/960px-Bank_Negara_Indonesia_logo_%282004%29.svg.png" class="h-8 bg-white p-1 rounded" alt="BNI">
          <img src="https://upload.wikimedia.org/wikipedia/commons/e/eb/Logo_ovo_purple.svg" class="h-8 bg-white p-1 rounded" alt="OVO">
          <img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" class="h-8 bg-white p-1 rounded" alt="GoPay">
          <img src="https://freepng.com/uploads/images/202512/uick-response-code-indonesia-standard-qris-logo-vector-png_1020x.jpg" class="h-8 bg-white p-0.5 rounded-md shadow-sm hover:scale-105 transition" alt="QRIS">
        </div>
      </div>
    </div>

    <div class="border-t border-navy-mid">
      <div class="max-w-7xl mx-auto px-6 py-5 flex flex-col md:flex-row items-center justify-between gap-2 text-sm text-white/50">
        <p>© 2026 TicketIn. All rights reserved.</p>
        <div class="flex gap-4">
          <a href="#" class="hover:text-gold transition-colors">Syarat & Ketentuan</a>
          <a href="#" class="hover:text-gold transition-colors">Kebijakan Privasi</a>
        </div>
      </div>
    </div>
  </footer>

  <script>
    // Mobile menu toggle
    const menuBtn    = document.getElementById('menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    menuBtn?.addEventListener('click', () => mobileMenu.classList.toggle('open'));

    // User dropdown toggle
    function toggleDropdown() {
      const dropdown = document.getElementById('user-dropdown');
      dropdown.classList.toggle('hidden');
    }

    // Tutup dropdown kalau klik di luar
    document.addEventListener('click', function(e) {
      const wrap    = document.getElementById('user-dropdown-wrap');
      const dropdown = document.getElementById('user-dropdown');
      if (wrap && dropdown && !wrap.contains(e.target)) {
        dropdown.classList.add('hidden');
      }
    });
  </script>

  <script>
  // ─── WISHLIST ──────────────────────────────────────────────────────
  (function () {
    const CSRF = document.querySelector('meta[name=csrf-token]')?.content || '';

    // Init: mark already-wishlisted cards on page load
    function initWishlist() {
      const btns = document.querySelectorAll('.wish-toggle');
      if (!btns.length) return;
      const ids = Array.from(btns).map(b => b.dataset.eventId);

      fetch('/wishlist/status?ids[]=' + ids.join('&ids[]='), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(wishlisted => {
        btns.forEach(btn => {
          if (wishlisted.includes(parseInt(btn.dataset.eventId))) {
            markActive(btn, true);
          }
        });
      })
      .catch(() => {});
    }

    function markActive(btn, active) {
      const icon = btn.querySelector('.wish-icon');
      btn.dataset.wishlisted = active ? 'true' : 'false'; // state source of truth
      if (active) {
        icon.setAttribute('fill', '#ef4444');
        icon.setAttribute('stroke', '#ef4444');
        icon.classList.remove('text-gray-400');
        icon.classList.add('text-red-500');
        btn.title = 'Hapus dari Favorit';
      } else {
        icon.setAttribute('fill', 'none');
        icon.setAttribute('stroke', 'currentColor');
        icon.classList.add('text-gray-400');
        icon.classList.remove('text-red-500');
        btn.title = 'Tambah ke Favorit';
      }
    }

    window.toggleWish = function (btn) {
      const url = btn.dataset.url;
      // Gunakan data-wishlisted attribute sebagai state (lebih reliable dari SVG fill)
      const currentlyActive = btn.dataset.wishlisted === 'true';
      markActive(btn, !currentlyActive); // optimistic

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': CSRF,
          'X-Requested-With': 'XMLHttpRequest',
        }
      })
      .then(r => {
        if (r.status === 401) { window.location.href = '/login'; return null; }
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
      })
      .then(data => {
        if (!data) return;
        markActive(btn, data.wishlisted);
        showWishToast(data.wishlisted);
      })
      .catch(() => markActive(btn, currentlyActive)); // revert on error
    };

    function showWishToast(added) {
      let t = document.getElementById('wish-toast');
      if (!t) {
        t = document.createElement('div');
        t.id = 'wish-toast';
        t.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-[70] px-5 py-2.5 rounded-full text-sm font-semibold shadow-xl transition-all duration-300 opacity-0 pointer-events-none';
        document.body.appendChild(t);
      }
      t.textContent = added ? '❤️ Ditambahkan ke Favorit' : '🤍 Dihapus dari Favorit';
      t.style.background = added ? '#102A71' : '#6b7280';
      t.style.color = '#fff';
      t.style.opacity = '1';
      clearTimeout(t._timer);
      t._timer = setTimeout(() => { t.style.opacity = '0'; }, 2000);
    }

    document.addEventListener('DOMContentLoaded', initWishlist);
  })();
  </script>
  @stack('scripts')
</body>
</html>
