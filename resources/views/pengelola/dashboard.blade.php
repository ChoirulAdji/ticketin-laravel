@extends('layouts.pengelola')
@section('title', 'Dashboard — TicketIn EO')

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
  .stat-card {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 20px;
    transition: all .2s;
  }

  .stat-card:hover {
    box-shadow: 0 8px 24px rgba(0, 24, 64, .08);
    transform: translateY(-2px);
  }

  .chart-bar {
    background: #102A71;
    border-radius: 6px 6px 0 0;
    transition: all .3s;
    cursor: pointer;
    min-width: 28px;
  }

  .chart-bar:hover {
    background: #F5C400;
  }

  .notif-dot {
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
  }

  .notif-item {
    border-left: 3px solid #F5C400;
  }

  .badge-paid {
    background: rgba(34, 197, 94, .15);
    color: #16a34a;
    border: 1px solid rgba(34, 197, 94, .3);
  }

  .badge-pending {
    background: rgba(234, 179, 8, .15);
    color: #b45309;
    border: 1px solid rgba(234, 179, 8, .3);
  }

  .badge-cancelled {
    background: rgba(239, 68, 68, .15);
    color: #dc2626;
    border: 1px solid rgba(239, 68, 68, .3);
  }

  .progress-bar {
    height: 6px;
    border-radius: 99px;
    background: #e5e7eb;
    overflow: hidden;
  }

  .progress-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #102A71, #F5C400);
    transition: width 1s ease;
  }
</style>
@endpush

