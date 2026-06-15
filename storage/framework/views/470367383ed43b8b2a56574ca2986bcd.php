<?php $__env->startSection('title','Verifikasi Pengajuan EO'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .badge-pending  { background:rgba(234,179,8,.15); color:#b45309; border:1px solid rgba(234,179,8,.3); }
  .badge-approved { background:rgba(34,197,94,.15); color:#16a34a; border:1px solid rgba(34,197,94,.3); }
  .badge-rejected { background:rgba(239,68,68,.15); color:#dc2626; border:1px solid rgba(239,68,68,.3); }
  .modal-overlay { position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:100;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .3s; }
  .modal-overlay.show { opacity:1;pointer-events:all; }
  .modal-box { background:white;border-radius:20px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;transform:scale(.95);transition:transform .3s; }
  .modal-overlay.show .modal-box { transform:scale(1); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-7xl mx-auto">

  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-extrabold text-navy-deep">Verifikasi Pengajuan EO</h1>
      <p class="text-gray-400 text-sm mt-0.5">Total <?php echo e($pengajuan->total()); ?> pengajuan</p>
    </div>
    <form method="GET">
      <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none bg-white">
        <option value="">Semua Status</option>
        <option value="pending"  <?php echo e(request('status')==='pending'?'selected':''); ?>> Pending</option>
        <option value="approved" <?php echo e(request('status')==='approved'?'selected':''); ?>> Disetujui</option>
        <option value="rejected" <?php echo e(request('status')==='rejected'?'selected':''); ?>> Ditolak</option>
      </select>
    </form>
  </div>

  <?php if($pengajuan->isEmpty()): ?>
  <div class="bg-white border border-gray-100 rounded-2xl p-16 text-center shadow-sm">
    <div class="text-5xl mb-3"></div>
    <p class="text-gray-500">Tidak ada pengajuan ditemukan</p>
  </div>
  <?php else: ?>
  <div class="space-y-4">
    <?php $__currentLoopData = $pengajuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:border-navy-mid/20 transition-colors">
      <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">

        
        <div class="flex items-center gap-3 flex-1 min-w-0">
          <img src="<?php echo e($app->user->avatar_url); ?>" class="w-12 h-12 rounded-full object-cover flex-shrink-0">
          <div class="min-w-0">
            <p class="font-bold text-navy-deep"><?php echo e($app->user->nama_lengkap); ?></p>
            <p class="text-gray-400 text-sm"><?php echo e($app->user->email); ?></p>
            <p class="text-gray-400 text-xs mt-0.5">Dikirim <?php echo e($app->created_at->translatedFormat('d M Y, H:i')); ?></p>
          </div>
        </div>

        
        <div class="flex-1 min-w-0">
          <p class="font-bold text-navy-mid"><?php echo e($app->nama_organisasi); ?></p>
          <p class="text-gray-500 text-sm"><?php echo e(ucfirst($app->jenis_entitas)); ?> · <?php echo e(ucfirst($app->skala_event)); ?></p>
          <p class="text-gray-400 text-xs truncate"><?php echo e($app->alamat_organisasi); ?></p>
        </div>

        
        <div class="flex-shrink-0 text-right">
          <span class="text-xs font-semibold px-3 py-1 rounded-full inline-block mb-2
            <?php echo e($app->status==='pending'?'badge-pending':($app->status==='approved'?'badge-approved':'badge-rejected')); ?>">
            <?php echo e($app->status==='pending'?' Pending':($app->status==='approved'?' Disetujui':' Ditolak')); ?>

          </span>
          <?php if($app->reviewed_at): ?>
            <p class="text-gray-400 text-xs"><?php echo e($app->reviewed_at->format('d M Y')); ?></p>
          <?php endif; ?>
        </div>

        
        <div class="flex gap-2 flex-shrink-0">
          <button onclick="lihatDetail(<?php echo e($app->id); ?>)"
                  class="text-sm bg-navy-mid text-white font-semibold px-4 py-2 rounded-xl hover:bg-navy-deep transition-all">
            Lihat Detail
          </button>
          <?php if($app->status === 'pending'): ?>
          <form method="POST" action="<?php echo e(route('admin.pengajuan-eo.approve', $app)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-sm bg-green-500 text-white font-semibold px-4 py-2 rounded-xl hover:bg-green-600 transition-all">
               Setujui
            </button>
          </form>
          <button onclick="bukaReject(<?php echo e($app->id); ?>)"
                  class="text-sm bg-red-50 text-red-600 font-semibold px-4 py-2 rounded-xl hover:bg-red-100 transition-all border border-red-200">
             Tolak
          </button>
          <?php endif; ?>
        </div>
      </div>
    </div>

    
    <div id="app-data-<?php echo e($app->id); ?>" class="hidden"
         data-nama="<?php echo e($app->user->nama_lengkap); ?>"
         data-email="<?php echo e($app->user->email); ?>"
         data-org="<?php echo e($app->nama_organisasi); ?>"
         data-jenis="<?php echo e(ucfirst($app->jenis_entitas)); ?>"
         data-skala="<?php echo e(ucfirst($app->skala_event)); ?>"
         data-alamat="<?php echo e($app->alamat_organisasi); ?>"
         data-hp="<?php echo e($app->no_hp_bisnis); ?>"
         data-website="<?php echo e($app->website ?? '-'); ?>"
         data-npwp="<?php echo e($app->npwp ?? '-'); ?>"
         data-bank="<?php echo e(strtoupper($app->bank)); ?>"
         data-rek="<?php echo e($app->nomor_rekening); ?>"
         data-nama-rek="<?php echo e($app->nama_rekening); ?>"
         data-dokumen="<?php echo e($app->dokumen_url ?? ''); ?>"
         data-status="<?php echo e($app->status); ?>"
         data-avatar="<?php echo e($app->user->avatar_url); ?>">
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
  <div class="mt-6"><?php echo e($pengajuan->links()); ?></div>
  <?php endif; ?>
</div>


<div class="modal-overlay" id="detail-modal" onclick="if(event.target===this)this.classList.remove('show')">
  <div class="modal-box">
    <div class="flex items-center justify-between p-5 border-b border-gray-100">
      <h3 class="font-extrabold text-navy-deep">Detail Pengajuan EO</h3>
      <button onclick="document.getElementById('detail-modal').classList.remove('show')"
              class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="p-5">
      <div class="flex items-center gap-4 mb-5">
        <img id="d-avatar" src="" class="w-14 h-14 rounded-2xl object-cover">
        <div>
          <p id="d-nama" class="font-extrabold text-navy-deep text-lg"></p>
          <p id="d-email" class="text-gray-500 text-sm"></p>
        </div>
      </div>

      <div class="space-y-3 text-sm">
        <?php $__currentLoopData = [
          ['d-org','Nama Organisasi'],['d-jenis','Jenis Entitas'],['d-skala','Skala Event'],
          ['d-alamat','Alamat'],['d-hp','No. HP Bisnis'],['d-website','Website'],
          ['d-npwp','NPWP'],['d-bank','Bank'],['d-rek','No. Rekening'],['d-nama-rek','Nama Rekening'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$id,$label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex justify-between gap-4 py-2 border-b border-gray-50">
          <span class="text-gray-500 flex-shrink-0"><?php echo e($label); ?></span>
          <span id="<?php echo e($id); ?>" class="font-semibold text-navy-deep text-right"></span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div id="dokumen-wrap" class="py-2">
          <span class="text-gray-500">Dokumen</span>
          <a id="d-dokumen" href="" target="_blank" class="block mt-1 text-navy-mid font-semibold hover:underline text-xs">Lihat Dokumen →</a>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal-overlay" id="reject-modal" onclick="if(event.target===this)this.classList.remove('show')">
  <div class="modal-box p-6" style="max-width:420px">
    <h3 class="font-extrabold text-navy-deep mb-2">Tolak Pengajuan EO</h3>
    <p class="text-gray-500 text-sm mb-4">Berikan alasan penolakan agar pelamar bisa memperbaiki pengajuannya.</p>
    <form id="reject-form" method="POST">
      <?php echo csrf_field(); ?>
      <textarea name="catatan_admin" rows="4" placeholder="Contoh: Dokumen KTP tidak jelas, mohon upload ulang dengan kualitas lebih baik."
                class="w-full border border-gray-200 rounded-xl p-3 text-sm outline-none focus:border-red-400 resize-none mb-4"></textarea>
      <div class="flex gap-3">
        <button type="button" onclick="document.getElementById('reject-modal').classList.remove('show')"
                class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 rounded-xl text-sm">Batal</button>
        <button type="submit" class="flex-1 bg-red-500 text-white font-bold py-3 rounded-xl text-sm hover:bg-red-600 transition-all">Tolak Pengajuan</button>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  function lihatDetail(id) {
    const d = document.getElementById('app-data-'+id).dataset;
    document.getElementById('d-avatar').src     = d.avatar;
    document.getElementById('d-nama').textContent    = d.nama;
    document.getElementById('d-email').textContent   = d.email;
    document.getElementById('d-org').textContent     = d.org;
    document.getElementById('d-jenis').textContent   = d.jenis;
    document.getElementById('d-skala').textContent   = d.skala;
    document.getElementById('d-alamat').textContent  = d.alamat;
    document.getElementById('d-hp').textContent      = d.hp;
    document.getElementById('d-website').textContent = d.website;
    document.getElementById('d-npwp').textContent    = d.npwp;
    document.getElementById('d-bank').textContent    = d.bank;
    document.getElementById('d-rek').textContent     = d.rek;
    document.getElementById('d-nama-rek').textContent = d.namaRek;
    const dok = document.getElementById('d-dokumen');
    if (d.dokumen) { dok.href = d.dokumen; dok.parentElement.style.display='block'; }
    else { dok.parentElement.style.display='none'; }
    document.getElementById('detail-modal').classList.add('show');
  }

  function bukaReject(id) {
    document.getElementById('reject-form').action = '/admin/pengajuan-eo/'+id+'/reject';
    document.getElementById('reject-modal').classList.add('show');
  }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/admin/pengajuan-eo.blade.php ENDPATH**/ ?>