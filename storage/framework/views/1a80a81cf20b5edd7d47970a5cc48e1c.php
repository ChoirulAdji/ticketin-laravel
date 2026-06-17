<?php $__env->startSection('title','Dashboard Admin'); ?>

<?php $__env->startPush('styles'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
  .stat-card { background:white; border:1px solid #e5e7eb; border-radius:16px; padding:20px; transition:all .2s; }
  .stat-card:hover { box-shadow:0 8px 24px rgba(0,24,64,.08); transform:translateY(-2px); }
  .chart-bar { border-radius:6px 6px 0 0; transition:all .3s; min-width:32px; }
  .chart-bar:hover { opacity:.8; }
  .badge-paid { background:rgba(34,197,94,.15); color:#16a34a; border:1px solid rgba(34,197,94,.3); }
  .badge-pending { background:rgba(234,179,8,.15); color:#b45309; border:1px solid rgba(234,179,8,.3); }
  .badge-cancelled { background:rgba(239,68,68,.15); color:#dc2626; border:1px solid rgba(239,68,68,.3); }
  .progress-bar { height:6px; border-radius:99px; background:#e5e7eb; overflow:hidden; }
  .progress-fill { height:100%; border-radius:99px; background:linear-gradient(90deg,#102A71,#F5C400); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-7xl mx-auto">

  <div class="mb-8">
    <h1 class="text-2xl font-extrabold text-navy-deep">Dashboard Admin</h1>
    <p class="text-gray-400 text-sm mt-0.5">Pantau seluruh aktivitas platform TicketIn</p>
  </div>

  
  <?php if($stats['pending_eo'] > 0): ?>
  <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <p class="text-yellow-700 font-semibold text-sm">Ada <strong><?php echo e($stats['pending_eo']); ?></strong> pengajuan EO yang menunggu verifikasi!</p>
    </div>
    <a href="<?php echo e(route('admin.pengajuan-eo')); ?>" class="text-xs bg-yellow-500 text-white font-bold px-4 py-2 rounded-lg hover:bg-yellow-600 transition-all">Proses Sekarang</a>
  </div>
  <?php endif; ?>

  
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <?php $__currentLoopData = [
      ['Total User',$stats['total_user'],'text-navy-deep','bg-navy-mid/10',route('admin.users')],
      ['Total EO',$stats['total_eo'],'text-purple-700','bg-purple-50',route('admin.users').'?role=pengelola'],
      ['Total Event',$stats['total_event'],'text-blue-700','bg-blue-50',route('admin.events')],
      ['Total Pesanan',$stats['total_pesanan'],'text-green-700','bg-green-50',route('admin.pesanan')],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label,$val,$color,$bg,$link]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e($link); ?>" class="stat-card block">
      <div class="flex items-center justify-between mb-3">
      </div>
      <p class="text-3xl font-extrabold <?php echo e($color); ?>"><?php echo e(number_format($val)); ?></p>
      <p class="text-gray-400 text-xs mt-1"><?php echo e($label); ?></p>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="stat-card">
      <p class="text-2xl font-extrabold text-navy-deep">Rp <?php echo e(number_format($stats['total_pendapatan']/1000000,1)); ?>jt</p>
      <p class="text-gray-400 text-xs mt-1">Total Pendapatan Platform</p>
    </div>
    <a href="<?php echo e(route('admin.pengajuan-eo')); ?>" class="stat-card block">
      <p class="text-3xl font-extrabold text-yellow-600"><?php echo e($stats['pending_eo']); ?></p>
      <p class="text-gray-400 text-xs mt-1">Pengajuan EO Pending</p>
    </a>
    <a href="<?php echo e(route('admin.events')); ?>" class="stat-card block">
      <p class="text-3xl font-extrabold text-blue-600"><?php echo e($stats['pending_event']); ?></p>
      <p class="text-gray-400 text-xs mt-1">Event Draft</p>
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    
    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h2 class="font-bold text-navy-deep mb-6">Pendapatan Platform (6 Bulan)</h2>
      <?php if($grafikPendapatan->isEmpty()): ?>
        <div class="flex items-center justify-center h-40 text-gray-400 text-sm">Belum ada data</div>
      <?php else: ?>
        <?php $maxPend = $grafikPendapatan->max('total') ?: 1; ?>
        <div class="flex items-end gap-3 h-48 mb-3">
          <?php $__currentLoopData = $grafikPendapatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="flex flex-col items-center gap-1 flex-1">
            <span class="text-xs text-gray-400"><?php echo e(number_format($g['total']/1000000,1)); ?>jt</span>
            <div class="chart-bar w-full bg-navy-mid" style="height:<?php echo e(max(4,($g['total']/$maxPend)*160)); ?>px"
                 title="<?php echo e($g['bulan']); ?>: Rp <?php echo e(number_format($g['total'],0,',','.')); ?>"></div>
            <span class="text-xs text-gray-400 text-center" style="font-size:10px;"><?php echo e($g['bulan']); ?></span>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="flex items-center gap-4 pt-3 border-t border-gray-100 text-xs text-gray-400">
          <span>Total: <strong class="text-navy-deep">Rp <?php echo e(number_format($grafikPendapatan->sum('total'),0,',','.')); ?></strong></span>
          <span>Pesanan: <strong class="text-navy-deep"><?php echo e($grafikPendapatan->sum('pesanan')); ?></strong></span>
        </div>
      <?php endif; ?>
    </div>

    
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-navy-deep">Pengajuan EO</h2>
        <a href="<?php echo e(route('admin.pengajuan-eo')); ?>" class="text-xs text-navy-mid font-semibold hover:text-gold">Lihat Semua</a>
      </div>
      <?php if($pengajuanPending->isEmpty()): ?>
        <div class="text-center py-8 text-gray-400 text-sm">Tidak ada pengajuan pending</div>
      <?php else: ?>
        <div class="space-y-3">
          <?php $__currentLoopData = $pengajuanPending; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="flex items-center gap-3 p-3 bg-yellow-50 border border-yellow-100 rounded-xl">
            <img src="<?php echo e($app->user->avatar_url); ?>" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-navy-deep text-xs truncate"><?php echo e($app->user->nama_lengkap); ?></p>
              <p class="text-gray-500 text-xs truncate"><?php echo e($app->nama_organisasi); ?></p>
              <p class="text-gray-400 text-xs"><?php echo e($app->created_at->diffForHumans()); ?></p>
            </div>
            <a href="<?php echo e(route('admin.pengajuan-eo')); ?>" class="text-xs bg-navy-mid text-white px-2 py-1 rounded-lg hover:bg-navy-deep transition-all flex-shrink-0">Review</a>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>

  </div>

  
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h3 class="font-bold text-navy-deep text-sm mb-5">Metode Pembayaran</h3>
      <?php if($pieMetode->isEmpty()): ?>
        <p class="text-center text-gray-400 text-xs py-8">Belum ada data</p>
      <?php else: ?>
        <div class="flex justify-center mb-4">
          <canvas id="chartMetode" width="180" height="180"></canvas>
        </div>
        <div class="space-y-2 border-t border-gray-100 pt-3">
          <?php $__currentLoopData = $pieMetode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metode => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 uppercase font-medium"><?php echo e($metode); ?></span>
            <span class="font-bold text-navy-mid"><?php echo e($total); ?> transaksi</span>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>

    
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h3 class="font-bold text-navy-deep text-sm mb-5">Status Pesanan</h3>
      <?php if($pieStatus->isEmpty()): ?>
        <p class="text-center text-gray-400 text-xs py-8">Belum ada data</p>
      <?php else: ?>
        <div class="flex justify-center mb-4">
          <canvas id="chartStatus" width="180" height="180"></canvas>
        </div>
        <div class="space-y-2 border-t border-gray-100 pt-3">
          <?php $__currentLoopData = $pieStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 capitalize font-medium"><?php echo e($status); ?></span>
            <span class="font-bold text-navy-mid"><?php echo e($total); ?> pesanan</span>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>

    
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h3 class="font-bold text-navy-deep text-sm mb-5">Kategori Event</h3>
      <?php if($pieKategori->isEmpty()): ?>
        <p class="text-center text-gray-400 text-xs py-8">Belum ada data</p>
      <?php else: ?>
        <div class="flex justify-center mb-4">
          <canvas id="chartKategori" width="180" height="180"></canvas>
        </div>
        <div class="space-y-2 border-t border-gray-100 pt-3">
          <?php $__currentLoopData = $pieKategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat => $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 font-medium"><?php echo e($kat); ?></span>
            <span class="font-bold text-navy-mid"><?php echo e($total); ?> event</span>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>

  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h2 class="font-bold text-navy-deep mb-5">EO Terbaik</h2>
      <?php if($eoBest->isEmpty()): ?>
        <div class="text-center py-8 text-gray-400 text-sm">Belum ada data</div>
      <?php else: ?>
        <?php $maxPend2 = $eoBest->max('total_pendapatan') ?: 1; ?>
        <div class="space-y-4">
          <?php $__currentLoopData = $eoBest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $eo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="flex items-center gap-3">
            <span class="w-6 h-6 flex items-center justify-center text-sm font-extrabold <?php echo e($i===0?'text-gold':($i===1?'text-gray-400':($i===2?'text-amber-600':'text-gray-300'))); ?>">
              <?php echo e($i===0?'1':($i===1?'2':($i===2?'3':$i+1))); ?>

            </span>
            <img src="<?php echo e($eo->avatar_url); ?>" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <div class="flex justify-between items-center mb-1">
                <p class="font-semibold text-navy-deep text-xs truncate"><?php echo e($eo->nama_panggilan); ?></p>
                <p class="text-xs font-bold text-navy-mid ml-2 flex-shrink-0">Rp <?php echo e(number_format($eo->total_pendapatan/1000000,1)); ?>jt</p>
              </div>
              <div class="progress-bar">
                <div class="progress-fill" style="width:<?php echo e(($eo->total_pendapatan/$maxPend2)*100); ?>%"></div>
              </div>
              <p class="text-gray-400 text-xs mt-0.5"><?php echo e($eo->events_count); ?> event</p>
            </div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>

    
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
      <h2 class="font-bold text-navy-deep mb-5">Event Terpopuler</h2>
      <?php if($eventPopuler->isEmpty()): ?>
        <div class="text-center py-8 text-gray-400 text-sm">Belum ada data</div>
      <?php else: ?>
        <div class="space-y-3">
          <?php $__currentLoopData = $eventPopuler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="flex items-center gap-3">
            <span class="w-6 text-center text-xs font-bold text-gray-400"><?php echo e($i+1); ?></span>
            <img src="<?php echo e($ev->cover_url); ?>" class="w-10 h-10 rounded-xl object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <p class="font-semibold text-navy-deep text-xs truncate"><?php echo e($ev->judul); ?></p>
              <p class="text-gray-400 text-xs"><?php echo e($ev->lokasi_kota); ?> · <?php echo e($ev->orders_count); ?> pesanan</p>
            </div>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full flex-shrink-0 <?php echo e($ev->status==='published'?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500'); ?>">
              <?php echo e($ev->status==='published'?'Live':'Draft'); ?>

            </span>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  
  <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-5">
      <h2 class="font-bold text-navy-deep">Pesanan Terbaru</h2>
      <a href="<?php echo e(route('admin.pesanan')); ?>" class="text-xs text-navy-mid font-semibold hover:text-gold">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="border-b border-gray-100">
          <tr>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Pembeli</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Event</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Total</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3 pr-4">Waktu</th>
            <th class="text-left text-xs font-bold text-gray-400 uppercase pb-3">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          <?php $__currentLoopData = $pesananTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr class="hover:bg-gray-50">
            <td class="py-3 pr-4">
              <div class="flex items-center gap-2">
                <img src="<?php echo e($order->user->avatar_url); ?>" class="w-7 h-7 rounded-full object-cover">
                <span class="text-xs font-medium text-navy-deep"><?php echo e($order->user->nama_panggilan); ?></span>
              </div>
            </td>
            <td class="py-3 pr-4"><p class="text-xs text-gray-600 max-w-[160px] truncate"><?php echo e($order->event->judul); ?></p></td>
            <td class="py-3 pr-4"><p class="text-xs font-bold text-navy-deep">Rp <?php echo e(number_format($order->total_harga,0,',','.')); ?></p></td>
            <td class="py-3 pr-4"><p class="text-xs text-gray-400"><?php echo e($order->created_at->diffForHumans()); ?></p></td>
            <td class="py-3">
              <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?php echo e($order->status==='paid'?'badge-paid':($order->status==='pending'?'badge-pending':'badge-cancelled')); ?>">
                <?php echo e($order->status==='paid'?'Lunas':($order->status==='pending'?'Pending':'Batal')); ?>

              </span>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
const COLORS = ['#102A71','#F5C400','#3B82F6','#10B981','#EF4444','#8B5CF6','#F59E0B','#06B6D4','#EC4899'];

function makePie(id, labels, data) {
  const ctx = document.getElementById(id);
  if (!ctx) return;
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        backgroundColor: COLORS.slice(0, data.length),
        borderWidth: 2,
        borderColor: '#ffffff',
        hoverOffset: 6,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: ctx => ` ${ctx.label}: ${ctx.raw} (${Math.round(ctx.raw/ctx.dataset.data.reduce((a,b)=>a+b,0)*100)}%)`
          }
        }
      },
      cutout: '60%',
    }
  });
}

<?php if(!$pieMetode->isEmpty()): ?>
makePie('chartMetode',
  <?php echo json_encode($pieMetode->keys()->map(fn($k) => strtoupper($k))->values()); ?>,
  <?php echo json_encode($pieMetode->values()); ?>

);
<?php endif; ?>

<?php if(!$pieStatus->isEmpty()): ?>
makePie('chartStatus',
  <?php echo json_encode($pieStatus->keys()->map(fn($k) => ucfirst($k))->values()); ?>,
  <?php echo json_encode($pieStatus->values()); ?>

);
<?php endif; ?>

<?php if(!$pieKategori->isEmpty()): ?>
makePie('chartKategori',
  <?php echo json_encode($pieKategori->keys()->values()); ?>,
  <?php echo json_encode($pieKategori->values()); ?>

);
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>