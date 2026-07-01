@php
  $slides = $slides ?? collect();
  if ($slides instanceof \Illuminate\Support\Collection) {
      $slidesArr = $slides->all();   
  } elseif (is_array($slides)) {
      $slidesArr = $slides;
  } else {
      $slidesArr = [$slides];
  }
  $slideCount = count($slidesArr);

  $normalized = [];
  foreach ($slidesArr as $slide) {
            $obj = is_array($slide) ? (object) $slide : $slide;

      $imageUrl = null;
      if (method_exists($obj, 'getImageUrlAttribute')) {
          $imageUrl = $obj->getImageUrlAttribute();
      } elseif (isset($obj->image_url)) {
          $imageUrl = $obj->image_url;
      }
      $imageUrl = $imageUrl ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1400&q=80';

      // Ambil title
      $titleVal = null;
      if (method_exists($obj, 'getTitleAttribute')) {
          $titleVal = $obj->getTitleAttribute();
      } elseif (isset($obj->title)) {
          $titleVal = $obj->title;
      } elseif (isset($obj->judul)) {
          $titleVal = $obj->judul;
      }
      $titleVal = $titleVal ?? '';

      // Ambil link
      $linkVal = null;
      if (method_exists($obj, 'getLinkAttribute')) {
          $linkVal = $obj->getLinkAttribute();
      } elseif (isset($obj->link)) {
          $linkVal = $obj->link;
      } elseif (isset($obj->url_tujuan)) {
          $linkVal = $obj->url_tujuan;
      }
      $linkVal = $linkVal ?? '#';

      // Ambil event relasi (jika ada)
      $eventObj = isset($obj->event) ? $obj->event : null;

      // Ambil badge dari kategori event jika ada, fallback 'Event'
      $badge = isset($obj->badge)
          ? $obj->badge
          : ($eventObj ? ($eventObj->kategori ?? 'Event') : 'Event');

      // Subtitle: tanggal + lokasi dari event jika ada
      if (isset($obj->subtitle) && $obj->subtitle) {
          $subtitle = $obj->subtitle;
      } elseif ($eventObj && isset($eventObj->tanggal_waktu)) {
          $tgl = \Carbon\Carbon::parse($eventObj->tanggal_waktu)->translatedFormat('j M Y');
          $kota = $eventObj->lokasi_kota ?? '';
          $subtitle = $tgl . ($kota ? ' · ' . $kota : '');
      } else {
          $subtitle = '';
      }

      $normalized[] = [
          'image_url' => $imageUrl,
          'title'     => $titleVal,
          'link'      => $linkVal,
          'badge'     => $badge,
          'subtitle'  => $subtitle,
      ];
  }
@endphp