@section('content')
<div class="p-6 max-w-7xl mx-auto">

  {{-- Header --}}
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-2xl font-extrabold text-navy-deep">Dashboard</h1>
      <p class="text-gray-500 text-sm mt-1">Selamat datang, <span class="font-bold text-navy-mid">{{ auth()->user()->nama_panggilan }}</span>! </p>
    </div>
    <a href="{{ route('pengelola.event.create') }}" class="flex items-center gap-2 bg-gold text-navy-deep font-bold px-5 py-3 rounded-xl hover:bg-gold-light transition-all text-sm shadow-sm">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      Tambah Event
    </a>
  </div>

  @if(session('success'))
  <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm mb-6"> {{ session('success') }}</div>
  @endif

  {{-- ── STAT CARDS ── --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
      <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 bg-navy-mid/10 rounded-xl flex items-center justify-center">
          <svg class="w-5 h-5 text-navy-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">{{ $events->where('status','published')->count() }} Live</span>
        @if($events->where('status','pending_review')->count() > 0)
        <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">{{ $events->where('status','pending_review')->count() }} Review</span>
        @endif
      </div>
      <p class="text-3xl font-extrabold text-navy-deep">{{ $events->count() }}</p>
      <p class="text-gray-400 text-xs mt-1">Total Event</p>
    </div>

    <div class="stat-card">
      <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 bg-gold/10 rounded-xl flex items-center justify-center">
          <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
          </svg>
        </div>
        @php $pendingCount = \App\Models\Order::whereHas('event', fn($q) => $q->where('pengelola_id', auth()->id()))->where('status','pending')->count(); @endphp
        @if($pendingCount > 0)
        <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded-full">{{ $pendingCount }} Pending</span>
        @endif
      </div>
      <p class="text-3xl font-extrabold text-navy-deep">{{ $totalPesanan }}</p>
      <p class="text-gray-400 text-xs mt-1">Total Pesanan</p>
    </div>

    <div class="stat-card">
      <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
          <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
      <p class="text-2xl font-extrabold text-navy-deep">Rp {{ number_format($totalPendapatan/1000000, 1) }}jt</p>
      <p class="text-gray-400 text-xs mt-1">Total Pendapatan</p>
    </div>

    <div class="stat-card">
      <div class="flex items-center justify-between mb-3">
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
        </div>
      </div>
      @php $totalBuyer = \App\Models\Order::whereHas('event', fn($q) => $q->where('pengelola_id', auth()->id()))->where('status','paid')->distinct('user_id')->count('user_id'); @endphp
      <p class="text-3xl font-extrabold text-navy-deep">{{ $totalBuyer }}</p>
      <p class="text-gray-400 text-xs mt-1">Total Pembeli</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- ── GRAFIK PENJUALAN ── --}}
    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="font-bold text-navy-deep"> Grafik Penjualan Tiket</h2>
          <p class="text-gray-400 text-xs mt-0.5">Tiket terjual per event (status lunas)</p>
        </div>
        <select id="chartFilter" onchange="updateChart()" class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 outline-none focus:border-navy-mid">
          <option value="tiket">Jumlah Tiket</option>
          <option value="pendapatan">Pendapatan</option>
        </select>
      </div>

      @php
      $chartData = $events->map(function($event) {
      $paid = \App\Models\Order::where('event_id', $event->id)->where('status','paid');
      return [
      'judul' => \Illuminate\Support\Str::limit($event->judul, 20),
      'tiket' => $paid->sum('total_qty'),
      'pendapatan' => $paid->sum('total_harga'),
      ];
      })->sortByDesc('tiket')->take(6)->values();
      $maxTiket = $chartData->max('tiket') ?: 1;
      $maxPend = $chartData->max('pendapatan') ?: 1;
      @endphp

      @if($chartData->isEmpty())
      <div class="flex items-center justify-center h-40 text-gray-400 text-sm">Belum ada data penjualan</div>
      @else
      <div class="flex items-end gap-3 h-48 mb-3" id="chart-bars">
        @foreach($chartData as $item)
        <div class="flex flex-col items-center gap-1 flex-1">
          <span class="text-xs font-bold text-navy-mid chart-label">{{ $item['tiket'] }}</span>
          <div class="chart-bar w-full"
            style="height:{{ max(4, ($item['tiket']/$maxTiket)*160) }}px"
            data-tiket="{{ $item['tiket'] }}"
            data-pendapatan="{{ $item['pendapatan'] }}"
            data-max-tiket="{{ $maxTiket }}"
            data-max-pend="{{ $maxPend }}"
            title="{{ $item['judul'] }}: {{ $item['tiket'] }} tiket">
          </div>
          <span class="text-xs text-gray-400 text-center leading-tight" style="max-width:60px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item['judul'] }}</span>
        </div>
        @endforeach
      </div>
      <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
        <div class="w-3 h-3 bg-navy-mid rounded-sm"></div><span class="text-xs text-gray-400">Batang = Jumlah Tiket Terjual</span>
      </div>
      @endif
    </div>

    {{-- ── NOTIFIKASI ── --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-navy-deep"> Notifikasi</h2>
        @php
        $newOrders = \App\Models\Order::whereHas('event', fn($q) => $q->where('pengelola_id', auth()->id()))
        ->where('created_at', '>=', now()->subDay())
        ->latest()->take(5)->get();
        @endphp
        @if($newOrders->count() > 0)
        <span class="text-xs font-bold text-white bg-red-500 px-2 py-0.5 rounded-full">{{ $newOrders->count() }} baru</span>
        @endif
      </div>

      @if($newOrders->isEmpty())
      <div class="text-center py-8 text-gray-400 text-sm">
        <div class="text-3xl mb-2"></div>
        Belum ada notifikasi baru
      </div>
      @else
      <div class="space-y-3">
        @foreach($newOrders as $notif)
        <div class="notif-item bg-gray-50 rounded-xl p-3 pl-4">
          <div class="flex items-start justify-between gap-2">
            <div class="flex-1 min-w-0">
              <p class="text-xs font-bold text-navy-deep truncate">{{ $notif->user->nama_panggilan }}</p>
              <p class="text-xs text-gray-500 truncate">{{ \Illuminate\Support\Str::limit($notif->event->judul, 25) }}</p>
              <p class="text-xs text-gray-400 mt-0.5">{{ $notif->ticket_summary }}</p>
            </div>
            <div class="text-right flex-shrink-0">
              <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $notif->status==='paid'?'badge-paid':'badge-pending' }}">
                {{ $notif->status==='paid'?'Lunas':'Pending' }}
              </span>
              <p class="text-xs text-gray-300 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <a href="{{ route('pengelola.semua-pesanan') }}" class="block text-center text-xs text-navy-mid font-semibold mt-4 hover:text-gold transition-colors">Lihat Semua Pesanan →</a>
      @endif
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- ── REKAP PENDAPATAN PER BULAN ── --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      {{-- Pie Charts --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 items-start">

        {{-- Pie: Penjualan per Kategori Tiket --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
          <h3 class="font-bold text-navy-deep text-sm mb-4 truncate"> Kategori Tiket Terjual</h3>
          @if($pieKategoriTiket->isEmpty())
          <p class="text-center text-gray-400 text-xs py-8">Belum ada tiket terjual</p>
          @else
          <div class="flex justify-center">
            <canvas id="chartKategoriTiket" style="max-height:200px;"></canvas>
          </div>
          <div class="mt-3 space-y-1">
            @foreach($pieKategoriTiket as $kat => $qty)
            <div class="flex items-center justify-between text-xs">
              <span class="text-gray-600 font-medium">{{ $kat }}</span>
              <span class="font-bold text-navy-mid">{{ $qty }} tiket</span>
            </div>
            @endforeach
          </div>
          @endif
        </div>

        {{-- Pie: Status Pesanan --}}
        <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
          <h3 class="font-bold text-navy-deep text-sm mb-4"> Status Pesanan</h3>
          @if($piePesananStatus->isEmpty())
          <p class="text-center text-gray-400 text-xs py-8">Belum ada pesanan</p>
          @else
          <div class="flex justify-center">
            <canvas id="chartPesananStatus" style="max-height:200px;"></canvas>
          </div>
          <div class="mt-3 space-y-1">
            @foreach($piePesananStatus as $status => $total)
            <div class="flex items-center justify-between text-xs">
              <span class="text-gray-600 capitalize font-medium">{{ $status }}</span>
              <span class="font-bold text-navy-mid">{{ $total }} pesanan</span>
            </div>
            @endforeach
          </div>
          @endif
        </div>

      </div>

      <h2 class="font-bold text-navy-deep mb-5"> Rekap Pendapatan</h2>
      @php
      $rekapBulan = \App\Models\Order::whereHas('event', fn($q) => $q->where('pengelola_id', auth()->id()))
      ->where('status','paid')
      ->where('created_at', '>=', now()->subMonths(6))
      ->get()
      ->groupBy(fn($o) => $o->created_at->format('Y-m'))
      ->map(fn($g) => ['bulan' => $g->first()->created_at->format('M Y'), 'total' => $g->sum('total_harga'), 'tiket' => $g->sum('total_qty')])
      ->sortKeys()
      ->values();
      $maxRekap = $rekapBulan->max('total') ?: 1;
      @endphp

      @if($rekapBulan->isEmpty())
      <div class="text-center py-8 text-gray-400 text-sm">Belum ada data pendapatan</div>
      @else
      <div class="space-y-3">
        @foreach($rekapBulan as $r)
        <div>
          <div class="flex justify-between text-sm mb-1">
            <span class="font-semibold text-navy-deep">{{ $r['bulan'] }}</span>
            <div class="text-right">
              <span class="font-bold text-navy-mid">Rp {{ number_format($r['total'],0,',','.') }}</span>
              <span class="text-gray-400 text-xs ml-2">{{ $r['tiket'] }} tiket</span>
            </div>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" style="width:{{ ($r['total']/$maxRekap)*100 }}%"></div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-sm">
        <span class="text-gray-500">Total 6 bulan terakhir</span>
        <span class="font-extrabold text-navy-deep">Rp {{ number_format($rekapBulan->sum('total'),0,',','.') }}</span>
      </div>
      @endif
    </div>

    {{-- ── STATISTIK EVENT ── --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h2 class="font-bold text-navy-deep mb-5"> Statistik Event</h2>
      @if($events->isEmpty())
      <div class="text-center py-8 text-gray-400 text-sm">Belum ada event</div>
      @else
      <div class="space-y-4">
        @foreach($events->take(4) as $event)
        @php
        $soldQty = \App\Models\Order::where('event_id',$event->id)->where('status','paid')->sum('total_qty');
        $totalKuota = $event->ticketCategories->sum('kuota');
        $pct = $totalKuota > 0 ? min(100, round(($soldQty/$totalKuota)*100)) : 0;
        $pendapatan = \App\Models\Order::where('event_id',$event->id)->where('status','paid')->sum('total_harga');
        @endphp
        <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
          <div class="flex items-center justify-between mb-2">
            <p class="font-bold text-navy-deep text-sm truncate flex-1 mr-3">{{ \Illuminate\Support\Str::limit($event->judul,30) }}</p>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full
                {{ $event->status==='published'      ? 'bg-green-100 text-green-700' :
                   ($event->status==='pending_review' ? 'bg-amber-100 text-amber-700' :
                   ($event->status==='cancelled'      ? 'bg-red-100 text-red-600' :
                                                        'bg-gray-100 text-gray-500')) }}">
              {{ $event->status==='published'      ? ' Live' :
                   ($event->status==='pending_review' ? ' Review Admin' :
                   ($event->status==='cancelled'      ? ' Ditolak' : ' Draft')) }}
            </span>

            @if($event->status==='pending_review')
            <p class="text-xs text-amber-600 mt-1">Menunggu persetujuan admin untuk tampil ke publik.</p>
            @elseif($event->status==='draft' && $event->catatan_admin)
            <p class="text-xs text-red-500 mt-1">Ditolak: {{ $event->catatan_admin }}</p>
            @endif
          </div>
          <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
            <span> {{ $soldQty }}/{{ $totalKuota }} tiket</span>
            <span> Rp {{ number_format($pendapatan,0,',','.') }}</span>
            <span> {{ $event->orders_count }} pesanan</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" style="width:{{ $pct }}%"></div>
          </div>
          <div class="flex justify-between text-xs text-gray-400 mt-1">
            <span>Terjual {{ $pct }}%</span>
            <span>{{ $totalKuota - $soldQty }} sisa</span>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>

  {{-- ── MANAJEMEN PESANAN TERBARU ── --}}
  <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm mb-6">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-bold text-navy-deep"> Pesanan Masuk</h2>
      <a href="{{ route('pengelola.semua-pesanan') }}" class="text-xs text-navy-mid font-semibold hover:text-gold transition-colors">Lihat Semua →</a>
    </div>

    @php
    $pesananTerbaru = \App\Models\Order::whereHas('event', fn($q) => $q->where('pengelola_id', auth()->id()))
    ->with(['user','event'])
    ->latest()
    ->take(8)
    ->get();
    @endphp

    @if($pesananTerbaru->isEmpty())
    <div class="py-10 text-center text-gray-400 text-sm">
      <div class="text-3xl mb-2"></div>
      Belum ada pesanan masuk
    </div>
    @else
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-100">
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Pembeli</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Event</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Tiket</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Total</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Status</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @foreach($pesananTerbaru as $order)
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="py-3 pr-4">
              <div class="flex items-center gap-2">
                <img src="{{ $order->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                <div class="min-w-0">
                  <p class="font-semibold text-navy-deep text-xs truncate">{{ $order->user->nama_panggilan }}</p>
                  <p class="text-gray-400 text-xs truncate">{{ $order->user->email }}</p>
                </div>
              </div>
            </td>
            <td class="py-3 pr-4">
              <p class="text-navy-deep text-xs font-medium truncate max-w-[140px]">{{ \Illuminate\Support\Str::limit($order->event->judul,22) }}</p>
              <p class="text-gray-400 text-xs">{{ $order->created_at->format('d M, H:i') }}</p>
            </td>
            <td class="py-3 pr-4">
              <p class="text-navy-deep text-xs">{{ $order->total_qty }} tiket</p>
              <p class="text-gray-400 text-xs">{{ \Illuminate\Support\Str::limit($order->ticket_summary,20) }}</p>
            </td>
            <td class="py-3 pr-4">
              <p class="font-bold text-navy-deep text-xs">Rp {{ number_format($order->total_harga,0,',','.') }}</p>
              <p class="text-gray-400 text-xs">{{ $order->metode_bayar }}</p>
            </td>
            <td class="py-3 pr-4">
              <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $order->status==='paid'?'badge-paid':($order->status==='pending'?'badge-pending':'badge-cancelled') }}">
                {{ $order->status==='paid'?' Lunas':($order->status==='pending'?' Pending':' Batal') }}
              </span>
            </td>
            <td class="py-3">
              <form method="POST" action="{{ route('pengelola.pesanan.status', $order) }}">
                @csrf @method('PUT')
                <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-lg px-2 py-1 text-gray-700 outline-none focus:border-gold bg-white cursor-pointer">
                  <option value="pending" {{ $order->status==='pending'?'selected':'' }}>Pending</option>
                  <option value="paid" {{ $order->status==='paid'?'selected':'' }}>Lunas</option>
                  <option value="cancelled" {{ $order->status==='cancelled'?'selected':'' }}>Batal</option>
                </select>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  {{-- ── DAFTAR EVENT ── --}}
  <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-bold text-navy-deep"> Event Saya</h2>
      <a href="{{ route('pengelola.event.create') }}" class="text-xs text-navy-mid font-semibold hover:text-gold transition-colors">+ Tambah Event</a>
    </div>
    @if($events->isEmpty())
    <div class="py-10 text-center text-gray-400 text-sm">
      <div class="text-3xl mb-2"></div>
      Belum ada event. <a href="{{ route('pengelola.event.create') }}" class="text-navy-mid font-semibold hover:underline">Buat sekarang</a>
    </div>
    @else
    <div class="space-y-3">
      @foreach($events as $event)
      <div class="flex items-center gap-4 bg-gray-50 border border-gray-100 rounded-xl p-4 hover:border-navy-mid/20 transition-colors">
        <img src="{{ $event->cover_url }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
        <div class="flex-1 min-w-0">
          <p class="font-bold text-navy-deep text-sm truncate">{{ $event->judul }}</p>
          <p class="text-gray-400 text-xs mt-0.5">{{ $event->tanggal_waktu->format('d M Y') }} · {{ $event->lokasi_kota }}</p>
        </div>
        <div class="hidden sm:flex items-center gap-4 text-xs text-gray-500 flex-shrink-0">
          <span>{{ $event->orders_count }} pesanan</span>
        </div>
        <span class="text-xs font-semibold px-2 py-1 rounded-full flex-shrink-0 {{ $event->status==='published'?'bg-green-100 text-green-700':($event->status==='draft'?'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-700') }}">
          {{ $event->status==='published'?'Live':($event->status==='draft'?'Draft':'Batal') }}
        </span>
        <div class="flex gap-2 flex-shrink-0">
          <a href="{{ route('pengelola.event.pesanan',$event) }}" class="text-xs bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-all">Pesanan</a>
          <a href="{{ route('pengelola.event.edit',$event) }}" class="text-xs bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-200 transition-all">Edit</a>
          <form method="POST" action="{{ route('pengelola.event.destroy',$event) }}" onsubmit="return confirm('Hapus event ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-xs bg-red-50 text-red-600 px-3 py-1.5 rounded-lg hover:bg-red-100 transition-all">Hapus</button>
          </form>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>

</div>
@endsection

@push('scripts')
<script>
  // Pie charts EO
  const EO_COLORS = ['#102A71', '#F5C400', '#3B82F6', '#10B981', '#EF4444', '#8B5CF6', '#F59E0B'];

  function makeEoPie(id, labels, data) {
    const ctx = document.getElementById(id);
    if (!ctx) return;
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels,
        datasets: [{
          data,
          backgroundColor: EO_COLORS.slice(0, data.length),
          borderWidth: 2,
          borderColor: '#fff',
          hoverOffset: 6
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: ctx => ` ${ctx.label}: ${ctx.raw} (${Math.round(ctx.raw/ctx.dataset.data.reduce((a,b)=>a+b,0)*100)}%)`
            }
          }
        },
        cutout: '60%'
      }
    });
  }
  @if(!$pieKategoriTiket -> isEmpty())
  makeEoPie('chartKategoriTiket', {
    !!json_encode($pieKategoriTiket -> keys() -> values()) !!
  }, {
    !!json_encode($pieKategoriTiket -> values()) !!
  });
  @endif
  @if(!$piePesananStatus -> isEmpty())
  makeEoPie('chartPesananStatus', {
    !!json_encode($piePesananStatus -> keys() -> map(fn($k) => ucfirst($k)) -> values()) !!
  }, {
    !!json_encode($piePesananStatus -> values()) !!
  });
  @endif

  function updateChart() {
    const mode = document.getElementById('chartFilter').value;
    const bars = document.querySelectorAll('.chart-bar');
    const labels = document.querySelectorAll('.chart-label');

    let maxVal = 0;
    bars.forEach(b => {
      const val = parseFloat(mode === 'tiket' ? b.dataset.tiket : b.dataset.pendapatan);
      if (val > maxVal) maxVal = val;
    });
    if (maxVal === 0) maxVal = 1;

    bars.forEach((b, i) => {
      const val = parseFloat(mode === 'tiket' ? b.dataset.tiket : b.dataset.pendapatan);
      const h = Math.max(4, (val / maxVal) * 160);
      b.style.height = h + 'px';
      if (labels[i]) {
        labels[i].textContent = mode === 'tiket' ?
          b.dataset.tiket + ' tiket' :
          'Rp ' + parseInt(b.dataset.pendapatan).toLocaleString('id-ID');
      }
    });
  }
</script>
@endpush

