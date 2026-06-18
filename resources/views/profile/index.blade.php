@extends('layouts.app')
@section('title', 'Profil Saya — TicketIn')

@push('styles')
<style>
  .tab-btn { padding:10px 16px; font-size:.875rem; font-weight:600; color:#6b7280; border-bottom:2px solid transparent; transition:all .2s; background:none; cursor:pointer; white-space:nowrap; }
  .tab-btn.active { color:#102A71; border-bottom-color:#F5C400; }
  .tab-panel { display:none; }
  .tab-panel.active { display:block; }
  .badge-paid { background:rgba(34,197,94,.15); color:#16a34a; border:1px solid rgba(34,197,94,.3); }
  .badge-pending { background:rgba(234,179,8,.15); color:#b45309; border:1px solid rgba(234,179,8,.3); }
  .badge-cancelled { background:rgba(239,68,68,.15); color:#dc2626; border:1px solid rgba(239,68,68,.3); }
  .eticket-wrap .ticket-body { background:linear-gradient(135deg,#102A71 0%,#001840 100%); border-radius:20px; overflow:visible; position:relative; color:white; }
  .eticket-wrap .ticket-body::before { content:''; position:absolute; top:50%; left:-10px; width:20px; height:20px; background:#f3f4f6; border-radius:50%; transform:translateY(-50%); z-index:2; }
  .eticket-wrap .ticket-body::after  { content:''; position:absolute; top:50%; right:-10px; width:20px; height:20px; background:#f3f4f6; border-radius:50%; transform:translateY(-50%); z-index:2; }
  .ticket-dashed { border-top:2px dashed rgba(255,255,255,.2); margin:14px 0; }
  .qr-canvas canvas { border-radius:8px; width:96px!important; height:96px!important; }
  .ticket-qr canvas { width:72px!important; height:72px!important; }
  .ticket-code { overflow-wrap:anywhere; }
  .modal-ticket-body { padding:12px 20px 20px; display:flex; flex-direction:column; gap:16px; }
  #modal-qr-box { justify-content:flex-start; max-width:none; }
  @media (min-width:640px) {
    .ticket-qr canvas { width:96px!important; height:96px!important; }
    .modal-ticket-body { flex-direction:row; align-items:center; justify-content:space-between; }
    #modal-qr-box { justify-content:flex-end; max-width:190px; }
  }
  .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:100; display:flex; align-items:center; justify-content:center; padding:20px; opacity:0; pointer-events:none; transition:opacity .3s; }
  .modal-overlay.show { opacity:1; pointer-events:all; }
  .modal-box { background:white; border-radius:24px; width:100%; max-width:420px; max-height:90vh; overflow-y:auto; transform:scale(.95); transition:transform .3s; }
  .modal-overlay.show .modal-box { transform:scale(1); }
  .confirm-modal { position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:110; display:flex; align-items:center; justify-content:center; padding:20px; opacity:0; pointer-events:none; transition:opacity .3s; }
  .confirm-modal.show { opacity:1; pointer-events:all; }
  @media print {
    @page { size: A4 portrait; margin: 12mm; }
    html,
    body { width:100%!important; height:auto!important; min-height:0!important; margin:0!important; padding:0!important; overflow:visible!important; background:white!important; }
    body > header,
    body > footer,
    main > :not(#tiket-modal) { display:none!important; }
    main { display:block!important; margin:0!important; padding:0!important; }
    #tiket-modal { position:static!important; display:block!important; width:100%!important; min-height:0!important; background:white!important; padding:0!important; margin:0!important; opacity:1!important; pointer-events:none!important; }
    #tiket-modal .modal-box { position:static!important; transform:none!important; width:100%!important; max-width:420px!important; max-height:none!important; overflow:visible!important; margin:0 auto!important; box-shadow:none!important; border-radius:0!important; background:white!important; }
    #tiket-modal .modal-box > .flex,
    #tiket-modal .modal-box > .p-5 > :not(.print-area),
    #tiket-modal .print-hide { display:none!important; }
    #tiket-modal .modal-box > .p-5 { padding:0!important; }
    #tiket-modal .print-area { page-break-inside:avoid!important; break-inside:avoid!important; margin:0!important; box-shadow:none!important; }
  }
</style>
@endpush

@section('content')
<div class="pt-24 max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-10">



  {{-- PROFILE CARD --}}
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
      <div class="relative flex-shrink-0">
        <img src="{{ $user->avatar_url }}" class="w-24 h-24 rounded-2xl object-cover shadow-sm bg-gray-100">
        <a href="{{ route('profile.edit') }}" class="absolute -bottom-2 -right-2 w-8 h-8 bg-gold rounded-full flex items-center justify-center shadow hover:bg-gold-light transition-all">
          <svg class="w-4 h-4 text-navy-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
        </a>
      </div>
      <div class="flex-1 text-center sm:text-left">
        <h1 class="text-2xl font-extrabold text-navy-deep">{{ $user->nama_lengkap }}</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $user->email }}</p>
        @if($user->no_hp)<p class="text-gray-400 text-sm">+62{{ $user->no_hp }}</p>@endif
        <div class="flex items-center justify-center sm:justify-start gap-2 mt-3">
          <span class="text-xs font-bold px-3 py-1 rounded-full {{ $user->role==='pengelola' ? 'bg-gold/20 text-yellow-700 border border-gold/40' : ($user->role==='admin' ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-navy-mid/10 text-navy-mid border border-navy-mid/20') }}">
            {{ $user->role==='pengelola' ? ' Pengelola Event' : ($user->role==='admin' ? ' Admin' : ' Pembeli') }}
          </span>
          <span class="text-gray-400 text-xs">Bergabung {{ $user->created_at->format('M Y') }}</span>
        </div>
      </div>
      <div class="flex gap-3 flex-shrink-0">
        <a href="{{ route('profile.edit') }}" class="bg-gold text-navy-deep font-bold px-4 py-2 rounded-xl hover:bg-gold-light transition-all text-sm shadow-sm">Edit Profil</a>
        @if($user->isPengelola())
        <a href="{{ route('pengelola.dashboard') }}" class="bg-navy-mid text-white font-bold px-4 py-2 rounded-xl hover:bg-navy-deep transition-all text-sm">Dashboard EO</a>
        @endif
      </div>
    </div>
    <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-100">
      <div class="text-center">
        <p class="text-2xl font-extrabold text-navy-deep">{{ $orders->count() }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Pesanan</p>
      </div>
      <div class="text-center">
        <p class="text-2xl font-extrabold text-gold">{{ $orders->where('status','paid')->sum('total_qty') }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Tiket Aktif</p>
      </div>
      <div class="text-center">
        <p class="text-2xl font-extrabold text-navy-deep">{{ $orders->sum('total_qty') }}</p>
        <p class="text-gray-400 text-xs mt-0.5">Total Tiket</p>
      </div>
    </div>
    <div class="grid grid-cols-1 mt-4 pt-4 border-t border-gray-100">
      <div class="text-center">
        <p class="text-2xl font-extrabold text-red-400">{{ $wishlists->count() }}</p>
        <p class="text-gray-400 text-xs mt-0.5"> Event Favorit</p>
      </div>
    </div>
  </div>

  {{-- TABS --}}
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex border-b border-gray-100 px-6 gap-2 overflow-x-auto">
      <button class="tab-btn active" onclick="switchTab('tiket')"> Tiket Saya</button>
      <button class="tab-btn" onclick="switchTab('riwayat')"> Riwayat Pesanan</button>
      <button class="tab-btn" onclick="switchTab('favorit')"> Favorit</button>
    </div>

    {{-- TAB TIKET SAYA --}}
    <div class="tab-panel active p-6" id="tab-tiket">
      @php $paidOrders = $orders->where('status','paid'); @endphp
      @if($paidOrders->isEmpty())
      <div class="py-16 text-center">
        <div class="text-6xl mb-4"></div>
        <h3 class="text-lg font-bold text-navy-deep mb-2">Belum Ada Tiket Aktif</h3>
        <p class="text-gray-500 text-sm mb-5">Tiket yang sudah dibayar akan muncul di sini.</p>
        <a href="{{ route('events.index') }}" class="inline-block bg-gold text-navy-deep font-bold px-6 py-3 rounded-xl text-sm hover:bg-gold-light transition-all">Cari Event Sekarang</a>
      </div>
      @else
      <div class="space-y-6">
        @foreach($paidOrders as $order)
        @php
          $tickets = [];
          $ticketIndex = 0;
          foreach ($order->items as $item) {
              for ($unit = 1; $unit <= $item->qty; $unit++) {
                  $ticketIndex++;
                  $tickets[] = [
                      'code' => $order->order_code . '-' . str_pad($ticketIndex, 3, '0', STR_PAD_LEFT),
                      'category' => $item->ticketCategory->nama_kategori ?? 'Tiket',
                      'price' => 'Rp ' . number_format($item->harga_satuan, 0, ',', '.'),
                  ];
              }
          }
        @endphp
        <div class="eticket-wrap">
          <div class="ticket-body shadow-xl">
            <div class="p-5 pb-0">
              <div class="flex justify-between items-start mb-3">
                <div class="flex-1 min-w-0 pr-4">
                  <p class="text-white/50 text-xs uppercase tracking-wider mb-1">E-Ticket TicketIn</p>
                  <h3 class="text-lg font-extrabold text-white leading-tight line-clamp-2">{{ $order->event->judul }}</h3>
                </div>
                <span class="bg-green-500/20 text-green-400 border border-green-400/30 text-xs font-extrabold px-3 py-1 rounded-full flex-shrink-0 uppercase tracking-wider">VALID</span>
              </div>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                <div class="bg-white/5 rounded-xl p-3">
                  <p class="text-white/40 text-xs mb-1"> Tanggal</p>
                  <p class="text-white font-bold text-xs">{{ $order->event->tanggal_waktu->format('d M Y') }}</p>
                </div>
                <div class="bg-white/5 rounded-xl p-3">
                  <p class="text-white/40 text-xs mb-1"> Waktu</p>
                  <p class="text-white font-bold text-xs">{{ $order->event->tanggal_waktu->format('H:i') }} WIB</p>
                </div>
                <div class="bg-white/5 rounded-xl p-3">
                  <p class="text-white/40 text-xs mb-1"> Lokasi</p>
                  <p class="text-white font-bold text-xs truncate">{{ $order->event->lokasi_kota }}</p>
                </div>
                <div class="bg-white/5 rounded-xl p-3">
                  <p class="text-white/40 text-xs mb-1"> Jumlah</p>
                  <p class="text-white font-bold text-xs">{{ $order->total_qty }} Tiket</p>
                </div>
              </div>
              <div class="flex flex-wrap gap-2 mb-4">
                @foreach($order->items as $item)
                <span class="bg-gold/20 text-gold border border-gold/30 text-xs font-semibold px-3 py-1 rounded-full">{{ $item->qty }}x {{ $item->ticketCategory->nama_kategori }}</span>
                @endforeach
              </div>
            </div>
            <div class="px-5"><div class="ticket-dashed"></div></div>
            <div class="p-5 pt-0 flex flex-col lg:flex-row lg:items-start justify-between gap-4">
              <div class="flex-1 min-w-0">
                <p class="text-white/50 text-xs mb-1">Nama Pemesan</p>
                <p class="text-white font-bold text-sm truncate">{{ $user->nama_lengkap }}</p>
                <p class="text-white/40 text-xs mt-2 mb-0.5">Kode Order</p>
                <p class="font-mono font-extrabold text-gold tracking-widest text-sm">{{ $order->order_code }}</p>
                <p class="text-white/30 text-xs mt-3">Venue: {{ $order->event->venue }}</p>
              </div>
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3 w-full lg:w-auto flex-shrink-0">
                @foreach($tickets as $ticket)
                <div class="flex flex-col items-center gap-1 bg-white/5 border border-white/10 rounded-xl p-2 min-w-0">
                  <div class="qr-canvas ticket-qr" id="qr-{{ $ticket['code'] }}"></div>
                  <p class="ticket-code font-mono text-gold text-[10px] font-bold text-center leading-tight">{{ $ticket['code'] }}</p>
                  <p class="text-white/40 text-[10px] text-center">{{ $ticket['category'] }}</p>
                </div>
                @endforeach
              </div>
            </div>
          </div>

          {{-- Tombol aksi tiket --}}
          <div class="flex gap-2 mt-3">
            <button onclick="printTiket({{ $order->id }})" class="w-full bg-white border border-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition-all text-sm flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
              Print
            </button>
          </div>

          {{-- Data tersembunyi untuk modal --}}
          <div id="order-data-{{ $order->id }}" class="hidden"
               data-judul="{{ $order->event->judul }}"
               data-tanggal="{{ $order->event->tanggal_waktu->format('d M Y') }}"
               data-waktu="{{ $order->event->tanggal_waktu->format('H:i') }}"
               data-lokasi="{{ $order->event->lokasi_kota }}"
               data-venue="{{ $order->event->venue }}"
               data-qty="{{ $order->total_qty }}"
               data-summary="{{ $order->ticket_summary }}"
               data-kode="{{ $order->order_code }}"
               data-nama="{{ $user->nama_lengkap }}"
               data-total="Rp {{ number_format($order->total_harga,0,',','.') }}"
               data-tickets='@json($tickets)'
               data-cover="{{ $order->event->cover_url }}">
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>

    {{-- TAB RIWAYAT PESANAN --}}
    <div class="tab-panel p-6" id="tab-riwayat">
      @if($orders->isEmpty())
      <div class="py-16 text-center">
        <div class="text-5xl mb-4"></div>
        <p class="text-gray-500 mb-4">Belum ada pesanan.</p>
        <a href="{{ route('events.index') }}" class="inline-block bg-gold text-navy-deep font-bold px-6 py-3 rounded-xl text-sm">Jelajahi Event</a>
      </div>
      @else
      <div class="space-y-4">
        @foreach($orders as $order)
        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 hover:border-navy-mid/20 transition-colors">
          <div class="flex flex-col sm:flex-row items-start gap-4">
            <img src="{{ $order->event->cover_url }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <p class="font-bold text-navy-deep text-sm truncate">{{ $order->event->judul }}</p>
              <p class="text-gray-400 text-xs mt-0.5">{{ $order->event->tanggal_waktu->format('d M Y') }} · {{ $order->event->lokasi_kota }}</p>
              <p class="text-gray-500 text-xs mt-1">{{ $order->ticket_summary }}</p>
              <div class="flex items-center gap-3 mt-2">
                <span class="font-mono text-xs text-navy-mid font-bold bg-navy-mid/10 px-2 py-0.5 rounded-lg">{{ $order->order_code }}</span>
                <span class="text-gray-300 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</span>
              </div>
            </div>
            <div class="flex sm:flex-col items-center sm:items-end gap-3 flex-shrink-0 w-full sm:w-auto">
              <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $order->status==='paid' ? 'badge-paid' : ($order->status==='pending' ? 'badge-pending' : 'badge-cancelled') }}">
                {{ $order->status==='paid' ? ' Lunas' : ($order->status==='pending' ? ' Menunggu Bayar' : ' Dibatalkan') }}
              </span>
              <p class="font-bold text-navy-deep text-sm">Rp {{ number_format($order->total_harga,0,',','.') }}</p>
            </div>
          </div>

          {{-- Aksi berdasarkan status --}}
          @if($order->status === 'pending')
          <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row gap-3">
              <div class="flex-1 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 flex items-center gap-3">
                <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                  <p class="text-yellow-700 font-bold text-xs">Menunggu Pembayaran</p>
                  <p class="text-yellow-600 text-xs">Via {{ $order->metode_bayar }}</p>
                </div>
              </div>
              <div class="flex gap-2">
                <form method="POST" action="{{ route('profile.cek-bayar', $order) }}">
                  @csrf
                  <button type="submit" class="bg-navy-mid hover:bg-navy-deep text-white font-bold px-4 py-2.5 rounded-xl transition-all text-sm flex items-center gap-2 whitespace-nowrap h-full">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Konfirmasi Bayar
                  </button>
                </form>
                <button onclick="konfirmasiBatal('{{ $order->order_code }}', {{ $order->id }})" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold px-4 py-2.5 rounded-xl transition-all text-sm flex items-center gap-2 border border-red-200 whitespace-nowrap">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                  Batalkan
                </button>
              </div>
            </div>
          </div>

          @elseif($order->status === 'paid')
          <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-2 text-green-600 text-sm">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              <span class="font-semibold">Pembayaran Lunas</span>
            </div>
            <button onclick="switchTab('tiket')" class="bg-gold text-navy-deep font-bold px-4 py-2 rounded-xl hover:bg-gold-light transition-all text-sm flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
              Lihat E-Ticket
            </button>
          </div>

          @else
          <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-3 flex items-center gap-3">
              <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <div>
                <p class="text-red-600 font-bold text-xs">Pesanan Dibatalkan</p>
                <p class="text-red-400 text-xs">Pesanan ini telah dibatalkan dan tidak dapat dipulihkan.</p>
              </div>
            </div>
          </div>
          @endif

        </div>
        @endforeach
      </div>
      @endif
    </div>

    {{-- TAB FAVORIT --}}
    <div class="tab-panel p-6" id="tab-favorit">
      @if($wishlists->isEmpty())
        <div class="py-16 text-center">
          <div class="text-6xl mb-4"></div>
          <h3 class="text-lg font-bold text-navy-deep mb-2">Belum Ada Event Favorit</h3>
          <p class="text-gray-500 text-sm mb-5">Tap ikon  di card event untuk menyimpan ke sini.</p>
          <a href="{{ route('events.index') }}" class="inline-block bg-gold text-navy-deep font-bold px-6 py-3 rounded-xl text-sm hover:bg-gold-light transition-all">Jelajahi Event</a>
        </div>
      @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($wishlists as $fav)
          <a href="{{ route('events.show', $fav) }}"
             class="flex gap-4 bg-gray-50 hover:bg-white border border-gray-100 hover:border-navy-mid/20 rounded-2xl p-4 transition-all group shadow-sm hover:shadow-md">
            <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-200">
              <img src="{{ $fav->cover_url }}"
                   onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400&q=80'"
                   alt="{{ $fav->judul }}"
                   class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <div class="flex-1 min-w-0">
              <span class="text-xs font-semibold text-navy-mid bg-navy-mid/10 px-2 py-0.5 rounded-full">{{ $fav->kategori }}</span>
              <p class="font-bold text-navy-deep text-sm mt-1.5 leading-snug line-clamp-2">{{ $fav->judul }}</p>
              <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $fav->tanggal_waktu->format('d M Y') }}
              </p>
              <p class="text-xs text-gray-400 flex items-center gap-1">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $fav->lokasi_kota }}
              </p>
              <p class="text-xs font-bold text-navy-mid mt-1">
                @if($fav->harga_termurah == 0) <span class="text-green-600">GRATIS</span>
                @else Rp {{ number_format($fav->harga_termurah, 0, ',', '.') }} @endif
              </p>
            </div>
            <div class="flex-shrink-0 self-start">
              <button
                class="wish-toggle w-7 h-7 rounded-full bg-red-50 flex items-center justify-center hover:bg-red-100 transition"
                data-event-id="{{ $fav->id }}"
                data-url="{{ route('wishlist.toggle', $fav) }}"
                onclick="event.preventDefault(); event.stopPropagation(); toggleWish(this)"
                title="Hapus dari Favorit">
                <svg class="w-3.5 h-3.5 wish-icon text-red-500" fill="#ef4444" stroke="#ef4444" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
              </button>
            </div>
          </a>
          @endforeach
        </div>
      @endif
    </div>

  </div>
</div>

{{-- MODAL DETAIL TIKET --}}
<div class="modal-overlay" id="tiket-modal" onclick="tutupModal(event)">
  <div class="modal-box">
    <div class="flex items-center justify-between p-5 border-b border-gray-100">
      <h3 class="font-extrabold text-navy-deep">Detail E-Ticket</h3>
      <button onclick="document.getElementById('tiket-modal').classList.remove('show')" class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-all">
        <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="p-5">
      <img id="m-cover" src="" alt="" class="w-full h-36 object-cover rounded-xl mb-4">
      <div class="print-area" style="background:linear-gradient(135deg,#102A71,#001840);border-radius:16px;color:white;position:relative;overflow:visible;">
        <div style="position:absolute;top:50%;left:-8px;width:16px;height:16px;background:#f3f4f6;border-radius:50%;transform:translateY(-50%);z-index:2;"></div>
        <div style="position:absolute;top:50%;right:-8px;width:16px;height:16px;background:#f3f4f6;border-radius:50%;transform:translateY(-50%);z-index:2;"></div>
        <div style="padding:20px 20px 12px;">
          <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:12px;">
            <div style="flex:1;min-width:0;padding-right:12px;">
              <p style="font-size:10px;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px;">E-Ticket TicketIn</p>
              <p id="m-judul" style="font-size:15px;font-weight:800;line-height:1.3;"></p>
            </div>
            <span style="background:rgba(34,197,94,.2);color:#4ade80;border:1px solid rgba(34,197,94,.3);font-size:10px;font-weight:800;padding:4px 10px;border-radius:20px;flex-shrink:0;">VALID</span>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px;">
            <div style="background:rgba(255,255,255,.07);border-radius:10px;padding:10px;"><p style="color:rgba(255,255,255,.4);font-size:10px;margin-bottom:3px;"> Tanggal</p><p id="m-tanggal" style="font-weight:700;font-size:12px;"></p></div>
            <div style="background:rgba(255,255,255,.07);border-radius:10px;padding:10px;"><p style="color:rgba(255,255,255,.4);font-size:10px;margin-bottom:3px;"> Waktu</p><p id="m-waktu" style="font-weight:700;font-size:12px;"></p></div>
            <div style="background:rgba(255,255,255,.07);border-radius:10px;padding:10px;"><p style="color:rgba(255,255,255,.4);font-size:10px;margin-bottom:3px;"> Lokasi</p><p id="m-lokasi" style="font-weight:700;font-size:12px;"></p></div>
            <div style="background:rgba(255,255,255,.07);border-radius:10px;padding:10px;"><p style="color:rgba(255,255,255,.4);font-size:10px;margin-bottom:3px;"> Jumlah</p><p id="m-qty" style="font-weight:700;font-size:12px;"></p></div>
          </div>
          <div id="m-summary-wrap" style="margin-bottom:12px;"></div>
        </div>
        <div style="padding:0 20px;"><div style="border-top:2px dashed rgba(255,255,255,.2);margin:4px 0;"></div></div>
        <div class="modal-ticket-body">
          <div style="flex:1;min-width:0;">
            <p style="color:rgba(255,255,255,.4);font-size:10px;margin-bottom:3px;">Pemesan</p>
            <p id="m-nama" style="font-weight:700;font-size:13px;"></p>
            <p style="color:rgba(255,255,255,.4);font-size:10px;margin-top:10px;margin-bottom:3px;">Kode Order</p>
            <p id="m-kode" style="font-family:monospace;font-weight:800;color:#F5C400;letter-spacing:.1em;font-size:13px;"></p>
            <p style="color:rgba(255,255,255,.3);font-size:10px;margin-top:8px;">Venue: <span id="m-venue"></span></p>
            <p id="m-total" style="color:rgba(255,255,255,.5);font-size:11px;margin-top:4px;"></p>
          </div>
          <div id="modal-qr-box" style="display:flex;flex-wrap:wrap;gap:8px;flex-shrink:0;"></div>
        </div>
      </div>
      <div class="print-hide mt-4 bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-700 flex gap-2 items-start">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Tunjukkan QR Code ini kepada petugas di pintu masuk event.
      </div>
      <div class="print-hide flex gap-3 mt-4">
        <button onclick="window.print()" class="flex-1 bg-gray-100 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-200 transition-all text-sm flex items-center justify-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
          Print
        </button>
        <button id="share-btn" class="flex-1 bg-gold text-navy-deep font-bold py-3 rounded-xl hover:bg-gold-light transition-all text-sm flex items-center justify-center gap-2">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
          Bagikan
        </button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL KONFIRMASI BATALKAN --}}
<div class="confirm-modal" id="batal-modal">
  <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl">
    <div class="text-center mb-5">
      <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <svg class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      </div>
      <h3 class="text-lg font-extrabold text-navy-deep mb-2">Batalkan Pesanan?</h3>
      <p class="text-gray-500 text-sm">Kamu yakin ingin membatalkan pesanan</p>
      <p id="batal-kode" class="font-mono font-bold text-navy-mid mt-1 text-sm"></p>
      <p class="text-gray-400 text-xs mt-2">Pesanan yang dibatalkan tidak dapat dipulihkan.</p>
    </div>
    <div class="flex gap-3">
      <button onclick="tutupBatalModal()" class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-200 transition-all text-sm">Tidak, Kembali</button>
      <form id="batal-form" method="POST" class="flex-1">
        @csrf
        <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 transition-all text-sm">Ya, Batalkan</button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Generate QR code untuk setiap tiket
  if (typeof QRCode === 'undefined') return;

  document.querySelectorAll('[id^="qr-"]').forEach(function(el) {
    const code = el.id.replace('qr-', '');
    new QRCode(el, {
      text: code,
      width: 96,
      height: 96,
      colorDark: '#102A71',
      colorLight: '#ffffff',
      correctLevel: QRCode.CorrectLevel.M
    });
  });
});
</script>
<script>
  function switchTab(name) {
    const names = ['tiket','riwayat','favorit'];
    document.querySelectorAll('.tab-btn').forEach((b,i) => b.classList.toggle('active', names[i]===name));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-'+name).classList.add('active');
  }

  function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value;
    return div.innerHTML;
  }

  function lihatTiket(id) {
    const d = document.getElementById('order-data-'+id).dataset;
    const tickets = JSON.parse(d.tickets || '[]');
    document.getElementById('m-cover').src           = d.cover;
    document.getElementById('m-judul').textContent   = d.judul;
    document.getElementById('m-tanggal').textContent = d.tanggal;
    document.getElementById('m-waktu').textContent   = d.waktu + ' WIB';
    document.getElementById('m-lokasi').textContent  = d.lokasi;
    document.getElementById('m-qty').textContent     = d.qty + ' Tiket';
    document.getElementById('m-nama').textContent    = d.nama;
    document.getElementById('m-kode').textContent    = d.kode;
    document.getElementById('m-venue').textContent   = d.venue;
    document.getElementById('m-total').textContent   = 'Total: ' + d.total;
    document.getElementById('m-summary-wrap').innerHTML = (d.summary || '').split(',').filter(Boolean).map(s =>
      '<span style="background:rgba(245,196,0,.2);color:#F5C400;border:1px solid rgba(245,196,0,.3);font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;display:inline-block;margin:2px;">'+escapeHtml(s.trim())+'</span>'
    ).join('');
    document.getElementById('share-btn').onclick = () => bagikanTiket(d.kode, d.judul);
    // Generate QR code untuk tiap tiket dalam satu order.
    const mqrEl = document.getElementById('modal-qr-box');
    if (mqrEl) {
      mqrEl.innerHTML = '';
      tickets.forEach(ticket => {
        const wrap = document.createElement('div');
        wrap.style.cssText = 'width:86px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:5px;text-align:center;';
        const qr = document.createElement('div');
        qr.style.cssText = 'width:64px;height:64px;margin:0 auto 4px;border-radius:6px;overflow:hidden;';
        const code = document.createElement('p');
        code.style.cssText = 'font-family:monospace;color:#F5C400;font-size:9px;font-weight:800;line-height:1.2;word-break:break-all;';
        code.textContent = ticket.code;
        const category = document.createElement('p');
        category.style.cssText = 'color:rgba(255,255,255,.45);font-size:8px;line-height:1.2;margin-top:2px;';
        category.textContent = ticket.category;
        wrap.appendChild(qr);
        wrap.appendChild(code);
        wrap.appendChild(category);
        mqrEl.appendChild(wrap);

        if (typeof QRCode !== 'undefined') {
          new QRCode(qr, {
            text: ticket.code,
            width: 64,
            height: 64,
            colorDark: '#102A71',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M
          });
        } else {
          qr.innerHTML = '<div style="width:64px;height:64px;background:white;color:#102A71;border-radius:6px;display:flex;align-items:center;justify-content:center;text-align:center;font-size:8px;font-weight:800;padding:6px;">'+escapeHtml(ticket.code)+'</div>';
        }
      });
    }
    document.getElementById('tiket-modal').classList.add('show');
  }

  function tutupModal(e) {
    if (e.target === document.getElementById('tiket-modal')) {
      document.getElementById('tiket-modal').classList.remove('show');
    }
  }

  function printTiket(id) {
    lihatTiket(id);
    setTimeout(() => window.print(), 400);
  }

  function bagikanTiket(kode, judul) {
    const text = 'Tiket ' + judul + '\nKode: ' + kode + '\nPlatform: TicketIn';
    if (navigator.share) {
      navigator.share({ title: 'E-Ticket TicketIn', text: text });
    } else {
      navigator.clipboard.writeText(text);
      alert('Info tiket disalin ke clipboard!');
    }
  }

  function konfirmasiBatal(kode, orderId) {
    document.getElementById('batal-kode').textContent = kode;
    document.getElementById('batal-form').action = '/profile/pesanan/' + orderId + '/batalkan';
    document.getElementById('batal-modal').classList.add('show');
  }

  function tutupBatalModal() {
    document.getElementById('batal-modal').classList.remove('show');
  }

  document.getElementById('batal-modal').addEventListener('click', function(e) {
    if (e.target === this) tutupBatalModal();
  });
</script>
@endpush
