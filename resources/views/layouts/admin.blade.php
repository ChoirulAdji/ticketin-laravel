<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title','Admin — TicketIn')</title>
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
    body { background:#f1f5f9; }
    .sidebar { width:240px; flex-shrink:0; }
    .sidebar-link { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:12px; font-size:.875rem; font-weight:500; color:#6b7280; transition:all .2s; text-decoration:none; }
    .sidebar-link:hover { background:#f0f4ff; color:#102A71; }
    .sidebar-link.active { background:#102A71; color:white; font-weight:600; }
    .main-content { flex:1; min-width:0; overflow-y:auto; height:100vh; }
    .sidebar-section { font-size:.65rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.08em; padding:0 16px; margin:16px 0 6px; }
    @stack('styles')
  </style>
  @stack('styles')
</head>
<body class="flex h-screen overflow-hidden">

  {{-- SIDEBAR --}}
  <aside class="sidebar bg-white border-r border-gray-100 flex flex-col h-screen sticky top-0 overflow-y-auto shadow-sm">

    {{-- Logo --}}
    <div class="p-5 border-b border-gray-100">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div>
          <p class="font-extrabold text-navy-deep text-sm">TicketIn</p>
          <p class="text-red-500 text-xs font-bold">Admin Panel</p>
        </div>
      </div>
    </div>

    {{-- User --}}
    <div class="p-4 border-b border-gray-100">
      <div class="flex items-center gap-3">
        <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover">
        <div class="min-w-0">
          <p class="font-bold text-navy-deep text-sm truncate">{{ auth()->user()->nama_panggilan }}</p>
          <p class="text-red-500 text-xs font-semibold"> Super Admin</p>
        </div>
      </div>
    </div>

    {{-- Nav --}}
    <nav class="p-3 flex-1 space-y-1">
      <p class="sidebar-section">Utama</p>
      <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
      </a>

      <p class="sidebar-section">Manajemen</p>
      <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        Manajemen User
      </a>

      <a href="{{ route('admin.events') }}" class="sidebar-link {{ request()->routeIs('admin.events') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Manajemen Event
      </a>


      <p class="sidebar-section">Website</p>
      <a href="{{ route('admin.hero-slider') }}"
         class="sidebar-link {{ request()->routeIs('admin.hero-slider*') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Hero Slider
      </a>

      <p class="sidebar-section">Laporan</p>
      <a href="{{ route('admin.laporan') }}"
         class="sidebar-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Laporan Penjualan
      </a>

      <p class="sidebar-section">Verifikasi</p>
      <a href="{{ route('admin.pengajuan-eo') }}" class="sidebar-link {{ request()->routeIs('admin.pengajuan-eo') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Verifikasi EO
        @php $pendingEo = \App\Models\EoApplication::where('status','pending')->count(); @endphp
        @if($pendingEo > 0)
          <span class="ml-auto text-xs bg-red-500 text-white font-bold px-1.5 py-0.5 rounded-full">{{ $pendingEo }}</span>
        @endif
      </a>
    </nav>

    {{-- Bottom --}}
    <div class="p-3 border-t border-gray-100 space-y-1">
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
    <div class="sticky top-0 bg-white border-b border-gray-100 z-10 px-6 py-3 flex items-center justify-between">
      <h2 class="font-bold text-navy-deep text-sm">@yield('title','Dashboard Admin')</h2>
      <div class="flex items-center gap-3 text-xs text-gray-400">
        @if(session('success'))
          <span class="text-green-600 bg-green-50 border border-green-200 px-3 py-1.5 rounded-lg"> {{ session('success') }}</span>
        @endif
        @if(session('error'))
          <span class="text-red-600 bg-red-50 border border-red-200 px-3 py-1.5 rounded-lg"> {{ session('error') }}</span>
        @endif
        {{ now()->format('d M Y') }}
      </div>
    </div>
    @yield('content')
  </div>

  @stack('scripts')
</body>
</html>
