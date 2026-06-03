<?php $__env->startSection('title', 'TicketIn — ' . $event->judul); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .tab-btn { padding: 10px 4px; font-size:.875rem; font-weight:600; color:#6b7280; border-bottom:2px solid transparent; transition:all .2s; white-space:nowrap; background:none; border-top:none; border-left:none; border-right:none; cursor:pointer; }
  .tab-btn.active { color:#102A71; border-bottom-color:#F5C400; }
  .tab-panel { display:none; }
  .tab-panel.active { display:block; }
  .faq-ans { display:none; }
  .buy-bar { position:fixed; bottom:0; left:0; right:0; background:white; border-top:1px solid #e5e7eb; padding:12px 24px; z-index:40; box-shadow:0 -4px 20px rgba(0,0,0,.08); }
  .hover-scale { transition:transform .2s; }
  .hover-scale:hover { transform:scale(1.1); }
  .wish-btn.active svg { fill:#ef4444; stroke:#ef4444; }
  .hide-scrollbar::-webkit-scrollbar { display:none; }
  .star-btn { font-size:1.75rem; cursor:pointer; color:#d1d5db; transition:color .15s, transform .15s; line-height:1; }
  .star-btn:hover, .star-btn.active { color:#F5C400; transform:scale(1.2); }
  .review-card { transition:box-shadow .2s; }
  .review-card:hover { box-shadow:0 4px 16px rgba(16,42,113,.08); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pt-24 max-w-7xl mx-auto px-6 py-10 pb-28 lg:pb-10">
  <div class="flex flex-col lg:flex-row gap-10 items-start">

    <!-- LEFT COLUMN -->
    <div class="flex-1 w-full min-w-0">

      <!-- Category + Title -->
      <div class="mb-6">
        <span class="inline-block bg-navy-mid/10 text-navy-mid text-xs font-bold px-3 py-1.5 rounded-md mb-3 uppercase tracking-wider"><?php echo e($event->kategori); ?></span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-navy-deep leading-tight mb-2"><?php echo e($event->judul); ?></h1>
        <p class="text-gray-500 text-sm flex items-center gap-2">
          Diselenggarakan oleh
          <span class="font-semibold text-navy-mid"><?php echo e($event->pengelola->nama_lengkap ?? 'TicketIn EO'); ?></span>
          <?php if($event->jumlah_review > 0): ?>
          <span class="inline-flex items-center gap-1 bg-gold/10 text-yellow-700 text-xs font-bold px-2 py-0.5 rounded-full ml-1">
            ★ <?php echo e($event->rating_rata_rata); ?> <span class="font-normal text-yellow-600">(<?php echo e($event->jumlah_review); ?>)</span>
          </span>
          <?php endif; ?>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        </p>
      </div>

      <!-- Hero Image -->
      <div class="w-full h-56 sm:h-64 md:h-[350px] rounded-2xl overflow-hidden relative shadow-sm border border-gray-200 mb-8 group bg-gray-100 flex justify-center items-center">
        <img src="<?php echo e($event->cover_url); ?>"
             onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1200&q=80'"
             alt="<?php echo e($event->judul); ?>" class="w-full h-full object-cover md:object-contain group-hover:scale-105 transition-transform duration-700"/>
        <div class="absolute top-4 left-4 z-10">
          <span class="bg-gold text-navy-deep text-xs font-bold px-3 py-1.5 rounded-full shadow-md">🔥 Populer</span>
        </div>
        <div class="absolute top-4 right-4 flex gap-2 z-10">
          <?php if(auth()->guard()->check()): ?>
          <button
            class="wish-toggle w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md hover-scale"
            data-event-id="<?php echo e($event->id); ?>"
            data-url="<?php echo e(route('wishlist.toggle', $event)); ?>"
            onclick="toggleWish(this)"
            title="Tambah ke Favorit">
            <svg class="w-5 h-5 wish-icon transition-all text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
          </button>
          <?php endif; ?>
          <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md hover-scale" onclick="shareEvent()">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-navy-mid" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
          </button>
        </div>
      </div>


      
      <?php if($event->galleries->isNotEmpty()): ?>
      <div class="mt-3 mb-4 px-4 md:px-0">
        <div class="flex gap-2 overflow-x-auto pb-2 hide-scrollbar">
          <button type="button" onclick="openLightbox(0)"
            class="flex-shrink-0 w-24 h-16 rounded-xl overflow-hidden ring-2 ring-gold shadow-md">
            <img src="<?php echo e($event->cover_url); ?>" class="w-full h-full object-cover">
          </button>
          <?php $__currentLoopData = $event->galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $gal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <button type="button" onclick="openLightbox(<?php echo e($i + 1); ?>)"
            class="flex-shrink-0 w-24 h-16 rounded-xl overflow-hidden ring-1 ring-gray-200 hover:ring-gold transition shadow">
            <img src="<?php echo e($gal->url); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
          </button>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>

      
      <div id="lightbox" class="fixed inset-0 z-[80] bg-black/95 hidden items-center justify-center" onclick="closeLightbox()">
        <button type="button" onclick="event.stopPropagation();moveLightbox(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center text-2xl transition">&#8249;</button>
        <img id="lightbox-img" src="" alt="Gallery" class="max-w-[90vw] max-h-[85vh] rounded-xl shadow-2xl object-contain" onclick="event.stopPropagation()">
        <button type="button" onclick="event.stopPropagation();moveLightbox(1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center text-2xl transition">&#8250;</button>
        <button type="button" onclick="closeLightbox()" class="absolute top-4 right-4 w-8 h-8 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center transition text-sm">&#10005;</button>
        <div id="lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/60 text-sm"></div>
      </div>
      <?php endif; ?>

      <!-- TABS -->
      <div class="flex gap-6 border-b border-gray-200 mb-6 overflow-x-auto hide-scrollbar pb-1">
        <button class="tab-btn active" data-tab="info">Deskripsi Event</button>
        <button class="tab-btn" data-tab="lineup">Line-up</button>
        <button class="tab-btn" data-tab="faq">FAQ</button>
        <button class="tab-btn" data-tab="ulasan" id="tab-btn-ulasan">Ulasan</button>
      </div>

      <!-- TAB: Info -->
      <div class="tab-panel active" id="tab-info">
        <div class="text-gray-600 text-sm md:text-base leading-relaxed mb-6">
          <?php echo nl2br(e($event->deskripsi)); ?>

        </div>
        <h3 class="text-base font-bold text-navy-deep mb-3">Highlight Event</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8">
          <div class="bg-white border border-gray-100 rounded-xl p-4 text-center shadow-sm">
            <p class="text-2xl font-extrabold text-navy-mid"><?php echo e($event->lineups->count() ?: '–'); ?></p>
            <p class="text-xs text-gray-500 mt-1">Artis / Pembicara</p>
          </div>
          <div class="bg-white border border-gray-100 rounded-xl p-4 text-center shadow-sm">
            <p class="text-2xl font-extrabold text-navy-mid"><?php echo e($event->ticketCategories->count()); ?></p>
            <p class="text-xs text-gray-500 mt-1">Kategori Tiket</p>
          </div>
          <div class="bg-white border border-gray-100 rounded-xl p-4 text-center shadow-sm col-span-2 sm:col-span-1">
            <p class="text-2xl font-extrabold text-navy-mid"><?php echo e($event->tanggal_waktu->format('H:i')); ?></p>
            <p class="text-xs text-gray-500 mt-1">Jam Mulai</p>
          </div>
        </div>
      </div>

      <!-- TAB: Lineup -->
      <div class="tab-panel" id="tab-lineup">
        <div class="space-y-4">
          <?php if($event->lineups->isNotEmpty()): ?>
            <?php $__currentLoopData = $event->lineups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex items-center gap-4 hover:border-navy-mid/30 transition-colors">
              <div class="w-16 h-16 rounded-full bg-navy-mid/10 flex items-center justify-center flex-shrink-0 text-navy-mid font-bold text-xl">
                <?php echo e(strtoupper(substr($artis->nama, 0, 1))); ?>

              </div>
              <div class="flex-1">
                <p class="font-bold text-navy-deep text-base"><?php echo e($artis->nama); ?></p>
                <p class="text-sm text-gray-500">Penampilan Spesial</p>
              </div>
              <?php if($artis->is_headliner): ?>
                <span class="bg-gold/20 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full hidden sm:block">HEADLINER</span>
              <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php else: ?>
            <p class="text-gray-500 text-sm text-center py-6 border border-dashed border-gray-200 rounded-xl">Daftar artis belum tersedia.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- TAB: FAQ -->
      <div class="tab-panel" id="tab-faq">
        <div class="space-y-3">
          <?php if($event->faqs->isNotEmpty()): ?>
            <?php $__currentLoopData = $event->faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
              <button class="w-full text-left px-5 py-4 flex justify-between items-center text-sm font-semibold text-navy-deep" onclick="toggleFaq(this)">
                <?php echo e($faq->pertanyaan); ?>

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div class="faq-ans px-5 pb-4 text-sm text-gray-500 leading-relaxed"><?php echo e($faq->jawaban); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php else: ?>
            <p class="text-gray-500 text-sm text-center py-6 border border-dashed border-gray-200 rounded-xl">FAQ belum tersedia.</p>
          <?php endif; ?>
        </div>
      </div>


      <!-- TAB: Ulasan -->
      <div class="tab-panel" id="tab-ulasan">

        <?php
          $rataRata   = $event->rating_rata_rata;
          $totalReview = $event->jumlah_review;
          $dist = [5=>0,4=>0,3=>0,2=>0,1=>0];
          foreach($event->reviews as $r) { $dist[$r->rating] = ($dist[$r->rating] ?? 0) + 1; }
        ?>

        
        <?php if($totalReview > 0): ?>
        <div class="flex flex-col sm:flex-row gap-6 bg-white border border-gray-100 rounded-2xl p-6 mb-6 shadow-sm">
          <div class="flex flex-col items-center justify-center sm:w-36 flex-shrink-0">
            <p class="text-5xl font-extrabold text-navy-deep"><?php echo e($rataRata); ?></p>
            <div class="flex gap-0.5 my-1.5">
              <?php for($i=1;$i<=5;$i++): ?>
                <span class="text-xl <?php echo e($i <= round($rataRata) ? 'text-gold' : 'text-gray-200'); ?>">★</span>
              <?php endfor; ?>
            </div>
            <p class="text-xs text-gray-500"><?php echo e($totalReview); ?> ulasan</p>
          </div>
          <div class="flex-1 flex flex-col justify-center gap-1.5">
            <?php for($star=5;$star>=1;$star--): ?>
              <?php $count = $dist[$star]; $pct = $totalReview ? round($count/$totalReview*100) : 0; ?>
              <div class="flex items-center gap-2 text-xs">
                <span class="w-2 text-gray-500 font-medium text-right"><?php echo e($star); ?></span>
                <span class="text-gold text-sm">★</span>
                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                  <div class="h-full bg-gold rounded-full transition-all duration-500" style="width:<?php echo e($pct); ?>%"></div>
                </div>
                <span class="w-8 text-gray-400 text-right"><?php echo e($count); ?></span>
              </div>
            <?php endfor; ?>
          </div>
        </div>
        <?php endif; ?>

        
        <?php if(auth()->guard()->check()): ?>
          <?php if($userReview): ?>
            <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-4 mb-6 flex items-start justify-between gap-4">
              <div>
                <p class="text-sm font-semibold text-green-700 mb-0.5">Ulasan kamu sudah terkirim ✅</p>
                <div class="flex gap-0.5 mb-1">
                  <?php for($i=1;$i<=5;$i++): ?>
                    <span class="<?php echo e($i <= $userReview->rating ? 'text-gold' : 'text-gray-300'); ?>">★</span>
                  <?php endfor; ?>
                </div>
                <?php if($userReview->ulasan): ?>
                  <p class="text-sm text-gray-600"><?php echo e($userReview->ulasan); ?></p>
                <?php endif; ?>
              </div>
              <form method="POST" action="<?php echo e(route('events.review.destroy', $event)); ?>" onsubmit="return confirm('Hapus ulasan ini?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-medium transition-colors whitespace-nowrap">Hapus</button>
              </form>
            </div>
          <?php elseif($userCanReview): ?>
            <div class="bg-white border border-gray-100 rounded-2xl p-6 mb-6 shadow-sm" id="review-form-wrap">
              <h4 class="text-sm font-bold text-navy-deep mb-4">Tulis Ulasanmu</h4>
              <form method="POST" action="<?php echo e(route('events.review.store', $event)); ?>" id="review-form">
                <?php echo csrf_field(); ?>
                
                <div class="flex gap-1 mb-4" id="star-picker">
                  <?php for($i=1;$i<=5;$i++): ?>
                    <button type="button" class="star-btn" data-val="<?php echo e($i); ?>" onclick="setRating(<?php echo e($i); ?>)">★</button>
                  <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="rating-input" value="">
                <p id="rating-hint" class="text-xs text-red-400 mb-3 hidden">Pilih bintang terlebih dahulu</p>

                
                <textarea name="ulasan" placeholder="Ceritakan pengalamanmu di event ini (opsional)…"
                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 resize-none focus:outline-none focus:ring-2 focus:ring-navy-mid/30 transition"
                  rows="3" maxlength="1000"></textarea>

                <div class="flex justify-end mt-3">
                  <button type="submit" onclick="return validateReview()"
                    class="bg-navy-mid text-white text-sm font-semibold px-6 py-2.5 rounded-xl hover:bg-navy-deep transition-all">
                    Kirim Ulasan
                  </button>
                </div>
              </form>
            </div>
          <?php else: ?>
            <div class="bg-gray-50 border border-dashed border-gray-200 rounded-xl px-5 py-4 mb-6 text-sm text-gray-500 text-center">
              <?php if(auth()->guard()->check()): ?>
                Hanya pembeli yang sudah membayar tiket yang dapat memberikan ulasan.
              <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="text-navy-mid font-semibold hover:underline">Masuk</a> untuk menulis ulasan.
              <?php endif; ?>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <div class="bg-gray-50 border border-dashed border-gray-200 rounded-xl px-5 py-4 mb-6 text-sm text-gray-500 text-center">
            <a href="<?php echo e(route('login')); ?>" class="text-navy-mid font-semibold hover:underline">Masuk</a> untuk menulis ulasan.
          </div>
        <?php endif; ?>

        
        <?php if($event->reviews->isNotEmpty()): ?>
          <div class="space-y-4">
            <?php $__currentLoopData = $event->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="review-card bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
              <div class="flex items-start gap-3">
                <img src="<?php echo e($review->user->avatar_url); ?>" alt="<?php echo e($review->user->nama_panggilan); ?>"
                     class="w-9 h-9 rounded-full object-cover flex-shrink-0 ring-2 ring-gray-100">
                <div class="flex-1 min-w-0">
                  <div class="flex items-center justify-between gap-2 flex-wrap">
                    <p class="text-sm font-semibold text-navy-deep"><?php echo e($review->user->nama_panggilan); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($review->created_at->diffForHumans()); ?></p>
                  </div>
                  <div class="flex gap-0.5 my-1">
                    <?php for($i=1;$i<=5;$i++): ?>
                      <span class="text-sm <?php echo e($i <= $review->rating ? 'text-gold' : 'text-gray-200'); ?>">★</span>
                    <?php endfor; ?>
                  </div>
                  <?php if($review->ulasan): ?>
                    <p class="text-sm text-gray-600 leading-relaxed mt-1"><?php echo e($review->ulasan); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php elseif($totalReview === 0): ?>
          <div class="text-center py-10 border border-dashed border-gray-200 rounded-xl">
            <p class="text-3xl mb-2">⭐</p>
            <p class="text-sm text-gray-400">Belum ada ulasan. Jadilah yang pertama!</p>
          </div>
        <?php endif; ?>

      </div>

    </div>

    <!-- RIGHT: Sticky Info Card -->
    <aside class="w-full lg:w-[360px] flex-shrink-0">
      <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-100 p-6 lg:sticky lg:top-28">
        <div class="space-y-5">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-navy-mid/5 flex items-center justify-center flex-shrink-0 text-navy-mid">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <p class="font-bold text-navy-deep"><?php echo e($event->tanggal_waktu->translatedFormat('l, d F Y')); ?></p>
              <p class="text-sm text-gray-500 mt-0.5"><?php echo e($event->tanggal_waktu->format('H:i')); ?> WIB</p>
            </div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-navy-mid/5 flex items-center justify-center flex-shrink-0 text-navy-mid">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
              <p class="font-bold text-navy-deep"><?php echo e($event->lokasi_kota); ?></p>
              <p class="text-sm text-gray-500 mt-0.5"><?php echo e($event->venue); ?></p>
              <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-800 mt-1 inline-block">Buka Maps</a>
            </div>
          </div>
        </div>
        <hr class="my-6 border-gray-100"/>
        <div>
          <p class="text-sm text-gray-500 mb-1">Mulai dari</p>
          <p class="text-2xl font-extrabold text-navy-deep mb-5">
            Rp <?php echo e(number_format($event->harga_termurah, 0, ',', '.')); ?>

          </p>
          <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('events.pilih-tiket', $event)); ?>"
               class="flex items-center justify-center w-full bg-gold text-navy-deep font-bold text-base py-3.5 rounded-xl hover:bg-gold-light transition-all hover:shadow-lg hover:shadow-gold/30">
              Beli Tiket Sekarang
            </a>
          <?php else: ?>
            <a href="<?php echo e(route('login')); ?>"
               class="flex items-center justify-center w-full bg-gold text-navy-deep font-bold text-base py-3.5 rounded-xl hover:bg-gold-light transition-all">
              Masuk untuk Beli Tiket
            </a>
          <?php endif; ?>
        </div>
      </div>
    </aside>

  </div>
</div>

<!-- MOBILE STICKY BUY BAR -->
<div class="buy-bar lg:hidden">
  <div class="flex items-center justify-between gap-4">
    <div>
      <p class="text-xs text-gray-500 mb-0.5">Harga Tiket</p>
      <p class="text-lg font-extrabold text-navy-deep">Rp <?php echo e(number_format($event->harga_termurah, 0, ',', '.')); ?></p>
    </div>
    <?php if(auth()->guard()->check()): ?>
      <a href="<?php echo e(route('events.pilih-tiket', $event)); ?>" class="flex-1 bg-gold text-navy-deep text-center font-bold py-3 rounded-xl hover:bg-gold-light transition-all text-sm shadow-md">
        Beli Tiket
      </a>
    <?php else: ?>
      <a href="<?php echo e(route('login')); ?>" class="flex-1 bg-gold text-navy-deep text-center font-bold py-3 rounded-xl text-sm shadow-md">
        Masuk untuk Beli
      </a>
    <?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
    });
  });
  function toggleFaq(btn) {
    const ans  = btn.nextElementSibling;
    const icon = btn.querySelector('svg');
    ans.style.display = ans.style.display === 'block' ? 'none' : 'block';
    icon.style.transform = ans.style.display === 'block' ? 'rotate(180deg)' : '';
  }
  let selectedRating = 0;
  function setRating(val) {
    selectedRating = val;
    document.getElementById('rating-input').value = val;
    document.querySelectorAll('#star-picker .star-btn').forEach((btn, idx) => {
      btn.classList.toggle('active', idx < val);
    });
    document.getElementById('rating-hint').classList.add('hidden');
  }
  function validateReview() {
    if (!selectedRating) {
      document.getElementById('rating-hint').classList.remove('hidden');
      return false;
    }
    return true;
  }
  // Auto-open ulasan tab if URL has #ulasan
  if (location.hash === '#ulasan') {
    document.querySelector('[data-tab="ulasan"]')?.click();
  }

  // ─── LIGHTBOX ──────────────────────────────────────────────────────
  <?php if($event->galleries->isNotEmpty()): ?>
  const lightboxImages = [
    '<?php echo e($event->cover_url); ?>',
    <?php $__currentLoopData = $event->galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>'<?php echo e($gal->url); ?>',<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  ];
  let lbIndex = 0;
  window.openLightbox = function(idx) {
    lbIndex = idx;
    const lb = document.getElementById('lightbox');
    lb.classList.remove('hidden');
    lb.classList.add('flex');
    document.body.style.overflow = 'hidden';
    updateLightbox();
  };
  function closeLightbox() {
    const lb = document.getElementById('lightbox');
    lb.classList.add('hidden');
    lb.classList.remove('flex');
    document.body.style.overflow = '';
  }
  window.closeLightbox = closeLightbox;
  function moveLightbox(dir) {
    lbIndex = (lbIndex + dir + lightboxImages.length) % lightboxImages.length;
    updateLightbox();
  }
  window.moveLightbox = moveLightbox;
  function updateLightbox() {
    document.getElementById('lightbox-img').src = lightboxImages[lbIndex];
    document.getElementById('lightbox-counter').textContent = (lbIndex+1) + ' / ' + lightboxImages.length;
  }
  document.addEventListener('keydown', function(e) {
    const lb = document.getElementById('lightbox');
    if (lb && !lb.classList.contains('hidden')) {
      if (e.key === 'ArrowLeft')  moveLightbox(-1);
      if (e.key === 'ArrowRight') moveLightbox(1);
      if (e.key === 'Escape')     closeLightbox();
    }
  });
  <?php endif; ?>
  function shareEvent() {
    if (navigator.share) { navigator.share({ title: document.title, url: location.href }); }
    else { navigator.clipboard.writeText(location.href); alert('Link disalin ke clipboard!'); }
  }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Application\Software\XAMPP\htdocs\ticketin-laravel\resources\views/events/show.blade.php ENDPATH**/ ?>