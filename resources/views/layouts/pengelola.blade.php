<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title','Dashboard EO — TicketIn')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: { extend: {
        colors: { navy: { deep:'#001840', mid:'#102A71' }, gold: { DEFAULT:'#F5C400', light:'#FFDC5F' } },
        fontFamily: { poppins: ['Poppins','sans-serif'] }
      }}
    }
  </script>
  <style>
    * { font-family:'Poppins',sans-serif; }
    body { background:#f9fafb; }
    .sidebar { width:240px; flex-shrink:0; }
    .sidebar-link { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:12px; font-size:.875rem; font-weight:500; color:#6b7280; transition:all .2s; }
    .sidebar-link:hover { background:#f0f4ff; color:#102A71; }
    .sidebar-link.active { background:#102A71; color:white; font-weight:600; }
    .sidebar-link.active svg { color:white; }
    .main-content { flex:1; min-width:0; overflow-y:auto; height:100vh; }
    @stack('styles')
  </style>
  @stack('styles')
</head>
<body class="flex h-screen overflow-hidden">

  {{-- SIDEBAR --}}
  <aside class="sidebar bg-white border-r border-gray-100 flex flex-col h-screen sticky top-0 overflow-y-auto">

    {{-- Logo --}}
    <div class="p-5 border-b border-gray-100">
      <a href="{{ route('pengelola.dashboard') }}" class="flex items-center gap-2">
        <div class="w-8 h-8 bg-gold rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-navy-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
        </div>
        <div>
          <p class="font-extrabold text-navy-deep text-sm">TicketIn</p>
          <p class="text-gray-400 text-xs">Pengelola Event</p>
        </div>
      </a>
    </div>

    {{-- User Info --}}
    <div class="p-4 border-b border-gray-100">
      <div class="flex items-center gap-3">
        <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover">
        <div class="min-w-0">
          <p class="font-bold text-navy-deep text-sm truncate">{{ auth()->user()->nama_panggilan }}</p>
          <p class="text-gray-400 text-xs truncate">{{ auth()->user()->email }}</p>
        </div>
      </div>
    </div>

    {{-- Navigation --}}
    <nav class="p-3 flex-1 space-y-1">
      <p class="text-xs font-bold text-gray-400 uppercase tracking-wider px-3 mb-2 mt-2">Menu Utama</p>

      <a href="{{ route('pengelola.dashboard') }}"
         class="sidebar-link {{ request()->routeIs('pengelola.dashboard') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
      </a>

      <a href="{{ route('pengelola.event.create') }}"
         class="sidebar-link {{ request()->routeIs('pengelola.event.create') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Event
      </a>

      <a href="{{ route('pengelola.semua-pesanan') }}"
         class="sidebar-link {{ request()->routeIs('pengelola.semua-pesanan') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Semua Pesanan
        @php $pending = \App\Models\Order::whereHas('event', fn($q) => $q->where('pengelola_id', auth()->id()))->where('status','pending')->count(); @endphp
        @if($pending > 0)
          <span class="ml-auto text-xs bg-red-500 text-white font-bold px-1.5 py-0.5 rounded-full">{{ $pending }}</span>
        @endif
      </a>

      <a href="{{ route('pengelola.laporan') }}"
         class="sidebar-link {{ request()->routeIs('pengelola.laporan*') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Laporan Penjualan
      </a>

      <p class="text-xs font-bold text-gray-400 uppercase tracking-wider px-3 mb-2 mt-4">Event Saya</p>

      @foreach(\App\Models\Event::where('pengelola_id', auth()->id())->latest()->take(5)->get() as $ev)
      <a href="{{ route('pengelola.event.pesanan', $ev) }}"
         class="sidebar-link {{ request()->route('event')?->id === $ev->id ? 'active' : '' }}">
        <div class="w-4 h-4 rounded bg-navy-mid/20 flex-shrink-0 overflow-hidden">
          <img src="{{ $ev->cover_url }}" class="w-full h-full object-cover">
        </div>
        <span class="truncate text-xs">{{ \Illuminate\Support\Str::limit($ev->judul, 18) }}</span>
      </a>
      @endforeach
    </nav>

    {{-- Bottom Links --}}
    <div class="p-3 border-t border-gray-100 space-y-1">
      <a href="{{ route('profile.edit') }}" class="sidebar-link">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Pengaturan Akun
      </a>
      <a href="{{ route('dashboard') }}" class="sidebar-link" target="_blank">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        Lihat Website
      </a>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-link w-full text-left text-red-500 hover:bg-red-50">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          Keluar
        </button>
      </form>
    </div>
  </aside>

  {{-- MAIN --}}
  <div class="main-content">
    {{-- Top Bar --}}
    <div class="sticky top-0 bg-white border-b border-gray-100 z-10 px-6 py-3 flex items-center justify-between">
      <h2 class="font-bold text-navy-deep text-sm">@yield('title','Dashboard')</h2>
      <div class="flex items-center gap-3">
        @if(session('success'))
        <span class="text-xs text-green-600 bg-green-50 border border-green-200 px-3 py-1.5 rounded-lg"> {{ session('success') }}</span>
        @endif
        <span class="text-xs text-gray-400">{{ now()->translatedFormat('d M Y') }}</span>
      </div>
    </div>

    @yield('content')
  </div>

  @stack('scripts')
</body>
</html>
