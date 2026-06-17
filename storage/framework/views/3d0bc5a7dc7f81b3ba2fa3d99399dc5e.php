<?php $__env->startSection('title','Pesanan — ' . $event->judul); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-6 py-10">
  <div class="flex items-center gap-3 mb-2">
    <a href="<?php echo e(route('pengelola.dashboard')); ?>" class="text-gray-400 hover:text-navy-mid transition-colors">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="text-2xl font-extrabold text-navy-deep">Laporan Pesanan</h1>
  </div>
  <div class="flex items-center gap-4 mb-8">
    <img src="<?php echo e($event->cover_url); ?>" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
    <div><p class="font-bold text-navy-deep"><?php echo e($event->judul); ?></p><p class="text-gray-400 text-sm"><?php echo e($event->tanggal_waktu->format('d M Y')); ?> · <?php echo e($event->venue); ?>, <?php echo e($event->lokasi_kota); ?></p></div>
  </div>

  <?php if(session('success')): ?>
    <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-xl text-sm mb-6"> <?php echo e(session('success')); ?></div>
  <?php endif; ?>

  <!-- Stats -->
  <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <?php $__currentLoopData = [['Total Pesanan',$stats['total'],'text-navy-deep'],[' Lunas',$stats['paid'],'text-green-600'],[' Pending',$stats['pending'],'text-yellow-600'],[' Dibatalkan',$stats['cancelled'],'text-red-600'],[' Pendapatan','Rp '.number_format($stats['pendapatan'],0,',','.'),'text-yellow-600']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$l,$v,$c]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 text-center <?php echo e($loop->last ? 'col-span-2 lg:col-span-1' : ''); ?>">
      <p class="text-lg font-extrabold <?php echo e($c); ?>"><?php echo e($v); ?></p>
      <p class="text-gray-400 text-xs mt-1"><?php echo e($l); ?></p>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <!-- Table -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="font-bold text-navy-deep text-lg mb-5"> Daftar Pesanan</h2>
    <?php if($orders->isEmpty()): ?>
      <div class="py-16 text-center"><div class="text-5xl mb-3"></div><p class="text-gray-500">Belum ada pesanan untuk event ini.</p></div>
    <?php else: ?>
      <div class="space-y-3">
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 bg-gray-50 border border-gray-100 rounded-xl p-4 hover:border-navy-mid/20 transition-colors">
          <div class="flex items-center gap-3 flex-1 min-w-0">
            <img src="<?php echo e($order->user->avatar_url); ?>" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
            <div class="min-w-0">
              <p class="font-semibold text-navy-deep text-sm truncate"><?php echo e($order->user->nama_lengkap); ?></p>
              <p class="text-gray-400 text-xs truncate"><?php echo e($order->user->email); ?></p>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-navy-mid font-mono text-xs font-bold"><?php echo e($order->order_code); ?></p>
            <p class="text-gray-500 text-xs mt-0.5"><?php echo e($order->ticket_summary); ?></p>
            <p class="text-gray-300 text-xs mt-0.5"><?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
          </div>
          <div class="text-right flex-shrink-0">
            <p class="font-bold text-navy-deep text-sm">Rp <?php echo e(number_format($order->total_harga,0,',','.')); ?></p>
            <p class="text-gray-400 text-xs"><?php echo e($order->metode_bayar); ?></p>
          </div>
          <div class="flex items-center gap-2 flex-shrink-0">
            <span class="text-xs font-semibold px-3 py-1 rounded-full
              <?php echo e($order->status==='paid' ? 'bg-green-100 text-green-700' : ($order->status==='pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')); ?>">
              <?php echo e($order->status==='paid' ? ' Lunas' : ($order->status==='pending' ? ' Pending' : ' Batal')); ?>

            </span>
            <form method="POST" action="<?php echo e(route('pengelola.pesanan.status',$order)); ?>">
              <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
              <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded-lg px-2 py-1.5 text-gray-700 outline-none focus:border-gold bg-white">
                <option value="pending" <?php echo e($order->status==='pending'?'selected':''); ?>>Pending</option>
                <option value="paid" <?php echo e($order->status==='paid'?'selected':''); ?>>Lunas</option>
                <option value="cancelled" <?php echo e($order->status==='cancelled'?'selected':''); ?>>Batal</option>
              </select>
            </form>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <div class="mt-6"><?php echo e($orders->links()); ?></div>
    <?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pengelola', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/pengelola/pesanan.blade.php ENDPATH**/ ?>