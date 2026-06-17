<?php $__env->startSection('title', 'TicketIn — Semua Event'); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<main class="pt-24 max-w-7xl mx-auto px-4 sm:px-6 py-10">

  
  <div class="mb-8">
    <h1 class="text-3xl font-extrabold text-navy-deep">Semua Event</h1>
    <p class="text-gray-500 text-sm mt-1">
      Menampilkan <span class="font-semibold text-navy-mid"><?php echo e($events->total()); ?></span>
      dari <span class="font-semibold"><?php echo e($totalEvents); ?></span> event tersedia
      <?php if(request()->hasAny(['search','kategori','kota'])): ?>
      &mdash; <a href="<?php echo e(route('events.index')); ?>" class="text-red-400 hover:underline text-xs font-semibold">Reset semua filter</a>
      <?php endif; ?>
    </p>
  </div>

  <div class="flex flex-col lg:flex-row gap-8 items-start">

    
    <aside class="w-full lg:w-64 flex-shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:sticky lg:top-28">

      <form method="GET" action="<?php echo e(route('events.index')); ?>" id="filter-form">
        
        <input type="hidden" name="sort" value="<?php echo e(request('sort', 'terdekat')); ?>">

        
        <div class="mb-6 relative">
          <input type="text" name="search" value="<?php echo e(request('search')); ?>"
            placeholder="Cari nama event..."
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-gold focus:ring-2 focus:ring-gold/20 outline-none text-sm transition-all"
            oninput="clearTimeout(window._st); window._st=setTimeout(()=>this.form.submit(),600)">
          <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>

        
        <div class="mb-6">
          <h4 class="font-bold text-navy-deep mb-3 text-sm">Kategori</h4>
          <div class="space-y-2">
            <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="flex items-center gap-3 text-sm text-gray-600 hover:text-navy-deep cursor-pointer group">
              <input type="checkbox" name="kategori[]" value="<?php echo e($kat); ?>"
                class="accent-gold rounded"
                <?php echo e(in_array($kat, (array) request('kategori', [])) ? 'checked' : ''); ?>

                onchange="this.form.submit()">
              <span class="group-hover:font-medium transition-all"><?php echo e($kat); ?></span>
              <?php $cnt = \App\Models\Event::published()->where('kategori',$kat)->count() ?>
              <span class="ml-auto text-xs text-gray-300 font-medium"><?php echo e($cnt); ?></span>
            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>

        <hr class="border-gray-100 my-5">

        
        <div class="mb-6">
          <h4 class="font-bold text-navy-deep mb-3 text-sm">Lokasi Kota</h4>
          <div class="space-y-2">
            <?php $__currentLoopData = $kotas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="flex items-center gap-3 text-sm text-gray-600 hover:text-navy-deep cursor-pointer group">
              <input type="checkbox" name="kota[]" value="<?php echo e($kota); ?>"
                class="accent-gold rounded"
                <?php echo e(in_array($kota, (array) request('kota', [])) ? 'checked' : ''); ?>

                onchange="this.form.submit()">
              <span class="group-hover:font-medium transition-all"><?php echo e($kota); ?></span>
              <?php $cnt = \App\Models\Event::published()->where('lokasi_kota',$kota)->count() ?>
              <span class="ml-auto text-xs text-gray-300 font-medium"><?php echo e($cnt); ?></span>
            </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>

        <?php if(request()->hasAny(['search','kategori','kota'])): ?>
        <a href="<?php echo e(route('events.index', ['sort' => request('sort','terdekat')])); ?>"
          class="block w-full py-2.5 text-sm font-semibold text-red-500 bg-red-50 hover:bg-red-100 rounded-xl transition-colors text-center">
          Reset Filter
        </a>
        <?php endif; ?>
      </form>
    </aside>

    
    <div class="flex-1 w-full min-w-0">

      
      <div class="flex flex-wrap items-center gap-2 mb-6">
        <span class="text-xs font-semibold text-gray-500 mr-1">Urutkan:</span>
        <?php $__currentLoopData = ['terdekat'=>' Terdekat','terbaru'=>' Terbaru','termurah'=>' Termurah','termahal'=>' Termahal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(request()->fullUrlWithQuery(['sort'=>$val, 'page'=>1])); ?>"
          class="sort-btn px-3 py-1.5 text-xs rounded-full border border-gray-200 bg-white text-gray-600 hover:border-navy-mid <?php echo e(request('sort','terdekat') === $val ? 'active' : ''); ?>">
          <?php echo e($label); ?>

        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php $__currentLoopData = (array) request('kategori', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(request()->fullUrlWithQuery(['kategori' => array_values(array_diff((array)request('kategori',[]), [$kat])), 'page'=>1])); ?>"
          class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-navy-mid/10 text-navy-mid font-semibold rounded-full hover:bg-red-50 hover:text-red-500 transition">
          <?php echo e($kat); ?> ×
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = (array) request('kota', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kota): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(request()->fullUrlWithQuery(['kota' => array_values(array_diff((array)request('kota',[]), [$kota])), 'page'=>1])); ?>"
          class="inline-flex items-center gap-1 px-3 py-1.5 text-xs bg-navy-mid/10 text-navy-mid font-semibold rounded-full hover:bg-red-50 hover:text-red-500 transition">
          <?php echo e($kota); ?> ×
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      
      <?php if($events->isNotEmpty()): ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('partials.event-card', ['event' => $row], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      
      <?php if($events->hasPages()): ?>
      <div class="mt-10 flex flex-col items-center gap-3">
        
        <p class="text-xs text-gray-400">
          Halaman <?php echo e($events->currentPage()); ?> dari <?php echo e($events->lastPage()); ?>

          &nbsp;·&nbsp; <?php echo e($events->firstItem()); ?>–<?php echo e($events->lastItem()); ?> dari <?php echo e($events->total()); ?> event
        </p>

        
        <nav class="flex items-center gap-1 flex-wrap justify-center" aria-label="Pagination">

          
          <?php if($events->onFirstPage()): ?>
          <span class="px-3 py-2 rounded-xl border border-gray-100 text-gray-300 text-sm cursor-not-allowed bg-gray-50">‹</span>
          <?php else: ?>
          <a href="<?php echo e($events->previousPageUrl()); ?>" class="px-3 py-2 rounded-xl border border-gray-200 text-navy-mid text-sm hover:bg-navy-mid hover:text-white hover:border-navy-mid transition font-semibold">‹</a>
          <?php endif; ?>

          
          <?php
          $current = $events->currentPage();
          $last = $events->lastPage();
          $range = 2; // pages around current
          $pages = collect();
          for ($p = max(1,$current-$range); $p <= min($last,$current+$range); $p++) $pages->push($p);
            ?>

            <?php if($pages->first() > 1): ?>
            <a href="<?php echo e($events->url(1)); ?>" class="px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 transition">1</a>
            <?php if($pages->first() > 2): ?>
            <span class="px-2 text-gray-300 text-sm">…</span>
            <?php endif; ?>
            <?php endif; ?>

            <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($page === $current): ?>
            <span class="px-3 py-2 rounded-xl border text-sm font-bold bg-navy-mid text-white border-navy-mid"><?php echo e($page); ?></span>
            <?php else: ?>
            <a href="<?php echo e($events->url($page)); ?>" class="px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 transition"><?php echo e($page); ?></a>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($pages->last() < $last): ?>
              <?php if($pages->last() < $last - 1): ?>
                <span class="px-2 text-gray-300 text-sm">…</span>
                <?php endif; ?>
                <a href="<?php echo e($events->url($last)); ?>" class="px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 transition"><?php echo e($last); ?></a>
                <?php endif; ?>

                
                <?php if($events->hasMorePages()): ?>
                <a href="<?php echo e($events->nextPageUrl()); ?>" class="px-3 py-2 rounded-xl border border-gray-200 text-navy-mid text-sm hover:bg-navy-mid hover:text-white hover:border-navy-mid transition font-semibold">›</a>
                <?php else: ?>
                <span class="px-3 py-2 rounded-xl border border-gray-100 text-gray-300 text-sm cursor-not-allowed bg-gray-50">›</span>
                <?php endif; ?>
        </nav>
      </div>
      <?php endif; ?>

      <?php else: ?>
      <div class="py-24 text-center bg-white rounded-2xl border border-dashed border-gray-200">

        <h3 class="text-lg font-bold text-navy-deep mb-1">Event tidak ditemukan</h3>
        <p class="text-sm text-gray-500 mb-5">Coba ubah kata kunci atau hapus beberapa filter.</p>
        <a href="<?php echo e(route('events.index')); ?>"
          class="inline-block bg-gold text-navy-deep font-bold text-sm px-6 py-3 rounded-xl hover:bg-gold-light transition">
          Lihat Semua Event
        </a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/events/index.blade.php ENDPATH**/ ?>