<style>
  /* ===== HERO SLIDER ===== */
  .hs-wrap { position: relative; width: 100%; }

  /* ---- Desktop: fade slider ---- */
  .hs-desktop {
    display: block;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    height: 500px;
  }
  .hs-desktop-slide {
    position: absolute; inset: 0;
    transition: opacity 0.7s ease;
  }
  .hs-desktop-slide img {
    width: 100%; height: 100%; object-fit: cover;
  }
  .hs-desktop-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(180deg, rgba(0,24,64,0.0) 30%, rgba(0,24,64,0.75) 100%);
    display: flex; flex-direction: column; justify-content: flex-end;
    padding: 36px 48px;
  }
  .hs-desktop-title {
    color: white; font-size: 34px; font-weight: 700;
    max-width: 560px; line-height: 1.25; margin: 0;
    text-shadow: 0 2px 12px rgba(0,0,0,0.4);
  }

  /* Desktop arrow & dots */
  .hs-arrow {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 40px; height: 40px; border-radius: 50%;
    background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    color: white; cursor: pointer; z-index: 10;
    border: 1px solid rgba(255,255,255,0.2);
    transition: background 0.2s;
  }
  .hs-arrow:hover { background: rgba(255,255,255,0.3); }
  .hs-arrow.prev { left: 20px; }
  .hs-arrow.next { right: 20px; }

  .hs-desktop-dots {
    position: absolute; bottom: 18px; left: 50%; transform: translateX(-50%);
    display: flex; gap: 8px; z-index: 10;
  }
  .hs-desktop-dots .hs-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(255,255,255,0.4); cursor: pointer;
    transition: all 0.3s;
  }
  .hs-desktop-dots .hs-dot.active {
    background: #F5C400; transform: scale(1.3);
  }

  /* ---- Mobile: horizontal swipe card slider ---- */
  .hs-mobile { display: none; }

  @media (max-width: 767px) {
    .hs-desktop { display: none; }
    .hs-arrow, .hs-desktop-dots { display: none; }

    .hs-mobile {
      display: block;
      position: relative;
      width: 100%;
    }

    .hs-mobile-track {
      display: flex;
      overflow-x: auto;
      scroll-snap-type: x mandatory;
      gap: 12px;
      scrollbar-width: none;
      -webkit-overflow-scrolling: touch;
      padding-bottom: 4px;
      /* padding kiri biar slide pertama sedikit offset = lebih "app-like" */
      padding-left: 16px;
      padding-right: 16px;
    }
    .hs-mobile-track::-webkit-scrollbar { display: none; }

    .hs-mobile-slide {
      scroll-snap-align: start;
      flex: 0 0 88%;
      position: relative;
      border-radius: 18px;
      overflow: hidden;
      height: 200px;
      text-decoration: none;
      display: block;
      box-shadow: 0 4px 20px rgba(0,24,64,0.15);
    }
    .hs-mobile-slide img {
      width: 100%; height: 100%; object-fit: cover; display: block;
    }
    .hs-mobile-overlay {
      position: absolute; inset: 0;
      background: linear-gradient(180deg,
        rgba(0,24,64,0.0) 40%,
        rgba(0,24,64,0.80) 100%);
      display: flex; flex-direction: column; justify-content: flex-end;
      padding: 16px;
    }
    .hs-mobile-title {
      color: white; font-size: 16px; font-weight: 700;
      line-height: 1.3; margin: 0;
      text-shadow: 0 1px 6px rgba(0,0,0,0.4);
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    /* Dots mobile */
    .hs-mobile-dots {
      display: flex; justify-content: center; gap: 6px;
      margin: 12px 0 4px;
    }
    .hs-mobile-dots .hs-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: #cbd5e1;
      transition: all 0.3s;
    }
    .hs-mobile-dots .hs-dot.active {
      width: 18px; border-radius: 4px; background: #102A71;
    }
  }
</style>

<div class="hs-wrap">

  {{-- ===== DESKTOP: fade autoplay slider ===== --}}
  <div class="hs-desktop" id="hs-desktop">
    @foreach($normalized as $i => $slide)
      <a href="{{ $slide['link'] }}"
         class="hs-desktop-slide"
         style="opacity:{{ $i === 0 ? 1 : 0 }}; pointer-events:{{ $i === 0 ? 'auto' : 'none' }};"
         data-hs-index="{{ $i }}">
        <img src="{{ $slide['image_url'] }}"
             onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1400&q=80'"
             alt="{{ $slide['title'] }}">
        <div class="hs-desktop-overlay">
          <h2 class="hs-desktop-title">{{ $slide['title'] }}</h2>
        </div>
      </a>
    @endforeach

    <div class="hs-arrow prev" onclick="hsDesktopGo(-1)">
      <svg style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
    </div>
    <div class="hs-arrow next" onclick="hsDesktopGo(1)">
      <svg style="width:18px;height:18px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </div>

    <div class="hs-desktop-dots" id="hs-desktop-dots">
      @foreach($normalized as $i => $slide)
        <div class="hs-dot {{ $i === 0 ? 'active' : '' }}" onclick="hsDesktopGoTo({{ $i }})"></div>
      @endforeach
    </div>
  </div>

  {{-- ===== MOBILE: swipeable horizontal card slider ===== --}}
  <div class="hs-mobile">
    <div class="hs-mobile-track" id="hs-mobile-track">
      @foreach($normalized as $i => $slide)
        <a href="{{ $slide['link'] }}" class="hs-mobile-slide" data-hs-index="{{ $i }}">
          <img src="{{ $slide['image_url'] }}"
               onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'"
               alt="{{ $slide['title'] }}"
               loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
          <div class="hs-mobile-overlay">
            <div class="hs-mobile-title">{{ $slide['title'] }}</div>
          </div>
        </a>
      @endforeach
    </div>

    <div class="hs-mobile-dots" id="hs-mobile-dots">
      @foreach($normalized as $i => $slide)
        <div class="hs-dot {{ $i === 0 ? 'active' : '' }}" data-hs-index="{{ $i }}"></div>
      @endforeach
    </div>
  </div>
