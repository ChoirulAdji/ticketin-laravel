@extends('layouts.admin')
@section('title', 'Manajemen Event')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-extrabold text-navy-deep">Manajemen Event</h1>
    @if(($counts['pending_review'] ?? 0) > 0)
    <span class="bg-amber-500 text-white text-sm font-bold px-3 py-1.5 rounded-full animate-pulse">
      {{ $counts['pending_review'] }} menunggu review
    </span>
    @endif
  </div>

  {{-- Tab filter status --}}
  <div class="flex flex-wrap gap-2 mb-6">
    @foreach([
      'pending_review' => ['label' => 'Menunggu Review', 'color' => 'amber'],
      'published'      => ['label' => 'Published',       'color' => 'green'],
      'draft'          => ['label' => 'Draft / Ditolak', 'color' => 'gray'],
      'cancelled'      => ['label' => 'Cancelled',       'color' => 'red'],
    ] as $s => $cfg)
    <a href="{{ request()->fullUrlWithQuery(['status' => $s, 'page' => 1]) }}"
       class="px-4 py-2 rounded-xl text-sm font-semibold border transition
              {{ $status === $s
                ? 'bg-navy-mid text-white border-navy-mid'
                : 'bg-white text-gray-600 border-gray-200 hover:border-navy-mid' }}">
      {{ $cfg['label'] }}
      <span class="ml-1 text-xs opacity-75">({{ $counts[$s] ?? 0 }})</span>
    </a>
    @endforeach
  </div>

  {{-- Search --}}
  <form method="GET" class="mb-4 flex gap-2">
    <input type="hidden" name="status" value="{{ $status }}">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari judul event..."
           class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-navy-mid">
    <button type="submit" class="bg-navy-mid text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-navy-deep transition">Cari</button>
  </form>

  {{-- Event list --}}
  @if($events->isEmpty())
    <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200">
      <p class="text-gray-500">Tidak ada event dengan status ini.</p>
    </div>
  @else
  <div class="space-y-4">
    @foreach($events as $event)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="flex flex-col sm:flex-row gap-4 p-5">

        {{-- Cover --}}
        <div class="w-full sm:w-40 h-28 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
          <img src="{{ $event->cover_url }}" class="w-full h-full object-cover"
               onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400'">
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2 flex-wrap">
            <div>
              <h3 class="font-bold text-navy-deep text-sm">{{ $event->judul }}</h3>
              <p class="text-xs text-gray-500 mt-0.5">
                oleh <span class="font-semibold">{{ $event->pengelola->nama_lengkap ?? '-' }}</span>
                · {{ $event->tanggal_waktu->format('d M Y H:i') }}
                · {{ $event->lokasi_kota }}
              </p>
              <p class="text-xs text-gray-400 mt-0.5">{{ $event->orders_count }} pesanan</p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold flex-shrink-0
              {{ $event->status === 'published'      ? 'bg-green-100 text-green-700' :
                 ($event->status === 'pending_review' ? 'bg-amber-100 text-amber-700' :
                 ($event->status === 'draft'          ? 'bg-gray-100 text-gray-600' :
                                                        'bg-red-100 text-red-600')) }}">
              {{ $event->status === 'pending_review' ? 'Menunggu Review' :
                 ($event->status === 'published'     ? 'Published' :
                 ($event->status === 'draft'         ? 'Draft' : 'Cancelled')) }}
            </span>
          </div>

          {{-- Catatan admin jika ada --}}
          @if($event->catatan_admin)
          <div class="mt-2 bg-red-50 border border-red-100 rounded-lg px-3 py-2 text-xs text-red-700">
            <span class="font-semibold">Catatan admin:</span> {{ $event->catatan_admin }}
          </div>
          @endif
        </div>

        {{-- Actions --}}
        <div class="flex flex-col gap-2 flex-shrink-0 justify-center">
          @if($event->status === 'pending_review')

          {{-- Approve --}}
          <form method="POST" action="{{ route('admin.events.approve', $event) }}">
            @csrf
            <button type="submit"
              class="w-full bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition"
              onclick="return confirm('Approve event ini? Akan langsung tampil ke publik.')">
              Approve
            </button>
          </form>

          {{-- Reject with reason --}}
          <button type="button"
            onclick="showRejectModal({{ $event->id }}, '{{ addslashes($event->judul) }}')"
            class="w-full bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
            Tolak
          </button>

          @elseif($event->status === 'published')
          <form method="POST" action="{{ route('admin.events.reject', $event) }}">
            @csrf
            <input type="hidden" name="alasan" value="Diturunkan oleh admin.">
            <button type="submit"
              class="w-full bg-orange-400 hover:bg-orange-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition"
              onclick="return confirm('Turunkan event ini dari publik?')">
              Turunkan
            </button>
          </form>
          @endif

          <a href="{{ route('events.show', $event) }}" target="_blank"
             class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-xl transition">
            Preview
          </a>

          <form method="POST" action="{{ route('admin.events.hapus', $event) }}"
                onsubmit="return confirm('Hapus event ini permanen?')">
            @csrf @method('DELETE')
            <button type="submit"
              class="w-full bg-white border border-red-200 hover:bg-red-50 text-red-500 text-xs font-semibold px-4 py-2 rounded-xl transition">
              Hapus
            </button>
          </form>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- Pagination --}}
  <div class="mt-6">{{ $events->withQueryString()->links() }}</div>
  @endif
</div>

{{-- Reject Modal --}}
<div id="reject-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
    <h3 class="font-bold text-navy-deep text-lg mb-1">Tolak Event</h3>
    <p id="reject-event-name" class="text-sm text-gray-500 mb-4"></p>
    <form id="reject-form" method="POST">
      @csrf
      <textarea name="alasan" placeholder="Alasan penolakan (opsional, akan dikirim ke EO)..."
        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-navy-mid resize-none"
        rows="3" maxlength="500"></textarea>
      <div class="flex gap-3 mt-4">
        <button type="button" onclick="closeRejectModal()"
          class="flex-1 border border-gray-200 text-gray-600 font-semibold py-2.5 rounded-xl hover:bg-gray-50 transition text-sm">
          Batal
        </button>
        <button type="submit"
          class="flex-1 bg-red-500 text-white font-bold py-2.5 rounded-xl hover:bg-red-600 transition text-sm">
          Tolak Event
        </button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
const rejectRoutes = {
  @foreach($events as $event)
  {{ $event->id }}: "{{ route('admin.events.reject', $event) }}",
  @endforeach
};
function showRejectModal(id, title) {
  document.getElementById('reject-event-name').textContent = title;
  document.getElementById('reject-form').action = rejectRoutes[id];
  document.getElementById('reject-modal').classList.remove('hidden');
}
function closeRejectModal() {
  document.getElementById('reject-modal').classList.add('hidden');
}
</script>
@endpush
@endsection
