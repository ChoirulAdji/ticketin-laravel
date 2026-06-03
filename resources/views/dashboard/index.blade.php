@extends('layouts.app')
@section('title', 'TicketIn — Temukan Event Terbaik')

@push('styles')
<style>
  .event-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
  .event-card:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 20px 40px rgba(0,24,64,0.15); }
  .dot.active { background-color: #F5C400; transform: scale(1.3); }
  .dot { transition: all 0.3s; }
  .slider-track { display: flex; transition: transform 0.6s cubic-bezier(0.77,0,0.175,1); }
  .slide { min-width: 100%; position: relative; }
  .badge-popular { animation: badgePulse 2s infinite; }
  @keyframes badgePulse { 0%,100%{box-shadow:0 0 0 0 rgba(245,196,0,0.5);}50%{box-shadow:0 0 0 6px rgba(245,196,0,0);} }
</style>
@endpush

@section('content')

  <!-- HERO SLIDER -->
  <section class="pt-[96px] pb-6 max-w-7xl mx-auto px-6">
    <div class="relative w-full rounded-2xl overflow-hidden shadow-md group" style="max-height:400px;aspect-ratio:21/9;">

      <div id="slider" class="slider-track h-full">
        @if($eventsSlider->isNotEmpty())
          @foreach($eventsSlider as $slide)
            <div class="slide h-full cursor-pointer" onclick="location.href='{{ route('events.show', $slide) }}'">
              <img src="{{ $slide->cover_url }}"
                   onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1400&q=80'"
                   alt="{{ $slide->judul }}" class="w-full h-full object-cover"/>
            </div>
          @endforeach
        @else
          <div class="slide h-full"><img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1400&q=80" class="w-full h-full object-cover"/></div>
          <div class="slide h-full"><img src="https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=1400&q=80" class="w-full h-full object-cover"/></div>
          <div class="slide h-full"><img src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1400&q=80" class="w-full h-full object-cover"/></div>
        @endif
      </div>

      <button id="prev-btn" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-gold text-white hover:text-navy-deep backdrop-blur-sm w-10 h-10 rounded-full flex items-center justify-center transition-all z-10 opacity-0 group-hover:opacity-100">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <button id="next-btn" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-gold text-white hover:text-navy-deep backdrop-blur-sm w-10 h-10 rounded-full flex items-center justify-center transition-all z-10 opacity-0 group-hover:opacity-100">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </button>

      <div id="dots" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
        @php $slideCount = $eventsSlider->isNotEmpty() ? $eventsSlider->count() : 3; @endphp
        @for($i = 0; $i < $slideCount; $i++)
          <button class="dot w-2.5 h-2.5 rounded-full {{ $i==0?'bg-white active':'bg-white/50' }}" data-index="{{ $i }}"></button>
        @endfor
      </div>
    </div>
  </section>

  <!-- EVENT TERDEKAT -->
  <section class="max-w-7xl mx-auto px-6 py-14">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl md:text-3xl font-extrabold text-navy-deep">Event Terdekat</h2>
        <p class="text-gray-500 text-sm mt-1">Jangan sampai ketinggalan event di kotamu</p>
      </div>
      <a href="{{ route('events.index') }}" class="text-navy-mid font-semibold hover:text-gold transition-colors text-sm flex items-center gap-1">
        Lihat semua
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @if($eventsTerdekat->isNotEmpty())
        @foreach($eventsTerdekat as $row)
        <div class="event-card bg-white rounded-xl shadow-md overflow-hidden cursor-pointer group flex flex-col"
             style="height:380px;"
             onclick="location.href='{{ route('events.show', $row) }}'">
          <div class="overflow-hidden flex-shrink-0" style="height:180px;">
            <img src="{{ $row->cover_url }}"
                 onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'"
                 alt="{{ $row->judul }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"/>
          </div>
          <div class="p-4 flex flex-col flex-1 min-h-0">
            <div class="mb-2">
              <span class="bg-navy-mid/10 text-navy-mid text-xs px-2 py-0.5 rounded-full font-medium">{{ $row->kategori }}</span>
            </div>
            <h3 class="font-bold text-navy-deep text-sm leading-snug mb-2"
                style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:40px;">
              {{ $row->judul }}
            </h3>
            <p class="text-xs text-gray-400 flex items-center gap-1 mb-0.5">
              <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              {{ $row->tanggal_waktu->format('d M Y, H:i') }}
            </p>
            <p class="text-xs text-gray-400 flex items-center gap-1">
              <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              {{ $row->lokasi_kota }}
            </p>
            <div class="flex-1"></div>
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
              <p class="text-navy-mid font-bold text-sm">
                {{ ($row->harga_termurah ?? 0) == 0 ? 'GRATIS' : 'Rp '.number_format($row->harga_termurah, 0, ',', '.') }}
              </p>
              <button class="bg-gold text-navy-deep text-xs font-bold px-4 py-2 rounded-lg hover:bg-gold-light transition-all">Beli</button>
            </div>
          </div>
        </div>
        @endforeach
      @else
        <div class="col-span-4 py-12 text-center bg-white rounded-2xl border border-dashed border-gray-200">
          <h3 class="text-lg font-bold text-navy-deep">Belum ada event</h3>
          <p class="text-sm text-gray-500 mt-1">Saat ini belum ada event yang ditambahkan.</p>
        </div>
      @endif
    </div>
  </section>

  <!-- REKOMENDASI EVENT -->
  <section class="max-w-7xl mx-auto px-6 py-14">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl md:text-3xl font-extrabold text-navy-deep">Rekomendasi Event</h2>
        <p class="text-gray-500 text-sm mt-1">Untukmu</p>
      </div>
      <a href="{{ route('events.index') }}" class="text-navy-mid font-semibold hover:text-gold transition-colors text-sm flex items-center gap-1">
        Lihat semua
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @if($eventsRekomendasi->isNotEmpty())
        @foreach($eventsRekomendasi as $row)
        <div class="event-card bg-white rounded-xl shadow-md overflow-hidden cursor-pointer group flex flex-col relative"
             style="height:380px;"
             onclick="location.href='{{ route('events.show', $row) }}'">
          <span class="badge-popular absolute top-3 right-3 z-10 bg-gold text-navy-deep text-xs font-bold px-3 py-1 rounded-full">🔥 Populer</span>
          <div class="overflow-hidden flex-shrink-0" style="height:180px;">
            <img src="{{ $row->cover_url }}"
                 onerror="this.src='https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=400&q=80'"
                 alt="{{ $row->judul }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"/>
          </div>
          <div class="p-4 flex flex-col flex-1 min-h-0">
            <div class="mb-2">
              <span class="bg-navy-mid/10 text-navy-mid text-xs px-2 py-0.5 rounded-full font-medium">{{ $row->kategori }}</span>
            </div>
            <h3 class="font-bold text-navy-deep text-sm leading-snug mb-2"
                style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:40px;">
              {{ $row->judul }}
            </h3>
            <p class="text-xs text-gray-400 flex items-center gap-1 mb-0.5">
              <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              {{ $row->tanggal_waktu->format('d M Y, H:i') }}
            </p>
            <p class="text-xs text-gray-400 flex items-center gap-1">
              <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              {{ $row->lokasi_kota }}
            </p>
            <div class="flex-1"></div>
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
              <p class="text-navy-mid font-bold text-sm">
                {{ ($row->harga_termurah ?? 0) == 0 ? 'GRATIS' : 'Rp '.number_format($row->harga_termurah, 0, ',', '.') }}
              </p>
              <button class="bg-gold text-navy-deep text-xs font-bold px-4 py-2 rounded-lg hover:bg-gold-light transition-all">Beli</button>
            </div>
          </div>
        </div>
        @endforeach
      @else
        <div class="col-span-4 py-12 text-center bg-white rounded-2xl border border-dashed border-gray-200">
          <h3 class="text-lg font-bold text-navy-deep">Belum ada rekomendasi</h3>
        </div>
      @endif
    </div>
  </section>

@endsection

@push('scripts')
<script>
  const track = document.querySelector('.slider-track');
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  const prevBtn = document.getElementById('prev-btn');
  const nextBtn = document.getElementById('next-btn');
  let current = 0;

  function goTo(n) {
    current = (n + slides.length) % slides.length;
    track.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d,i) => {
      d.classList.toggle('active', i===current);
      d.classList.toggle('bg-white', i===current);
      d.classList.toggle('bg-white/50', i!==current);
    });
  }

  nextBtn?.addEventListener('click', () => goTo(current+1));
  prevBtn?.addEventListener('click', () => goTo(current-1));
  dots.forEach((d,i) => d.addEventListener('click', () => goTo(i)));
  setInterval(() => goTo(current+1), 5000);
</script>
@endpush
