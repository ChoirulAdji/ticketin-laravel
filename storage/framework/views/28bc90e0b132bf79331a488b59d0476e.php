<?php $__env->startSection('title', 'Pilih Tiket — ' . $event->judul); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .form-input { border: 1.5px solid #e5e7eb; border-radius: 12px; outline: none; transition: all .2s; color: #1e293b; }
  .form-input:focus { border-color: #F5C400; box-shadow: 0 0 0 3px rgba(245,196,0,.15); }
  .ticket-card { background: white; border: 1.5px solid #e5e7eb; border-radius: 16px; transition: all .2s; }
  .ticket-card:hover { border-color: #F5C400; box-shadow: 0 4px 20px rgba(0,24,64,.08); }
  .qty-btn { width:32px; height:32px; border-radius:8px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; cursor:pointer; font-weight:700; transition:all .2s; border:none; }
  .qty-btn:hover { background:#F5C400; color:#001840; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pt-24 max-w-3xl mx-auto px-6 py-10">
  <a href="<?php echo e(route('events.show', $event)); ?>" class="flex items-center gap-2 text-navy-mid hover:text-gold transition-colors text-sm font-semibold mb-6">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali ke Detail Event
  </a>

  <div class="flex items-center gap-4 mb-8">
    <img src="<?php echo e($event->cover_url); ?>" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
    <div>
      <h1 class="text-xl font-extrabold text-navy-deep"><?php echo e($event->judul); ?></h1>
      <p class="text-gray-500 text-sm"><?php echo e($event->tanggal_waktu->format('d M Y')); ?> · <?php echo e($event->venue); ?>, <?php echo e($event->lokasi_kota); ?></p>
    </div>
  </div>

  <?php if($errors->any()): ?>
    <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl mb-6 text-sm"><?php echo e($errors->first()); ?></div>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('checkout.keranjang', $event)); ?>">
    <?php echo csrf_field(); ?>
    <h2 class="font-bold text-navy-deep text-base mb-4">Pilih Jumlah Tiket</h2>
    <div class="space-y-4 mb-8">
      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="ticket-card p-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="font-bold text-navy-deep"><?php echo e($cat->nama_kategori); ?></p>
            <p class="text-gold font-bold text-lg mt-1"><?php echo e($cat->harga > 0 ? 'Rp '.number_format($cat->harga,0,',','.') : 'GRATIS'); ?></p>
            <p class="text-gray-400 text-xs mt-0.5">Sisa: <?php echo e(number_format($cat->kuota,0,',','.')); ?> tiket</p>
          </div>
          <div class="flex items-center gap-3">
            <button type="button" onclick="changeQty('<?php echo e($cat->id); ?>',-1)" class="qty-btn">−</button>
            <input type="number" name="tickets[<?php echo e($cat->id); ?>]" id="qty-<?php echo e($cat->id); ?>"
                   class="form-input w-12 text-center font-bold py-1.5 text-sm" value="0" min="0" max="<?php echo e($cat->kuota); ?>" readonly>
            <button type="button" onclick="changeQty('<?php echo e($cat->id); ?>',1)" class="qty-btn">+</button>
          </div>
        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div id="totalPreview" class="hidden bg-navy-mid/5 border border-navy-mid/10 rounded-xl p-4 mb-6">
      <div class="flex justify-between text-sm text-gray-600 mb-1">
        <span>Total Tiket</span><span id="previewQty">0</span>
      </div>
      <div class="flex justify-between font-bold text-navy-deep">
        <span>Subtotal</span><span id="previewTotal" class="text-gold">Rp 0</span>
      </div>
    </div>

    <button type="submit" class="w-full bg-gold text-navy-deep font-bold py-4 rounded-xl hover:bg-gold-light transition-all text-sm hover:shadow-lg hover:shadow-gold/30">
      Lanjut ke Checkout →
    </button>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  const prices = { <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>'<?php echo e($cat->id); ?>': <?php echo e($cat->harga); ?>,<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> };
  function changeQty(id, d) {
    const inp = document.getElementById('qty-'+id);
    inp.value = Math.max(0, Math.min(+inp.getAttribute('max'), +inp.value+d));
    updateTotal();
  }
  function updateTotal() {
    let total=0, qty=0;
    Object.keys(prices).forEach(id => { const v=+document.getElementById('qty-'+id).value; total+=v*prices[id]; qty+=v; });
    const preview = document.getElementById('totalPreview');
    if(qty>0){ preview.classList.remove('hidden'); document.getElementById('previewQty').textContent=qty+' tiket'; document.getElementById('previewTotal').textContent='Rp '+total.toLocaleString('id-ID'); }
    else preview.classList.add('hidden');
  }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Application\Software\XAMPP\htdocs\ticketin-laravel\resources\views/checkout/pilih-tiket.blade.php ENDPATH**/ ?>