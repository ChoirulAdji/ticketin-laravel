@extends('layouts.admin')
@section('title','Semua Pesanan')

@push('styles')
<style>
  .badge-paid      { background:rgba(34,197,94,.15); color:#16a34a; border:1px solid rgba(34,197,94,.3); }
  .badge-pending   { background:rgba(234,179,8,.15); color:#b45309; border:1px solid rgba(234,179,8,.3); }
  .badge-cancelled { background:rgba(239,68,68,.15); color:#dc2626; border:1px solid rgba(239,68,68,.3); }
</style>
@endpush

@section('content')
<div class="p-6 max-w-7xl mx-auto">

  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-extrabold text-navy-deep">Semua Pesanan</h1>
      <p class="text-gray-400 text-sm mt-0.5">Total {{ $orders->total() }} pesanan</p>
    </div>
    <form method="GET">
      <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none bg-white">
        <option value="">Semua Status</option>
        <option value="pending"   {{ request('status')==='pending'?'selected':'' }}>⏳ Pending</option>
        <option value="paid"      {{ request('status')==='paid'?'selected':'' }}>✅ Lunas</option>
        <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>❌ Dibatalkan</option>
      </select>
    </form>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    @foreach([
      ['Total',$stats['total'],'text-navy-deep','bg-white'],
      ['✅ Lunas',$stats['paid'],'text-green-700','bg-green-50'],
      ['⏳ Pending',$stats['pending'],'text-yellow-700','bg-yellow-50'],
      ['❌ Dibatalkan',$stats['cancelled'],'text-red-700','bg-red-50'],
      ['💰 Pendapatan','Rp '.number_format($stats['pendapatan']/1000000,1).'jt','text-navy-mid','bg-navy-mid/5'],
    ] as [$l,$v,$c,$bg])
    <div class="{{ $bg }} border border-gray-100 rounded-2xl p-4 shadow-sm text-center">
      <p class="text-xl font-extrabold {{ $c }}">{{ $v }}</p>
      <p class="text-gray-400 text-xs mt-1">{{ $l }}</p>
    </div>
    @endforeach
  </div>

  <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
    @if($orders->isEmpty())
      <div class="py-20 text-center text-gray-400">
        <div class="text-4xl mb-3">📭</div><p>Tidak ada pesanan</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-6 py-3">Pembeli</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Event</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Kode</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Total</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Tanggal</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Status</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @foreach($orders as $order)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <img src="{{ $order->user->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                  <div>
                    <p class="font-semibold text-navy-deep text-xs">{{ $order->user->nama_lengkap }}</p>
                    <p class="text-gray-400 text-xs truncate max-w-[140px]">{{ $order->user->email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-4">
                <p class="text-navy-deep text-xs font-medium truncate max-w-[160px]">{{ $order->event->judul }}</p>
                <p class="text-gray-400 text-xs">{{ $order->event->pengelola->nama_panggilan }}</p>
              </td>
              <td class="px-4 py-4">
                <span class="font-mono text-xs text-navy-mid bg-navy-mid/10 px-2 py-1 rounded-lg">{{ $order->order_code }}</span>
              </td>
              <td class="px-4 py-4">
                <p class="font-bold text-navy-deep text-xs">Rp {{ number_format($order->total_harga,0,',','.') }}</p>
                <p class="text-gray-400 text-xs">{{ $order->total_qty }} tiket · {{ $order->metode_bayar }}</p>
              </td>
              <td class="px-4 py-4">
                <p class="text-xs text-gray-600">{{ $order->created_at->format('d M Y') }}</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</p>
              </td>
              <td class="px-4 py-4">
                <span class="text-xs font-semibold px-2 py-1 rounded-full
                  {{ $order->status==='paid'?'badge-paid':($order->status==='pending'?'badge-pending':'badge-cancelled') }}">
                  {{ $order->status==='paid'?'✅ Lunas':($order->status==='pending'?'⏳ Pending':'❌ Batal') }}
                </span>
              </td>
              <td class="px-4 py-4">
                <form method="POST" action="{{ route('admin.pesanan.status', $order) }}">
                  @csrf @method('PUT')
                  <select name="status" onchange="this.form.submit()"
                          class="text-xs border border-gray-200 rounded-lg px-2 py-1 outline-none focus:border-gold bg-white cursor-pointer">
                    <option value="pending"   {{ $order->status==='pending'?'selected':'' }}>Pending</option>
                    <option value="paid"      {{ $order->status==='paid'?'selected':'' }}>Lunas</option>
                    <option value="cancelled" {{ $order->status==='cancelled'?'selected':'' }}>Batal</option>
                  </select>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
    @endif
  </div>
</div>
@endsection
