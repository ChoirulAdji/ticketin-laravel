<div class="event-card bg-white rounded-xl shadow-md overflow-hidden cursor-pointer group flex flex-col relative"
     style="height:380px;"
     onclick="location.href='{{ route('events.show', $event) }}'">

  {{-- Wish button --}}
  @auth
  <button
    class="wish-toggle absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-md hover:scale-110 transition-all duration-200"
    data-event-id="{{ $event->id }}"
    data-url="{{ route('wishlist.toggle', $event) }}"
    onclick="event.stopPropagation(); toggleWish(this)"
    title="Tambah ke Favorit"
    aria-label="Wishlist">
    <svg class="w-4 h-4 wish-icon transition-all duration-200 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    </svg>
  </button>
  @endauth

  <div class="overflow-hidden flex-shrink-0" style="height:180px;">
    <img src="{{ $event->cover_url }}"
         onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'"
         alt="{{ $event->judul }}"
         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"/>
  </div>

  <div class="p-4 flex flex-col flex-1 min-h-0">
    <div class="mb-2 flex-shrink-0">
      <span class="bg-navy-mid/10 text-navy-mid text-xs px-2 py-0.5 rounded-full font-medium">{{ $event->kategori }}</span>
    </div>

    <h3 class="font-bold text-navy-deep text-sm leading-snug mb-2 flex-shrink-0"
        style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:40px;">
      {{ $event->judul }}
    </h3>

    <p class="text-xs text-gray-400 flex items-center gap-1 flex-shrink-0 mb-0.5">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
      </svg>
      <span>{{ $event->tanggal_waktu->format('d M Y') }}, {{ $event->tanggal_waktu->format('H:i') }}</span>
    </p>

    <p class="text-xs text-gray-400 flex items-center gap-1 flex-shrink-0 mb-1">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      <span class="truncate">{{ $event->lokasi_kota }}</span>
    </p>

    <div class="flex-1"></div>

    <div class="flex items-center justify-between pt-3 border-t border-gray-100 flex-shrink-0">
      <div>
        @if(($event->harga_termurah ?? 0) == 0)
          <p class="text-green-600 font-bold text-sm">GRATIS</p>
        @else
          <p class="text-gray-400 text-xs">Mulai dari</p>
          <p class="text-navy-mid font-bold text-sm">Rp {{ number_format($event->harga_termurah, 0, ',', '.') }}</p>
        @endif
      </div>
      <button class="bg-gold text-navy-deep text-xs font-bold px-4 py-2 rounded-lg hover:bg-gold-light transition-all duration-200">
        Beli
      </button>
    </div>
  </div>
</div>
