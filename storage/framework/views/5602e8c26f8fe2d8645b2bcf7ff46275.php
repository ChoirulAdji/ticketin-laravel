<?php $__env->startSection('title', 'Manajemen Event'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">

  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-extrabold text-navy-deep">Manajemen Event</h1>
    <?php if(($counts['pending_review'] ?? 0) > 0): ?>
    <span class="bg-amber-500 text-white text-sm font-bold px-3 py-1.5 rounded-full animate-pulse">
      <?php echo e($counts['pending_review']); ?> menunggu review
    </span>
    <?php endif; ?>
  </div>

  
  <div class="flex flex-wrap gap-2 mb-6">
    <?php $__currentLoopData = [
      'pending_review' => ['label' => 'Menunggu Review', 'color' => 'amber'],
      'published'      => ['label' => 'Published',       'color' => 'green'],
      'draft'          => ['label' => 'Draft / Ditolak', 'color' => 'gray'],
      'cancelled'      => ['label' => 'Cancelled',       'color' => 'red'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s => $cfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(request()->fullUrlWithQuery(['status' => $s, 'page' => 1])); ?>"
       class="px-4 py-2 rounded-xl text-sm font-semibold border transition
              <?php echo e($status === $s
                ? 'bg-navy-mid text-white border-navy-mid'
                : 'bg-white text-gray-600 border-gray-200 hover:border-navy-mid'); ?>">
      <?php echo e($cfg['label']); ?>

      <span class="ml-1 text-xs opacity-75">(<?php echo e($counts[$s] ?? 0); ?>)</span>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  
  <form method="GET" class="mb-4 flex gap-2">
    <input type="hidden" name="status" value="<?php echo e($status); ?>">
    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
           placeholder="Cari judul event..."
           class="flex-1 border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-navy-mid">
    <button type="submit" class="bg-navy-mid text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-navy-deep transition">Cari</button>
  </form>

  
  <?php if($events->isEmpty()): ?>
    <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200">
      <p class="text-gray-500">Tidak ada event dengan status ini.</p>
    </div>
  <?php else: ?>
  <div class="space-y-4">
    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="flex flex-col sm:flex-row gap-4 p-5">

        
        <div class="w-full sm:w-40 h-28 rounded-xl overflow-hidden flex-shrink-0 bg-gray-100">
          <img src="<?php echo e($event->cover_url); ?>" class="w-full h-full object-cover"
               onerror="this.src='https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400'">
        </div>

        
        <div class="flex-1 min-w-0">
          <div class="flex items-start justify-between gap-2 flex-wrap">
            <div>
              <h3 class="font-bold text-navy-deep text-sm"><?php echo e($event->judul); ?></h3>
              <p class="text-xs text-gray-500 mt-0.5">
                oleh <span class="font-semibold"><?php echo e($event->pengelola->nama_lengkap ?? '-'); ?></span>
                · <?php echo e($event->tanggal_waktu->format('d M Y H:i')); ?>

                · <?php echo e($event->lokasi_kota); ?>

              </p>
              <p class="text-xs text-gray-400 mt-0.5"><?php echo e($event->orders_count); ?> pesanan</p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold flex-shrink-0
              <?php echo e($event->status === 'published'      ? 'bg-green-100 text-green-700' :
                 ($event->status === 'pending_review' ? 'bg-amber-100 text-amber-700' :
                 ($event->status === 'draft'          ? 'bg-gray-100 text-gray-600' :
                                                        'bg-red-100 text-red-600'))); ?>">
              <?php echo e($event->status === 'pending_review' ? 'Menunggu Review' :
                 ($event->status === 'published'     ? 'Published' :
                 ($event->status === 'draft'         ? 'Draft' : 'Cancelled'))); ?>

            </span>
          </div>

          
          <?php if($event->catatan_admin): ?>
          <div class="mt-2 bg-red-50 border border-red-100 rounded-lg px-3 py-2 text-xs text-red-700">
            <span class="font-semibold">Catatan admin:</span> <?php echo e($event->catatan_admin); ?>

          </div>
          <?php endif; ?>
        </div>

        
        <div class="flex flex-col gap-2 flex-shrink-0 justify-center">
          <?php if($event->status === 'pending_review'): ?>

          
          <form method="POST" action="<?php echo e(route('admin.events.approve', $event)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit"
              class="w-full bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition"
              onclick="return confirm('Approve event ini? Akan langsung tampil ke publik.')">
              Approve
            </button>
          </form>

          
          <button type="button"
            onclick="showRejectModal(<?php echo e($event->id); ?>, '<?php echo e(addslashes($event->judul)); ?>')"
            class="w-full bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
            Tolak
          </button>

          <?php elseif($event->status === 'published'): ?>
          <form method="POST" action="<?php echo e(route('admin.events.reject', $event)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="alasan" value="Diturunkan oleh admin.">
            <button type="submit"
              class="w-full bg-orange-400 hover:bg-orange-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition"
              onclick="return confirm('Turunkan event ini dari publik?')">
              Turunkan
            </button>
          </form>
          <?php endif; ?>

          <a href="<?php echo e(route('events.show', $event)); ?>" target="_blank"
             class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-xl transition">
            Preview
          </a>

          <form method="POST" action="<?php echo e(route('admin.events.hapus', $event)); ?>"
                onsubmit="return confirm('Hapus event ini permanen?')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button type="submit"
              class="w-full bg-white border border-red-200 hover:bg-red-50 text-red-500 text-xs font-semibold px-4 py-2 rounded-xl transition">
              Hapus
            </button>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  
  <div class="mt-6"><?php echo e($events->withQueryString()->links()); ?></div>
  <?php endif; ?>
</div>


<div id="reject-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl p-6">
    <h3 class="font-bold text-navy-deep text-lg mb-1">Tolak Event</h3>
    <p id="reject-event-name" class="text-sm text-gray-500 mb-4"></p>
    <form id="reject-form" method="POST">
      <?php echo csrf_field(); ?>
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

<?php $__env->startPush('scripts'); ?>
<script>
const rejectRoutes = {
  <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php echo e($event->id); ?>: "<?php echo e(route('admin.events.reject', $event)); ?>",
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/admin/events.blade.php ENDPATH**/ ?>