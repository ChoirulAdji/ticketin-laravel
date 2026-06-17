<?php $__env->startSection('title', 'Kelola Hero Slider'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-8">

  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-extrabold text-navy-deep"> Kelola Hero Slider</h1>
      <p class="text-sm text-gray-500 mt-0.5">Atur banner yang tampil di halaman utama website</p>
    </div>
    <a href="<?php echo e(url('/')); ?>" target="_blank"
       class="text-sm text-navy-mid hover:underline font-semibold flex items-center gap-1">
      👁 Preview Website
    </a>
  </div>

  
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
    <h3 class="font-bold text-navy-deep mb-2">+ Tambah Slide Baru</h3>
    <p class="text-sm text-gray-500 mb-5">Pilih salah satu cara: pakai foto event yang ada, atau upload foto banner sendiri.</p>

    
    <div class="flex gap-2 mb-5" id="slide-mode-tabs">
      <button type="button" onclick="setMode('event')"
        id="tab-event"
        class="flex-1 py-2.5 rounded-xl text-sm font-semibold border-2 border-navy-mid bg-navy-mid text-white transition">
        Dari Event
      </button>
      <button type="button" onclick="setMode('custom')"
        id="tab-custom"
        class="flex-1 py-2.5 rounded-xl text-sm font-semibold border-2 border-gray-200 bg-white text-gray-500 transition">
        Banner Custom
      </button>
    </div>

    <form method="POST" action="<?php echo e(route('admin.hero-slider.store')); ?>" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>

      
      <div id="mode-event">
        <label class="text-xs font-semibold text-gray-500 block mb-1">Pilih Event</label>
        <select name="event_id" id="select-event"
                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-navy-mid mb-1">
          <option value="">— Pilih event —</option>
          <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($event->id); ?>"><?php echo e($event->judul); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <p class="text-xs text-gray-400 mb-4">Foto cover dan link event akan dipakai otomatis untuk slider.</p>
      </div>

      
      <div id="mode-custom" class="hidden">
        <div class="space-y-3">
          <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Upload Foto Banner <span class="text-red-400">*</span></label>
            <input type="file" name="gambar" accept="image/*"
                   class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-500">
            <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP — maks 3MB. Ukuran ideal: 1400×500px.</p>
          </div>
          <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Teks Judul (opsional)</label>
            <input type="text" name="judul" placeholder="Contoh: Promo Akhir Tahun "
                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-navy-mid">
          </div>
          <div>
            <label class="text-xs font-semibold text-gray-500 block mb-1">Link saat diklik (opsional)</label>
            <input type="url" name="url_tujuan" placeholder="https://..."
                   class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-navy-mid">
            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak perlu link.</p>
          </div>
        </div>
      </div>

      <div class="mt-5 flex justify-end">
        <button type="submit"
                class="bg-navy-mid text-white font-bold text-sm px-6 py-2.5 rounded-xl hover:bg-navy-deep transition">
          ✅ Tambah Slide
        </button>
      </div>
    </form>
  </div>

  <?php $__env->startPush('scripts'); ?>
  <script>
  function setMode(mode) {
    document.getElementById('mode-event').classList.toggle('hidden', mode !== 'event');
    document.getElementById('mode-custom').classList.toggle('hidden', mode !== 'custom');
    document.getElementById('tab-event').className  = 'flex-1 py-2.5 rounded-xl text-sm font-semibold border-2 transition ' +
      (mode === 'event'  ? 'border-navy-mid bg-navy-mid text-white' : 'border-gray-200 bg-white text-gray-500');
    document.getElementById('tab-custom').className = 'flex-1 py-2.5 rounded-xl text-sm font-semibold border-2 transition ' +
      (mode === 'custom' ? 'border-navy-mid bg-navy-mid text-white' : 'border-gray-200 bg-white text-gray-500');
    // Reset unused fields
    if (mode === 'event') {
      document.querySelector('input[name="gambar"]').value = '';
      document.querySelector('input[name="url_tujuan"]').value = '';
    } else {
      document.getElementById('select-event').value = '';
    }
  }
  </script>
  <?php $__env->stopPush(); ?>

  
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
      <h3 class="font-bold text-navy-deep">Slide Aktif (<?php echo e($sliders->count()); ?>)</h3>
      <p class="text-xs text-gray-400">Drag untuk ubah urutan</p>
    </div>

    <?php if($sliders->isEmpty()): ?>
      <div class="py-16 text-center">
        <p class="text-4xl mb-3">🖼️</p>
        <p class="text-gray-500 text-sm">Belum ada slide. Slider akan otomatis pakai foto event terbaru.</p>
      </div>
    <?php else: ?>
      <ul id="slider-list" class="divide-y divide-gray-50">
        <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="flex items-center gap-4 px-6 py-4 hover:bg-gray-50 transition" data-id="<?php echo e($slider->id); ?>">

          
          <div class="cursor-grab text-gray-300 hover:text-gray-500 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
            </svg>
          </div>

          
          <div class="w-24 h-14 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
            <img src="<?php echo e($slider->image_url); ?>" class="w-full h-full object-cover"
                 onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400'">
          </div>

          
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-sm text-navy-deep truncate">
              <?php echo e($slider->title ?: '(Tanpa judul)'); ?>

            </p>
            <p class="text-xs text-gray-400 mt-0.5">
              <?php if($slider->event_id): ?>
                📅 Event: <?php echo e($slider->event?->judul); ?>

              <?php else: ?>
                🖼️ Banner custom
              <?php endif; ?>
            </p>
            <?php if($slider->url_tujuan): ?>
            <p class="text-xs text-blue-400 truncate">🔗 <?php echo e($slider->url_tujuan); ?></p>
            <?php endif; ?>
          </div>

          
          <div class="flex-shrink-0 text-xs text-gray-400 font-semibold w-8 text-center">
            #<?php echo e($slider->urutan + 1); ?>

          </div>

          
          <form method="POST" action="<?php echo e(route('admin.hero-slider.toggle', $slider)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit"
              class="px-3 py-1.5 rounded-lg text-xs font-bold transition
                <?php echo e($slider->aktif
                  ? 'bg-green-100 text-green-700 hover:bg-green-200'
                  : 'bg-gray-100 text-gray-500 hover:bg-gray-200'); ?>">
              <?php echo e($slider->aktif ? '✅ Aktif' : '⏸ Nonaktif'); ?>

            </button>
          </form>

          
          <form method="POST" action="<?php echo e(route('admin.hero-slider.destroy', $slider)); ?>"
                onsubmit="return confirm('Hapus slide ini?')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit"
              class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
              </svg>
            </button>
          </form>

        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    <?php endif; ?>
  </div>

  
  <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3 text-sm text-blue-700 flex items-start gap-2">
    <span class="text-lg flex-shrink-0">💡</span>
    <span>Jika tidak ada slide yang dikonfigurasi di sini, slider akan otomatis menampilkan foto cover dari event-event yang sudah dipublish.</span>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/admin/hero-slider.blade.php ENDPATH**/ ?>