</div>

<script>
(function () {
  const SLIDE_COUNT = {{ $slideCount }};
  if (SLIDE_COUNT === 0) return;

  /* ---- DESKTOP: fade autoplay ---- */
  let dIdx = 0;
  let dTimer = null;

  function hsDesktopRender() {
    document.querySelectorAll('#hs-desktop .hs-desktop-slide').forEach(el => {
      const active = parseInt(el.dataset.hsIndex) === dIdx;
      el.style.opacity = active ? '1' : '0';
      el.style.pointerEvents = active ? 'auto' : 'none';
    });
    document.querySelectorAll('#hs-desktop-dots .hs-dot').forEach((dot, i) => {
      dot.classList.toggle('active', i === dIdx);
    });
  }

  window.hsDesktopGoTo = function (i) {
    dIdx = i;
    hsDesktopRender();
    hsDesktopResetTimer();
  };

  window.hsDesktopGo = function (delta) {
    dIdx = (dIdx + delta + SLIDE_COUNT) % SLIDE_COUNT;
    hsDesktopRender();
    hsDesktopResetTimer();
  };

  function hsDesktopResetTimer() {
    clearInterval(dTimer);
    dTimer = setInterval(() => window.hsDesktopGo(1), 5000);
  }

  if (document.getElementById('hs-desktop')) {
    hsDesktopResetTimer();
  }

  /* ---- MOBILE: native scroll-snap swipe ---- */
  const mTrack = document.getElementById('hs-mobile-track');
  if (!mTrack) return;

  const mDots = document.querySelectorAll('#hs-mobile-dots .hs-dot');
  let mTimer = null;

  function hsUpdateMobileDots() {
    const firstSlide = mTrack.children[0];
    if (!firstSlide) return;
    const slideW = firstSlide.offsetWidth + 12; // 12 = gap
    const idx = Math.round(mTrack.scrollLeft / slideW);
    mDots.forEach((dot, i) => dot.classList.toggle('active', i === idx));
  }

  mTrack.addEventListener('scroll', () => {
    window.requestAnimationFrame(hsUpdateMobileDots);
  }, { passive: true });

  function hsMobileResetTimer() {
    clearInterval(mTimer);
    mTimer = setInterval(() => {
      const firstSlide = mTrack.children[0];
      if (!firstSlide) return;
      const slideW = firstSlide.offsetWidth + 12;
      const maxScroll = mTrack.scrollWidth - mTrack.clientWidth;
      let next = mTrack.scrollLeft + slideW;
      if (next > maxScroll + 5) next = 0;
      mTrack.scrollTo({ left: next, behavior: 'smooth' });
    }, 4500);
  }

  // Pause saat user swipe
  mTrack.addEventListener('touchstart', () => clearInterval(mTimer), { passive: true });
  mTrack.addEventListener('touchend', hsMobileResetTimer, { passive: true });

  hsMobileResetTimer();
})();
</script>