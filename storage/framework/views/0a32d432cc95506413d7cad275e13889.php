<?php $__env->startSection('title','404 — Halaman Tidak Ditemukan'); ?>
<?php $__env->startSection('content'); ?>
<div class="pt-24 max-w-2xl mx-auto px-6 py-32 text-center">
  <div class="text-7xl mb-6"></div>
  <h1 class="text-4xl font-extrabold text-navy-deep mb-3">404</h1>
  <p class="text-xl text-gray-500 mb-8">Halaman yang kamu cari tidak ada atau sudah dipindah.</p>
  <div class="flex gap-3 justify-center">
    <a href="<?php echo e(route('dashboard')); ?>" class="bg-gold text-navy-deep font-bold px-6 py-3 rounded-xl hover:bg-gold-light transition-all text-sm">Beranda</a>
    <a href="<?php echo e(route('events.index')); ?>" class="bg-navy-mid text-white font-bold px-6 py-3 rounded-xl hover:bg-navy-deep transition-all text-sm">Lihat Event</a>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/errors/404.blade.php ENDPATH**/ ?>