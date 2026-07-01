@extends('layouts.admin')
@section('title','Dashboard Admin')

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
  .stat-card { background:white; border:1px solid #e5e7eb; border-radius:16px; padding:20px; transition:all .2s; }
  .stat-card:hover { box-shadow:0 8px 24px rgba(0,24,64,.08); transform:translateY(-2px); }
  .chart-bar { border-radius:6px 6px 0 0; transition:all .3s; min-width:32px; }
  .chart-bar:hover { opacity:.8; }
  .badge-paid { background:rgba(34,197,94,.15); color:#16a34a; border:1px solid rgba(34,197,94,.3); }
  .badge-pending { background:rgba(234,179,8,.15); color:#b45309; border:1px solid rgba(234,179,8,.3); }
  .badge-cancelled { background:rgba(239,68,68,.15); color:#dc2626; border:1px solid rgba(239,68,68,.3); }
  .progress-bar { height:6px; border-radius:99px; background:#e5e7eb; overflow:hidden; }
  .progress-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#102A71,#F5C400); }
</style>
@endpush

@section('content')
<div class="p-6 max-w-7xl mx-auto">

  <div class="mb-8">
    <h1 class="text-2xl font-extrabold text-navy-deep">Dashboard Admin</h1>
    <p class="text-gray-400 text-sm mt-0.5">Pantau seluruh aktivitas platform TicketIn</p>
  </div>

  {{-- Alert pengajuan EO --}}
  @if($stats['pending_eo'] > 0)
  <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <p class="text-yellow-700 font-semibold text-sm">Ada <strong>{{ $stats['pending_eo'] }}</strong> pengajuan EO yang menunggu verifikasi!</p>
    </div>
    <a href="{{ route('admin.pengajuan-eo') }}" class="text-xs bg-yellow-500 text-white font-bold px-4 py-2 rounded-lg hover:bg-yellow-600 transition-all">Proses Sekarang</a>
  </div>
  @endif

  {{-- Stats --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
      ['Total User',$stats['total_user'],'text-navy-deep','bg-navy-mid/10',route('admin.users')],
      ['Total EO',$stats['total_eo'],'text-purple-700','bg-purple-50',route('admin.users').'?role=pengelola'],
      ['Total Event',$stats['total_event'],'text-blue-700','bg-blue-50',route('admin.events')],
      ['Total Pesanan',$stats['total_pesanan'],'text-green-700','bg-green-50',route('admin.pesanan')],
      ['Penarikan Pending',$stats['pending_withdrawal'],'text-amber-700','bg-amber-50',route('admin.penarikan')],
    ] as [$label,$val,$color,$bg,$link])
    <a href="{{ $link }}" class="stat-card block">
      <div class="flex items-center justify-between mb-3">
      </div>
      <p class="text-3xl font-extrabold {{ $color }}">{{ number_format($val) }}</p>
      <p class="text-gray-400 text-xs mt-1">{{ $label }}</p>
    </a>
    @endforeach
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="stat-card">
      <p class="text-2xl font-extrabold text-navy-deep">Rp {{ number_format($stats['total_pendapatan']/1000000,1) }}jt</p>
      <p class="text-gray-400 text-xs mt-1">Total Pendapatan Platform</p>
    </div>
    <a href="{{ route('admin.pengajuan-eo') }}" class="stat-card block">
      <p class="text-3xl font-extrabold text-yellow-600">{{ $stats['pending_eo'] }}</p>
      <p class="text-gray-400 text-xs mt-1">Pengajuan EO Pending</p>
    </a>
    <a href="{{ route('admin.events') }}" class="stat-card block">
      <p class="text-3xl font-extrabold text-blue-600">{{ $stats['pending_event'] }}</p>
      <p class="text-gray-400 text-xs mt-1">Event Draft</p>
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Grafik Pendapatan --}}
    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h2 class="font-bold text-navy-deep mb-6">Pendapatan Platform (6 Bulan)</h2>
      @if($grafikPendapatan->isEmpty())
        <div class="flex items-center justify-center h-40 text-gray-400 text-sm">Belum ada data</div>
      @else
        @php $maxPend = $grafikPendapatan->max('total') ?: 1; @endphp
        <div class="flex items-end gap-3 h-48 mb-3">
          @foreach($grafikPendapatan as $g)
          <div class="flex flex-col items-center gap-1 flex-1">
            <span class="text-xs text-gray-400">{{ number_format($g['total']/1000000,1) }}jt</span>
            <div class="chart-bar w-full bg-navy-mid" style="height:{{ max(4,($g['total']/$maxPend)*160) }}px"
                 title="{{ $g['bulan'] }}: Rp {{ number_format($g['total'],0,',','.') }}"></div>
            <span class="text-xs text-gray-400 text-center" style="font-size:10px;">{{ $g['bulan'] }}</span>
          </div>
          @endforeach
        </div>
        <div class="flex items-center gap-4 pt-3 border-t border-gray-100 text-xs text-gray-400">
          <span>Total: <strong class="text-navy-deep">Rp {{ number_format($grafikPendapatan->sum('total'),0,',','.') }}</strong></span>
          <span>Pesanan: <strong class="text-navy-deep">{{ $grafikPendapatan->sum('pesanan') }}</strong></span>
        </div>
      @endif
    </div>

    {{-- Pengajuan EO Terbaru --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-navy-deep">Pengajuan EO</h2>
        <a href="{{ route('admin.pengajuan-eo') }}" class="text-xs text-navy-mid font-semibold hover:text-gold">Lihat Semua</a>
      </div>
      @if($pengajuanPending->isEmpty())
        <div class="text-center py-8 text-gray-400 text-sm">Tidak ada pengajuan pending</div>
      @else
        <div class="space-y-3">
          @foreach($pengajuanPending as $app)
          <div class="flex items-center gap-3 p-3 bg-yellow-50 border border-yellow-100 rounded-xl">
            <img src="{{ $app->user->avatar_url }}" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-navy-deep text-xs truncate">{{ $app->user->nama_lengkap }}</p>
              <p class="text-gray-500 text-xs truncate">{{ $app->nama_organisasi }}</p>
              <p class="text-gray-400 text-xs">{{ $app->created_at->diffForHumans() }}</p>
            </div>
            <a href="{{ route('admin.pengajuan-eo') }}" class="text-xs bg-navy-mid text-white px-2 py-1 rounded-lg hover:bg-navy-deep transition-all flex-shrink-0">Review</a>
          </div>
          @endforeach
        </div>
      @endif
    </div>

  </div>

  {{-- Pie Charts — full width row below bar chart --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    {{-- Pie 1: Metode Pembayaran --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h3 class="font-bold text-navy-deep text-sm mb-5">Metode Pembayaran</h3>
      @if($pieMetode->isEmpty())
        <p class="text-center text-gray-400 text-xs py-8">Belum ada data</p>
      @else
        <div class="flex justify-center mb-4">
          <canvas id="chartMetode" width="180" height="180"></canvas>
        </div>
        <div class="space-y-2 border-t border-gray-100 pt-3">
          @foreach($pieMetode as $metode => $total)
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 uppercase font-medium">{{ $metode }}</span>
            <span class="font-bold text-navy-mid">{{ $total }} transaksi</span>
          </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Pie 2: Status Pesanan --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h3 class="font-bold text-navy-deep text-sm mb-5">Status Pesanan</h3>
      @if($pieStatus->isEmpty())
        <p class="text-center text-gray-400 text-xs py-8">Belum ada data</p>
      @else
        <div class="flex justify-center mb-4">
          <canvas id="chartStatus" width="180" height="180"></canvas>
        </div>
        <div class="space-y-2 border-t border-gray-100 pt-3">
          @foreach($pieStatus as $status => $total)
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 capitalize font-medium">{{ $status }}</span>
            <span class="font-bold text-navy-mid">{{ $total }} pesanan</span>
          </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Pie 3: Kategori Event --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h3 class="font-bold text-navy-deep text-sm mb-5">Kategori Event</h3>
      @if($pieKategori->isEmpty())
        <p class="text-center text-gray-400 text-xs py-8">Belum ada data</p>
      @else
        <div class="flex justify-center mb-4">
          <canvas id="chartKategori" width="180" height="180"></canvas>
        </div>
        <div class="space-y-2 border-t border-gray-100 pt-3">
          @foreach($pieKategori as $kat => $total)
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 font-medium">{{ $kat }}</span>
            <span class="font-bold text-navy-mid">{{ $total }} event</span>
          </div>
          @endforeach
        </div>
      @endif
    </div>

  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- EO Terbaik --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h2 class="font-bold text-navy-deep mb-5">EO Terbaik</h2>
      @if($eoBest->isEmpty())
        <div class="text-center py-8 text-gray-400 text-sm">Belum ada data</div>
      @else
        @php $maxPend2 = $eoBest->max('total_pendapatan') ?: 1; @endphp
        <div class="space-y-4">
          @foreach($eoBest as $i => $eo)
          <div class="flex items-center gap-3">
            <span class="w-6 h-6 flex items-center justify-center text-sm font-extrabold {{ $i===0?'text-gold':($i===1?'text-gray-400':($i===2?'text-amber-600':'text-gray-300')) }}">
              {{ $i===0?'1':($i===1?'2':($i===2?'3':$i+1)) }}
            </span>
            <img src="{{ $eo->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <div class="flex justify-between items-center mb-1">
                <p class="font-semibold text-navy-deep text-xs truncate">{{ $eo->nama_panggilan }}</p>
                <p class="text-xs font-bold text-navy-mid ml-2 flex-shrink-0">Rp {{ number_format($eo->total_pendapatan/1000000,1) }}jt</p>
              </div>
              <div class="progress-bar">
                <div class="progress-fill" style="width:{{ ($eo->total_pendapatan/$maxPend2)*100 }}%"></div>
              </div>
              <p class="text-gray-400 text-xs mt-0.5">{{ $eo->events_count }} event</p>
            </div>
          </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Event Terpopuler --}}
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h2 class="font-bold text-navy-deep mb-5">Event Terpopuler</h2>
      @if($eventPopuler->isEmpty())
        <div class="text-center py-8 text-gray-400 text-sm">Belum ada data</div>
      @else
        <div class="space-y-3">
          @foreach($eventPopuler as $i => $ev)
          <div class="flex items-center gap-3">
            <span class="w-6 text-center text-xs font-bold text-gray-400">{{ $i+1 }}</span>
            <img src="{{ $ev->cover_url }}" class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-navy-deep text-xs truncate">{{ $ev->judul }}</p>
              <p class="text-gray-400 text-xs">{{ $ev->lokasi_kota }} · {{ $ev->orders_count }} pesanan</p>
            </div>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $ev->status==='published'?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500' }}">
              {{ $ev->status==='published'?'Live':'Draft' }}
            </span>
          </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  {{-- Pesanan Terbaru --}}
  <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-bold text-navy-deep">Pesanan Terbaru</h2>
      <a href="{{ route('admin.pesanan') }}" class="text-xs text-navy-mid font-semibold hover:text-gold">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="border-b border-gray-100">
          <tr>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Pembeli</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Event</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Total</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Waktu</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @foreach($pesananTerbaru as $order)
          <tr class="hover:bg-gray-50">
            <td class="py-3 pr-4">
              <div class="flex items-center gap-2">
                <img src="{{ $order->user->avatar_url }}" class="w-7 h-7 rounded-full object-cover">
                <span class="text-xs font-medium text-navy-deep">{{ $order->user->nama_panggilan }}</span>
              </div>
            </td>
            <td class="py-3 pr-4"><p class="text-xs text-gray-600 max-w-[160px] truncate">{{ $order->event->judul }}</p></td>
            <td class="py-3 pr-4"><p class="text-xs font-bold text-navy-deep">Rp {{ number_format($order->total_harga,0,',','.') }}</p></td>
            <td class="py-3 pr-4"><p class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</p></td>
            <td class="py-3">
              <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $order->status==='paid'?'badge-paid':($order->status==='pending'?'badge-pending':'badge-cancelled') }}">
                {{ $order->status==='paid'?'Lunas':($order->status==='pending'?'Pending':'Batal') }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>

@push('scripts')
<script>
const COLORS = ['#102A71','#F5C400','#3B82F6','#10B981','#EF4444','#8B5CF6','#F59E0B','#06B6D4','#EC4899'];

function makePie(id, labels, data) {
  const ctx = document.getElementById(id);
  if (!ctx) return;
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: COLORS.slice(0, data.length),
        borderWidth: 2,
        borderColor: '#ffffff',
        hoverOffset: 6,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: ctx => ` ${ctx.label}: ${ctx.raw} (${Math.round(ctx.raw/ctx.dataset.data.reduce((a,b)=>a+b,0)*100)}%)`
          }
        }
      },
      cutout: '60%',
    }
  });
}

@if(!$pieMetode->isEmpty())
makePie('chartMetode',
  {!! json_encode($pieMetode->keys()->map(fn($k) => strtoupper($k))->values()) !!},
  {!! json_encode($pieMetode->values()) !!}
);
@endif

@if(!$pieStatus->isEmpty())
makePie('chartStatus',
  {!! json_encode($pieStatus->keys()->map(fn($k) => ucfirst($k))->values()) !!},
  {!! json_encode($pieStatus->values()) !!}
);
@endif

@if(!$pieKategori->isEmpty())
makePie('chartKategori',
  {!! json_encode($pieKategori->keys()->values()) !!},
  {!! json_encode($pieKategori->values()) !!}
);
@endif
</script>
@endpush

@endsection
