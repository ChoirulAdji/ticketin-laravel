<?php $__env->startSection('title', 'Edit Profil — TicketIn'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .form-input { border:1.5px solid #e5e7eb; border-radius:12px; outline:none; transition:all .2s; color:#1e293b; background:white; width:100%; }
  .form-input:focus { border-color:#F5C400; box-shadow:0 0 0 3px rgba(245,196,0,.15); }
  .form-label { display:block; font-size:.75rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pt-24 max-w-2xl mx-auto px-6 py-10">
  <div class="flex items-center gap-3 mb-8">
    <a href="<?php echo e(route('profile.index')); ?>" class="text-gray-400 hover:text-navy-mid transition-colors">
      <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="text-2xl font-extrabold text-navy-deep">Edit Profil</h1>
  </div>

  

  <!-- Form Profil -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h2 class="font-bold text-navy-deep text-lg mb-5"> Informasi Akun</h2>
    <?php if($errors->hasAny(['nama_lengkap','email','foto_profil'])): ?>
      <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl mb-5 text-sm">
        <?php $__currentLoopData = ['nama_lengkap','email','foto_profil']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($errors->has($f)): ?><p><?php echo e($errors->first($f)); ?></p><?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data" class="space-y-5">
      <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
      <div class="flex items-center gap-5">
        <img id="avatarPreview" src="<?php echo e($user->avatar_url); ?>" class="w-20 h-20 rounded-2xl object-cover border-2 border-gray-200">
        <div class="flex-1">
          <label class="form-label">Foto Profil</label>
          <input type="file" name="foto_profil" accept="image/*" class="form-input px-4 py-2.5 text-sm text-gray-500" onchange="previewAvatar(this)">
          <p class="text-gray-400 text-xs mt-1">JPG, PNG, WebP. Maks 2MB.</p>
        </div>
      </div>
      <div>
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" value="<?php echo e(old('nama_lengkap',$user->nama_lengkap)); ?>" class="form-input px-4 py-3 text-sm" required>
      </div>
      <div>
        <label class="form-label">Email</label>
        <input type="email" name="email" value="<?php echo e(old('email',$user->email)); ?>" class="form-input px-4 py-3 text-sm" required>
      </div>
      <div>
        <label class="form-label">No. HP</label>
        <div class="flex">
          <span class="inline-flex items-center px-3 bg-gray-50 border border-r-0 border-gray-200 rounded-l-xl text-gray-500 text-sm">+62</span>
          <input type="tel" name="no_hp" value="<?php echo e(old('no_hp',$user->no_hp)); ?>" placeholder="812xxxxxxxx" class="form-input flex-1 px-4 py-3 text-sm" style="border-radius:0 12px 12px 0">
        </div>
      </div>
      <div>
        <label class="form-label">Role Akun</label>
        <div class="form-input px-4 py-3 text-sm text-gray-400 cursor-not-allowed bg-gray-50">
          <?php echo e($user->role==='pengelola' ? ' Pengelola Event (EO)' : ($user->role==='admin' ? ' Admin' : ' Pembeli Tiket')); ?>

        </div>
      </div>
      <button type="submit" class="w-full bg-gold text-navy-deep font-bold py-3.5 rounded-xl hover:bg-gold-light transition-all text-sm">
         Simpan Perubahan
      </button>
    </form>
  </div>

  <!-- Ganti Password -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="font-bold text-navy-deep text-lg mb-5"> Ganti Password</h2>
    <?php if($errors->hasAny(['current_password','password'])): ?>
      <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-xl mb-5 text-sm">
        <?php $__currentLoopData = ['current_password','password']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($errors->has($f)): ?><p><?php echo e($errors->first($f)); ?></p><?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(route('profile.password')); ?>" class="space-y-5">
      <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
      <div>
        <label class="form-label">Password Saat Ini</label>
        <input type="password" name="current_password" placeholder="Masukkan password lama" class="form-input px-4 py-3 text-sm" required>
      </div>
      <div>
        <label class="form-label">Password Baru</label>
        <input type="password" name="password" placeholder="Minimal 8 karakter" class="form-input px-4 py-3 text-sm" required>
      </div>
      <div>
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="form-input px-4 py-3 text-sm" required>
      </div>
      <button type="submit" class="w-full bg-navy-mid text-white font-bold py-3.5 rounded-xl hover:bg-navy-deep transition-all text-sm">
         Ubah Password
      </button>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  function previewAvatar(input) {
    if(input.files&&input.files[0]){
      const reader=new FileReader();
      reader.onload=e=>document.getElementById('avatarPreview').src=e.target.result;
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/profile/edit.blade.php ENDPATH**/ ?>