<?php $__env->startSection('title', 'Status Pengajuan EO — TicketIn'); ?>

<?php $__env->startSection('content'); ?>
<div class="pt-24 max-w-2xl mx-auto px-6 py-16 text-center">

  <?php if($app->status === 'pending'): ?>
  <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
    <svg class="w-10 h-10 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  </div>
  <h1 class="text-2xl font-extrabold text-navy-deep mb-2">Pengajuan Sedang Direview</h1>
  <p class="text-gray-500 mb-2">Pengajuan EO kamu untuk <strong class="text-navy-mid"><?php echo e($app->nama_organisasi); ?></strong> sedang dalam proses review oleh tim admin.</p>
  <p class="text-gray-400 text-sm mb-8">Proses verifikasi memakan waktu 1-3 hari kerja. Kami akan menghubungi kamu melalui email setelah selesai.</p>

  <?php elseif($app->status === 'approved'): ?>
  <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
    <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
  </div>
  <h1 class="text-2xl font-extrabold text-navy-deep mb-2">Pengajuan Disetujui! 🎉</h1>
  <p class="text-gray-500 mb-8">Selamat! Akun kamu sudah aktif sebagai Pengelola Event. Kamu bisa langsung mulai buat event.</p>
  <a href="<?php echo e(route('pengelola.dashboard')); ?>" class="inline-block bg-gold text-navy-deep font-bold px-8 py-3.5 rounded-xl hover:bg-gold-light transition-all text-sm">
    🎭 Buka Dashboard EO
  </a>

  <?php else: ?> 
  <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
    <svg class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
  </div>
  <h1 class="text-2xl font-extrabold text-navy-deep mb-2">Pengajuan Ditolak</h1>
  <p class="text-gray-500 mb-4">Mohon maaf, pengajuan EO kamu untuk <strong><?php echo e($app->nama_organisasi); ?></strong> tidak dapat kami setujui saat ini.</p>
  <?php if($app->catatan_admin): ?>
  <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-sm text-red-700 text-left">
    <p class="font-bold mb-1">Catatan dari Admin:</p>
    <p><?php echo e($app->catatan_admin); ?></p>
  </div>
  <?php endif; ?>
  <p class="text-gray-400 text-sm mb-8">Kamu bisa mengajukan kembali dengan melengkapi dokumen yang diperlukan.</p>
  <?php endif; ?>

  
  <div class="bg-white border border-gray-100 rounded-2xl p-6 text-left shadow-sm mt-6">
    <h3 class="font-bold text-navy-deep mb-4">Detail Pengajuan</h3>
    <div class="space-y-3 text-sm">
      <?php $__currentLoopData = [
        ['Nama Organisasi', $app->nama_organisasi],
        ['Jenis Entitas', ucfirst($app->jenis_entitas)],
        ['Skala Event', ucfirst($app->skala_event)],
        ['Alamat', $app->alamat_organisasi],
        ['No. HP Bisnis', $app->no_hp_bisnis],
        ['Bank', strtoupper($app->bank)],
        ['No. Rekening', $app->nomor_rekening],
        ['Nama Rekening', $app->nama_rekening],
        ['Tanggal Pengajuan', $app->created_at->format('d M Y, H:i')],
      ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="flex justify-between gap-4">
        <span class="text-gray-500 flex-shrink-0"><?php echo e($label); ?></span>
        <span class="font-semibold text-navy-deep text-right"><?php echo e($value ?? '-'); ?></span>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>

  <?php if($app->status === 'pending'): ?>
  <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-700 flex gap-3 items-start text-left">
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p>Halaman ini akan otomatis terupdate setelah admin memproses pengajuanmu. Kamu juga akan mendapat notifikasi melalui email.</p>
  </div>
  <?php endif; ?>

  <div class="mt-6">
    <a href="<?php echo e(route('dashboard')); ?>" class="text-navy-mid hover:text-gold font-semibold text-sm transition-colors">← Kembali ke Beranda</a>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Application\Software\XAMPP\htdocs\ticketin-laravel\resources\views/eo/status.blade.php ENDPATH**/ ?>