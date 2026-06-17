<?php $__env->startSection('title','Manajemen User'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .badge-active { background:rgba(34,197,94,.15); color:#16a34a; border:1px solid rgba(34,197,94,.3); }
  .badge-suspended { background:rgba(239,68,68,.15); color:#dc2626; border:1px solid rgba(239,68,68,.3); }
  .modal-overlay { position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:100;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .3s; }
  .modal-overlay.show { opacity:1;pointer-events:all; }
  .modal-box { background:white;border-radius:20px;width:100%;max-width:380px;transform:scale(.95);transition:transform .3s; }
  .modal-overlay.show .modal-box { transform:scale(1); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-7xl mx-auto">

  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-extrabold text-navy-deep">Manajemen User</h1>
      <p class="text-gray-400 text-sm mt-0.5">Total <?php echo e($users->total()); ?> user terdaftar</p>
    </div>
    <form method="GET" class="flex gap-2">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama / email..."
             class="border border-gray-200 rounded-xl px-4 py-2 text-sm outline-none focus:border-navy-mid w-56">
      <select name="role" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-navy-mid bg-white">
        <option value="">Semua Role</option>
        <option value="user"      <?php echo e(request('role')==='user'?'selected':''); ?>>Pembeli</option>
        <option value="pengelola" <?php echo e(request('role')==='pengelola'?'selected':''); ?>>Pengelola EO</option>
        <option value="admin"     <?php echo e(request('role')==='admin'?'selected':''); ?>>Admin</option>
      </select>
      <button type="submit" class="bg-navy-mid text-white font-bold px-4 py-2 rounded-xl text-sm hover:bg-navy-deep transition-all">Cari</button>
    </form>
  </div>

  <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 border-b border-gray-100">
        <tr>
          <th class="text-left text-xs font-bold text-gray-400 uppercase px-6 py-3">User</th>
          <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Role</th>
          <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Status</th>
          <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Bergabung</th>
          <th class="text-left text-xs font-bold text-gray-400 uppercase px-4 py-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="hover:bg-gray-50 transition-colors">
          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <img src="<?php echo e($user->avatar_url); ?>" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
              <div>
                <p class="font-semibold text-navy-deep text-sm"><?php echo e($user->nama_lengkap); ?></p>
                <p class="text-gray-400 text-xs"><?php echo e($user->email); ?></p>
                <?php if($user->no_hp): ?><p class="text-gray-300 text-xs">+62<?php echo e($user->no_hp); ?></p><?php endif; ?>
              </div>
            </div>
          </td>
          <td class="px-4 py-4">
            <span class="text-xs font-semibold px-2 py-1 rounded-full
              <?php echo e($user->role==='admin'?'bg-red-100 text-red-700':($user->role==='pengelola'?'bg-purple-100 text-purple-700':'bg-blue-100 text-blue-700')); ?>">
              <?php echo e($user->role==='admin'?' Admin':($user->role==='pengelola'?' EO':' Pembeli')); ?>

            </span>
          </td>
          <td class="px-4 py-4">
            <span class="text-xs font-semibold px-2 py-1 rounded-full <?php echo e($user->status_akun==='active'?'badge-active':'badge-suspended'); ?>">
              <?php echo e($user->status_akun==='active'?' Aktif':' Suspend'); ?>

            </span>
          </td>
          <td class="px-4 py-4">
            <p class="text-xs text-gray-500"><?php echo e($user->created_at->format('d M Y')); ?></p>
          </td>
          <td class="px-4 py-4">
            <div class="flex items-center gap-2">
              
              <form method="POST" action="<?php echo e(route('admin.users.toggle', $user)); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border transition-all
                  <?php echo e($user->status_akun==='active' ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-green-200 text-green-600 hover:bg-green-50'); ?>">
                  <?php echo e($user->status_akun==='active' ? 'Suspend' : 'Aktifkan'); ?>

                </button>
              </form>

              
              <button onclick="bukaModalRole(<?php echo e($user->id); ?>, '<?php echo e($user->nama_lengkap); ?>', '<?php echo e($user->role); ?>')"
                      class="text-xs px-3 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                Ubah Role
              </button>

              
              <form method="POST" action="<?php echo e(route('admin.users.hapus', $user)); ?>"
                    onsubmit="return confirm('Hapus akun <?php echo e($user->nama_lengkap); ?>? Data tidak bisa dikembalikan.')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="text-xs px-3 py-1.5 rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition-all">
                  Hapus
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100"><?php echo e($users->links()); ?></div>
  </div>
</div>


<div class="modal-overlay" id="role-modal" onclick="if(event.target===this)this.classList.remove('show')">
  <div class="modal-box p-6">
    <h3 class="font-extrabold text-navy-deep mb-1">Ubah Role User</h3>
    <p class="text-gray-500 text-sm mb-5">User: <strong id="modal-nama"></strong></p>
    <form method="POST" id="role-form">
      <?php echo csrf_field(); ?>
      <div class="space-y-2 mb-5">
        <?php $__currentLoopData = ['user'=>[' Pembeli','Bisa beli tiket event'],'pengelola'=>[' Pengelola EO','Bisa buat dan kelola event'],'admin'=>[' Admin','Akses penuh ke seluruh platform']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role=>[$label,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <label class="flex items-center gap-3 p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-navy-mid/30 transition-all has-[:checked]:border-navy-mid has-[:checked]:bg-navy-mid/5">
          <input type="radio" name="role" value="<?php echo e($role); ?>" class="accent-navy-mid">
          <div>
            <p class="font-semibold text-navy-deep text-sm"><?php echo e($label); ?></p>
            <p class="text-gray-400 text-xs"><?php echo e($desc); ?></p>
          </div>
        </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <div class="flex gap-3">
        <button type="button" onclick="document.getElementById('role-modal').classList.remove('show')"
                class="flex-1 bg-gray-100 text-gray-700 font-bold py-3 rounded-xl text-sm hover:bg-gray-200 transition-all">Batal</button>
        <button type="submit" class="flex-1 bg-navy-mid text-white font-bold py-3 rounded-xl text-sm hover:bg-navy-deep transition-all">Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  function bukaModalRole(id, nama, currentRole) {
    document.getElementById('modal-nama').textContent = nama;
    document.getElementById('role-form').action = '/admin/users/' + id + '/ubah-role';
    document.querySelectorAll('#role-form input[name="role"]').forEach(r => {
      r.checked = r.value === currentRole;
    });
    document.getElementById('role-modal').classList.add('show');
  }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/admin/users.blade.php ENDPATH**/ ?>