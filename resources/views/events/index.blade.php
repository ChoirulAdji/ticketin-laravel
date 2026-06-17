@extends('layouts.app')
@section('title', 'TicketIn — Semua Event')

@push('styles')
<style>
  .filter-chip {
    transition: all .2s;
  }

  .filter-chip.active {
    background: #102A71;
    color: #fff;
    border-color: #102A71;
  }

  .sort-btn {
    transition: all .15s;
  }

  .sort-btn.active {
    background: #F5C400;
    color: #001840;
    font-weight: 700;
  }

  .page-link-active {
    background: #102A71 !important;
    color: #fff !important;
    border-color: #102A71 !important;
  }
</style>
@endpush

@section('content')
<main class="pt-24 max-w-7xl mx-auto px-4 sm:px-6 py-10">

  {{-- Page Header --}}
  <div class="mb-8">
    <h1 class="text-3xl font-extrabold text-navy-deep">Semua Event</h1>
    <p class="text-gray-500 text-sm mt-1">
      Menampilkan <span class="font-semibold text-navy-mid">{{ $events->total() }}</span>
      dari <span class="font-semibold">{{ $totalEvents }}</span> event tersedia
      @if(request()->hasAny(['search','kategori','kota']))
      &mdash; <a href="{{ route('events.index') }}" class="text-red-400 hover:underline text-xs font-semibold">Reset semua filter</a>
      @endif
    </p>
  </div>

  <div class="flex flex-col lg:flex-row gap-8 items-start">

    {{-- ── SIDEBAR FILTER ─────────────────────────────────────────── --}}
    <aside class="w-full lg:w-64 flex-shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:sticky lg:top-28">

      <form method="GET" action="{{ route('events.index') }}" id="filter-form">
        {{-- Pertahankan sort --}}
        <input type="hidden" name="sort" value="{{ request('sort', 'terdekat') }}">

        {{-- Search --}}
        <div class="mb-6 relative">
          <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama event..."
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 outline-none text-sm transition-all"
            oninput="clearTimeout(window._st); window._st=setTimeout(()=>this.form.submit(),600)">
          <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>

        {{-- Kategori --}}
        <div class="mb-6">
          <h4 class="font-bold text-navy-deep mb-3 text-sm">Kategori</h4>
          <div class="space-y-2">
            @foreach($kategoris as $kat)
            <label class="flex items-center gap-3 text-sm text-gray-600 hover:text-navy-deep cursor-pointer group">
              <input type="checkbox" name="kategori[]" value="{{ $kat }}"
                class="accent-gold rounded"
                {{ in_array($kat, (array) request('kategori', [])) ? 'checked' : '' }}
                onchange="this.form.submit()">
              <span class="group-hover:font-medium transition-all">{{ $kat }}</span>
              @php $cnt = \App\Models\Event::published()->where('kategori',$kat)->count() @endphp
              <span class="ml-auto text-xs text-gray-300 font-medium">{{ $cnt }}</span>
            </label>
            @endforeach
          </div>
        </div>

        <hr class="border-gray-100 my-5">

        {{-- Kota --}}
        <div class="mb-6">
          <h4 class="font-bold text-navy-deep mb-3 text-sm">Lokasi Kota</h4>
          <div class="space-y-2">
            @foreach($kotas as $kota)
            <label class="flex items-center gap-3 text-sm text-gray-600 hover:text-navy-deep cursor-pointer group">
              <input type="checkbox" name="kota[]" value="{{ $kota }}"
                class="accent-gold rounded"
                {{ in_array($kota, (array) request('kota', [])) ? 'checked' : '' }}
                onchange="this.form.submit()">
              <span class="group-hover:font-medium transition-all">{{ $kota }}</span>
              @php $cnt = \App\Models\Event::published()->where('lokasi_kota',$kota)->count() @endphp
              <span class="ml-auto text-xs text-gray-300 font-medium">{{ $cnt }}</span>
            </label>
            @endforeach
          </div>
        </div>

        @if(request()->hasAny(['search','kategori','kota']))
        <a href="{{ route('events.index', ['sort' => request('sort','terdekat')]) }}"
          class="block w-full py-2.5 text-sm font-semibold text-red-500 bg-red-50 hover:bg-red-100 rounded-xl transition-colors text-center">
          Reset Filter
        </a>
        @endif
      </form>
    </aside>

    {{-- ── MAIN CONTENT ───────────────────────────────────────────── --}}
    <div class="flex-1 w-full min-w-0">

      {{-- Sort bar --}}
      <div class="flex flex-wrap items-center gap-2 mb-6">
        <span class="text-xs font-semibold text-gray-500 mr-1">Urutkan:</span>
        @foreach(['terdekat'=>' Terdekat','terbaru'=>' Terbaru','termurah'=>' Termurah','termahal'=>' Termahal'] as $val => $label)
        <a href="{{ request()->fullUrlWithQuery(['sort'=>$val, 'page'=>1]) }}"
          class="sort-btn px-3 py-1.5 text-xs rounded-full border border-gray-200 bg-white text-gray-600 hover:border-navy-mid {{ request('sort','terdekat') === $val ? 'active' : '' }}">
          {{ $label }}
        </a>
        @endforeach

        {{-- Active filter chips --}}
        @foreach((array) request('kategori', []) as $kat)
        <a href="{{ request()->fullUrlWithQuery(['kategori' => array_values(array_diff((array)request('kategori',[]), [$kat])), 'page'=>1]) }}"
          class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-navy-mid/10 text-navy-mid font-semibold rounded-full hover:bg-red-50 hover:text-red-500 transition">
          {{ $kat }} ×
        </a>
        @endforeach
        @foreach((array) request('kota', []) as $kota)
        <a href="{{ request()->fullUrlWithQuery(['kota' => array_values(array_diff((array)request('kota',[]), [$kota])), 'page'=>1]) }}"
          class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-navy-mid/10 text-navy-mid font-semibold rounded-full hover:bg-red-50 hover:text-red-500 transition">
          {{ $kota }} ×
        </a>
        @endforeach
      </div>

      {{-- Event Grid --}}
      @if($events->isNotEmpty())
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($events as $row)
        @include('partials.event-card', ['event' => $row])
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($events->hasPages())
      <div class="mt-10 flex flex-col items-center gap-3">
        {{-- Info --}}
        <p class="text-xs text-gray-400">
          Halaman {{ $events->currentPage() }} dari {{ $events->lastPage() }}
          &nbsp;·&nbsp; {{ $events->firstItem() }}–{{ $events->lastItem() }} dari {{ $events->total() }} event
        </p>

        {{-- Pagination links --}}
        <nav class="flex items-center gap-1 flex-wrap justify-center" aria-label="Pagination">

          {{-- Prev --}}
          @if($events->onFirstPage())
          <span class="px-3 py-2 rounded-xl border border-gray-100 text-gray-300 text-sm cursor-not-allowed bg-gray-50">‹</span>
          @else
          <a href="{{ $events->previousPageUrl() }}" class="px-3 py-2 rounded-xl border border-gray-200 text-navy-mid text-sm hover:bg-navy-mid hover:text-white hover:border-navy-mid transition font-semibold">‹</a>
          @endif

          {{-- Page numbers --}}
          @php
          $current = $events->currentPage();
          $last = $events->lastPage();
          $range = 2; // pages around current
          $pages = collect();
          for ($p = max(1,$current-$range); $p <= min($last,$current+$range); $p++) $pages->push($p);
            @endphp

            @if($pages->first() > 1)
            <a href="{{ $events->url(1) }}" class="px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 transition">1</a>
            @if($pages->first() > 2)
            <span class="px-2 text-gray-300 text-sm">…</span>
            @endif
            @endif

            @foreach($pages as $page)
            @if($page === $current)
            <span class="px-3 py-2 rounded-xl border text-sm font-bold bg-navy-mid text-white border-navy-mid">{{ $page }}</span>
            @else
            <a href="{{ $events->url($page) }}" class="px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 transition">{{ $page }}</a>
            @endif
            @endforeach

            @if($pages->last() < $last)
              @if($pages->last() < $last - 1)
                <span class="px-2 text-gray-300 text-sm">…</span>
                @endif
                <a href="{{ $events->url($last) }}" class="px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 transition">{{ $last }}</a>
                @endif

                {{-- Next --}}
                @if($events->hasMorePages())
                <a href="{{ $events->nextPageUrl() }}" class="px-3 py-2 rounded-xl border border-gray-200 text-navy-mid text-sm hover:bg-navy-mid hover:text-white hover:border-navy-mid transition font-semibold">›</a>
                @else
                <span class="px-3 py-2 rounded-xl border border-gray-100 text-gray-300 text-sm cursor-not-allowed bg-gray-50">›</span>
                @endif
        </nav>
      </div>
      @endif

      @else
      <div class="py-24 text-center bg-white rounded-2xl border border-dashed border-gray-200">

        <h3 class="text-lg font-bold text-navy-deep mb-1">Event tidak ditemukan</h3>
        <p class="text-sm text-gray-500 mb-5">Coba ubah kata kunci atau hapus beberapa filter.</p>
        <a href="{{ route('events.index') }}"
          class="inline-block bg-gold text-navy-deep font-bold text-sm px-6 py-3 rounded-xl hover:bg-gold-light transition">
          Lihat Semua Event
        </a>
      </div>
      @endif
    </div>
  </div>
</main>
@endsection