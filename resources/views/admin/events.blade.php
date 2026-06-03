@extends('layouts.admin')
@section('title','Manajemen Event')

@push('styles')
<style>
  .badge-published { background:rgba(34,197,94,.15); color:#16a34a; border:1px solid rgba(34,197,94,.3); }
  .badge-draft     { background:rgba(234,179,8,.15); color:#b45309; border:1px solid rgba(234,179,8,.3); }
  .badge-cancelled { background:rgba(239,68,68,.15); color:#dc2626; border:1px solid rgba(239,68,68,.3); }
</style>
@endpush

@section('content')
<div class="p-6 max-w-7xl mx-auto">

  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-extrabold text-navy-deep">Manajemen Event</h1>
      <p class="text-gray-400 text-sm mt-0.5">Total {{ $events->total() }} event</p>
    </div>
    <form method="GET" class="flex gap-2">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul event..."
             class="border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-navy-mid w-52">
      <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none bg-white">
        <option value="">Semua Status</option>
        <option value="published" {{ request('status')==='published'?'selected':'' }}>🟢 Published</option>
        <option value="draft"     {{ request('status')==='draft'?'selected':'' }}>📝 Draft</option>
        <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>❌ Cancelled</option>
      </select>
      <button type="submit" class="bg-navy-mid text-white font-bold px-4 py-2 rounded-xl text-sm hover:bg-navy-deep">Cari</button>
    </form>
  </div>

  <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
    @if($events->isEmpty())
      <div class="py-20 text-center text-gray-400">
        <div class="text-4xl mb-3">📭</div><p>Tidak ada event ditemukan</p>
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-6 py-3">Event</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Pengelola</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Tanggal</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Pesanan</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Status</th>
              <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            @foreach($events as $event)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <img src="{{ $event->cover_url }}" class="w-12 h-10 rounded-xl object-cover flex-shrink-0">
                  <div class="min-w-0">
                    <p class="font-semibold text-navy-deep text-sm truncate max-w-[180px]">{{ $event->judul }}</p>
                    <p class="text-gray-400 text-xs">{{ $event->kategori }} · {{ $event->lokasi_kota }}</p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-4">
                <div class="flex items-center gap-2">
                  <img src="{{ $event->pengelola->avatar_url }}" class="w-7 h-7 rounded-full object-cover">
                  <span class="text-xs text-gray-600 truncate max-w-[120px]">{{ $event->pengelola->nama_panggilan }}</span>
                </div>
              </td>
              <td class="px-4 py-4">
                <p class="text-xs text-gray-600">{{ $event->tanggal_waktu->format('d M Y') }}</p>
                <p class="text-xs text-gray-400">{{ $event->tanggal_waktu->format('H:i') }} WIB</p>
              </td>
              <td class="px-4 py-4">
                <p class="text-xs font-bold text-navy-deep">{{ $event->orders_count }}</p>
                <p class="text-xs text-gray-400">pesanan</p>
              </td>
              <td class="px-4 py-4">
                <span class="text-xs font-semibold px-2 py-1 rounded-full
                  {{ $event->status==='published'?'badge-published':($event->status==='draft'?'badge-draft':'badge-cancelled') }}">
                  {{ $event->status==='published'?'🟢 Live':($event->status==='draft'?'📝 Draft':'❌ Batal') }}
                </span>
              </td>
              <td class="px-4 py-4">
                <div class="flex items-center gap-2">
                  @if($event->status !== 'published')
                  <form method="POST" action="{{ route('admin.events.approve', $event) }}">
                    @csrf
                    <button type="submit" class="text-xs bg-green-50 text-green-700 border border-green-200 px-3 py-1.5 rounded-lg hover:bg-green-100 transition-all">
                      ✅ Publish
                    </button>
                  </form>
                  @endif
                  @if($event->status !== 'cancelled')
                  <form method="POST" action="{{ route('admin.events.reject', $event) }}">
                    @csrf
                    <button type="submit" class="text-xs bg-yellow-50 text-yellow-700 border border-yellow-200 px-3 py-1.5 rounded-lg hover:bg-yellow-100 transition-all">
                      🚫 Tolak
                    </button>
                  </form>
                  @endif
                  <form method="POST" action="{{ route('admin.events.hapus', $event) }}"
                        onsubmit="return confirm('Hapus event {{ addslashes($event->judul) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs bg-red-50 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-100 transition-all">
                      🗑️ Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4 border-t border-gray-100">{{ $events->links() }}</div>
    @endif
  </div>
</div>
@endsection
