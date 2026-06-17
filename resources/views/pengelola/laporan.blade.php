@extends('layouts.pengelola')
@section('title', 'Laporan Penjualan')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

  <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
      <h1 class="text-2xl font-extrabold text-navy-deep"> Laporan Penjualan Event Saya</h1>
      <p class="text-sm text-gray-500 mt-0.5">Rekap penjualan tiket semua event yang kamu kelola</p>
    </div>
    <a href="{{ route('pengelola.laporan.export', request()->query()) }}"
       class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold text-sm px-5 py-2.5 rounded-xl transition shadow">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
      Download Excel
    </a>
  </div>

  {{-- Info biaya platform --}}
  <div class="bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 mb-4 text-sm text-blue-700 flex items-center gap-2">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    Pendapatan kamu adalah harga tiket (subtotal). Biaya layanan 5% dipotong oleh platform TicketIn.
  </div>

  {{-- Summary Cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
      ['label'=>'Pendapatan Kamu',    'value'=>$summary['total_pendapatan'],     'fmt'=>'rupiah', 'color'=>'green'],
      ['label'=>'Biaya Platform (5%)','value'=>$summary['total_biaya_platform'], 'fmt'=>'rupiah', 'color'=>'blue'],
      ['label'=>'Tiket Terjual',      'value'=>$summary['total_tiket'],          'fmt'=>'number', 'color'=>'purple'],
      ['label'=>'Menunggu Konfirmasi','value'=>$summary['pending'],              'fmt'=>'number', 'color'=>'amber'],
    ] as $card)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
      <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</p>
      <p class="text-2xl font-extrabold mt-1
        {{ $card['color']==='green' ? 'text-green-600' :
           ($card['color']==='amber' ? 'text-amber-500' :
           ($card['color']==='purple'? 'text-purple-600' : 'text-navy-mid')) }}">
        @if($card['fmt']==='rupiah')
          Rp {{ number_format($card['value'],0,',','.') }}
        @else
          {{ number_format($card['value']) }}
        @endif
      </p>
    </div>
    @endforeach
  </div>

  {{-- Filter --}}
  <form method="GET" action="{{ route('pengelola.laporan') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
      <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Event</label>
        <select name="event_id" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-navy-mid">
          <option value="">Semua Event</option>
          @foreach($events as $ev)
          <option value="{{ $ev->id }}" {{ request('event_id')==$ev->id?'selected':'' }}>{{ Str::limit($ev->judul,30) }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Status</label>
        <select name="status" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-navy-mid">
          <option value="">Semua Status</option>
          <option value="paid"      {{ request('status')==='paid'?'selected':'' }}>Paid</option>
          <option value="pending"   {{ request('status')==='pending'?'selected':'' }}>Pending</option>
          <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Cancelled</option>
        </select>
      </div>
      <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Metode Bayar</label>
        <select name="metode" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-navy-mid">
          <option value="">Semua Metode</option>
          @foreach(['bca','bni','bri','mandiri','gopay','ovo','dana','qris'] as $m)
          <option value="{{ $m }}" {{ request('metode')===$m?'selected':'' }}>{{ strtoupper($m) }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Dari Tanggal</label>
        <input type="date" name="dari" value="{{ request('dari') }}"
               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-navy-mid">
      </div>
      <div>
        <label class="text-xs font-semibold text-gray-500 block mb-1">Sampai Tanggal</label>
        <input type="date" name="sampai" value="{{ request('sampai') }}"
               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-navy-mid">
      </div>
      <div class="flex items-end gap-2">
        <button type="submit" class="flex-1 bg-navy-mid text-white font-semibold text-sm py-2 rounded-xl hover:bg-navy-deep transition">Filter</button>
        <a href="{{ route('pengelola.laporan') }}" class="px-3 py-2 border border-gray-200 text-gray-500 rounded-xl hover:bg-gray-50 transition text-sm">Reset</a>
      </div>
    </div>
  </form>

  {{-- Table --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-navy-deep text-white">
            <th class="px-4 py-3 text-left font-semibold text-xs">Kode Order</th>
            <th class="px-4 py-3 text-left font-semibold text-xs">Pembeli</th>
            <th class="px-4 py-3 text-left font-semibold text-xs">Event</th>
            <th class="px-4 py-3 text-left font-semibold text-xs">Tiket</th>
            <th class="px-4 py-3 text-right font-semibold text-xs">Harga Tiket</th>
            <th class="px-4 py-3 text-right font-semibold text-xs">Biaya Platform</th>
            <th class="px-4 py-3 text-right font-semibold text-xs">Pendapatan Kamu</th>
            <th class="px-4 py-3 text-left font-semibold text-xs">Metode</th>
            <th class="px-4 py-3 text-left font-semibold text-xs">Status</th>
            <th class="px-4 py-3 text-left font-semibold text-xs">Tanggal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($orders as $order)
          <tr class="hover:bg-gray-50 transition">
            <td class="px-4 py-3 font-mono text-xs text-navy-mid font-semibold">{{ $order->order_code }}</td>
            <td class="px-4 py-3">
              <p class="font-semibold text-gray-800 text-xs">{{ $order->user->nama_lengkap ?? '-' }}</p>
              <p class="text-gray-400 text-xs">{{ $order->user->email ?? '-' }}</p>
            </td>
            <td class="px-4 py-3 text-xs text-gray-700 max-w-[180px]">
              <p class="font-semibold truncate">{{ $order->event->judul ?? '-' }}</p>
              <p class="text-gray-400">{{ $order->event->tanggal_waktu?->format('d M Y') }}</p>
            </td>
            <td class="px-4 py-3 text-xs text-gray-600">{{ $order->ticket_summary }}</td>
            <td class="px-4 py-3 text-right text-xs text-gray-600">Rp {{ number_format($order->subtotal,0,',','.') }}</td>
            <td class="px-4 py-3 text-right text-xs text-red-500 font-semibold">- Rp {{ number_format($order->biaya_layanan,0,',','.') }}</td>
            <td class="px-4 py-3 text-right font-bold text-xs text-green-600">Rp {{ number_format($order->pendapatan_eo,0,',','.') }}</td>
            <td class="px-4 py-3"><span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2 py-0.5 rounded-full uppercase">{{ $order->metode_bayar }}</span></td>
            <td class="px-4 py-3">
              <span class="text-xs font-bold px-2 py-0.5 rounded-full
                {{ $order->status==='paid'?'bg-green-100 text-green-700':
                   ($order->status==='pending'?'bg-amber-100 text-amber-700':'bg-red-100 text-red-600') }}">
                {{ ucfirst($order->status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
          </tr>
          @empty
          <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400">Tidak ada data pesanan.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="px-4 py-4 border-t border-gray-100">{{ $orders->withQueryString()->links() }}</div>
  </div>
</div>
@endsection
