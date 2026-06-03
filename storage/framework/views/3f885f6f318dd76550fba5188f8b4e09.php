<?php $__env->startSection('title', 'Checkout — ' . $event->judul); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .form-input { border:1.5px solid #e5e7eb; border-radius:12px; outline:none; transition:all .2s; color:#1e293b; background:white; width:100%; }
  .form-input:focus { border-color:#F5C400; box-shadow:0 0 0 3px rgba(245,196,0,.15); }
  .form-input[readonly] { background:#f8fafc; opacity:.8; cursor:not-allowed; }
  .step-indicator { display:flex; align-items:center; gap:0; margin-bottom:32px; }
  .step-circle { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; flex-shrink:0; }
  .step-circle.active { background:#102A71; color:white; }
  .step-circle.done { background:#F5C400; color:#001840; }
  .step-circle.pending { background:#f1f5f9; color:#94a3b8; }
  .step-line { flex:1; height:2px; background:#e5e7eb; }
  .step-line.done { background:#F5C400; }
  .step-panel { display:none; }
  .step-panel.active { display:block; }
  .pay-method { border:2px solid #e5e7eb; border-radius:12px; cursor:pointer; transition:all .2s; }
  .pay-method.selected { border-color:#F5C400; background:#fffbeb; }
  .summary-card { background:linear-gradient(135deg,#102A71 0%,#001840 100%); border-radius:20px; }
  .buy-bar { position:fixed; bottom:0; left:0; right:0; background:white; border-top:1px solid #e5e7eb; padding:12px 24px; z-index:40; box-shadow:0 -4px 20px rgba(0,0,0,.08); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pt-24 max-w-7xl mx-auto px-6 py-10 pb-28 lg:pb-10">
  <h1 class="text-2xl font-extrabold text-navy-deep mb-8">Checkout</h1>

  <form id="checkoutForm" method="POST" action="<?php echo e(route('checkout.proses', $event)); ?>">
    <?php echo csrf_field(); ?>
    <div class="flex flex-col lg:flex-row gap-8">

      <!-- LEFT -->
      <div class="flex-1 min-w-0">

        <!-- Step Indicator -->
        <div class="step-indicator">
          <div class="step-circle active" id="sc1">1</div>
          <div class="step-line" id="sl1"></div>
          <div class="step-circle pending" id="sc2">2</div>
        </div>

        <!-- STEP 1: Data Diri -->
        <div class="step-panel active" id="panel1">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-navy-deep text-base mb-5 flex items-center gap-2">
              <span class="w-7 h-7 bg-navy-mid rounded-lg flex items-center justify-center text-white text-xs font-bold">1</span>
              Data Pemesan Utama
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                <input type="text" name="nama" value="<?php echo e(auth()->user()->nama_lengkap); ?>" placeholder="Nama sesuai KTP" class="form-input px-4 py-2.5 text-sm"/>
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email *</label>
                <input type="email" value="<?php echo e(auth()->user()->email); ?>" readonly class="form-input px-4 py-2.5 text-sm"/>
                <input type="hidden" name="email" value="<?php echo e(auth()->user()->email); ?>">
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">No. HP *</label>
                <div class="flex">
                  <span class="inline-flex items-center px-3 bg-gray-50 border border-r-0 border-gray-200 rounded-l-xl text-gray-500 text-sm font-medium">+62</span>
                  <input type="tel" name="no_hp" value="<?php echo e(ltrim(auth()->user()->no_hp ?? '', '0')); ?>" placeholder="812xxxxxxxx" class="form-input flex-1 px-4 py-2.5 text-sm rounded-l-none" style="border-radius:0 12px 12px 0"/>
                </div>
              </div>
              <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kota Asal</label>
                <input type="text" name="kota_asal" placeholder="Surabaya" class="form-input px-4 py-2.5 text-sm"/>
              </div>
            </div>
            <div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-700 flex gap-3 items-start">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <p>E-Ticket akan dikirimkan ke alamat email di atas. Pastikan email aktif.</p>
            </div>
            <div class="mt-4">
              <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Catatan (opsional)</label>
              <textarea name="catatan" rows="2" placeholder="Kebutuhan khusus, dsb..." class="form-input px-4 py-2.5 text-sm resize-none"></textarea>
            </div>
          </div>
          <button type="button" onclick="goStep(2)" class="mt-6 w-full bg-navy-mid text-white font-bold py-3.5 rounded-xl hover:bg-navy-deep transition-colors text-sm shadow-md">
            Pilih Metode Pembayaran →
          </button>
        </div>

        <!-- STEP 2: Pembayaran -->
        <div class="step-panel" id="panel2">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
            <h2 class="font-bold text-navy-deep text-base mb-5 flex items-center gap-2">
              <span class="w-7 h-7 bg-navy-mid rounded-lg flex items-center justify-center text-white text-xs font-bold">2</span>
              Metode Pembayaran
            </h2>
            <input type="hidden" name="metode_bayar" id="selectedPayInput" value="BCA">

            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Transfer Bank (Virtual Account)</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
              <?php $__currentLoopData = [['BCA','bg-blue-600','BCA'],['Mandiri','bg-yellow-500','M'],['BNI','bg-orange-600','BNI']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$nama,$bg,$label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="pay-method <?php echo e($loop->first ? 'selected':''); ?> p-4 flex items-center gap-3" onclick="selectPay(this,'<?php echo e($nama); ?>')">
                <div class="w-10 h-10 <?php echo e($bg); ?> rounded-lg flex items-center justify-center text-white font-extrabold text-xs"><?php echo e($label); ?></div>
                <div><p class="font-bold text-navy-deep text-xs"><?php echo e($nama); ?></p><p class="text-[11px] text-gray-400 mt-0.5">Virtual Account</p></div>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">E-Wallet / QRIS</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
              <?php $__currentLoopData = [['OVO','bg-purple-600','OVO','Potong Saldo'],['GoPay','bg-green-500','GP','Aplikasi Gojek'],['QRIS','bg-navy-mid','QR','Scan Semua Bank']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$nama,$bg,$label,$desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="pay-method p-4 flex items-center gap-3" onclick="selectPay(this,'<?php echo e($nama); ?>')">
                <div class="w-10 h-10 <?php echo e($bg); ?> rounded-lg flex items-center justify-center text-white font-bold text-xs"><?php echo e($label); ?></div>
                <div><p class="font-bold text-navy-deep text-xs"><?php echo e($nama); ?></p><p class="text-[11px] text-gray-400 mt-0.5"><?php echo e($desc); ?></p></div>
              </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="bg-navy-mid/5 border border-navy-mid/10 rounded-xl p-4 text-sm">
              <p class="font-bold text-navy-deep text-xs uppercase tracking-wider mb-2">Instruksi Pembayaran</p>
              <p class="text-gray-600 text-sm leading-relaxed" id="pay-instruction">
                Setelah klik "Bayar Sekarang", Anda akan mendapatkan Nomor Virtual Account <strong>BCA</strong> untuk melakukan pembayaran.
              </p>
            </div>
          </div>

          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <label class="flex items-start gap-3 cursor-pointer select-none">
              <input type="checkbox" id="agree" class="mt-1 w-4 h-4 accent-yellow-400"/>
              <span class="text-sm text-gray-600 leading-relaxed">
                Saya telah memastikan data diri sudah benar, dan saya setuju dengan <a href="#" class="text-navy-mid font-semibold hover:underline">Syarat & Ketentuan</a> serta kebijakan refund TicketIn.
              </span>
            </label>
          </div>

          <div class="flex gap-3 mt-5">
            <button type="button" onclick="goStep(1)" class="w-1/3 border border-gray-200 text-navy-deep font-bold py-3.5 rounded-xl hover:bg-gray-50 transition-colors text-sm">Kembali</button>
            <button type="button" onclick="submitOrder()" class="w-2/3 bg-gold text-navy-deep font-bold py-3.5 rounded-xl hover:bg-gold-light transition-all text-sm hover:shadow-lg hover:shadow-gold/30">
              Bayar Sekarang
            </button>
          </div>
        </div>
      </div>

      <!-- RIGHT: Summary -->
      <aside class="w-full lg:w-[380px] flex-shrink-0">
        <div class="summary-card p-6 text-white lg:sticky lg:top-24 shadow-xl">
          <p class="text-xs font-bold uppercase tracking-widest text-gold mb-4">Ringkasan Pesanan</p>
          <img src="<?php echo e($event->cover_url); ?>" class="w-full h-32 object-cover rounded-xl mb-4 opacity-90"/>
          <h3 class="font-extrabold text-lg leading-tight mb-1 line-clamp-2"><?php echo e($event->judul); ?></h3>
          <p class="text-white/70 text-xs flex items-center gap-1.5 mb-1">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <?php echo e($event->tanggal_waktu->format('d M Y')); ?> · <?php echo e($event->tanggal_waktu->format('H:i')); ?> WIB
          </p>
          <p class="text-white/70 text-xs flex items-center gap-1.5 mb-4">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            <?php echo e($event->lokasi_kota); ?>

          </p>
          <hr class="border-white/15 my-4"/>
          <div class="space-y-3 text-sm">
            <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex justify-between text-white/80">
              <span><?php echo e($item['qty']); ?>× <?php echo e($item['category']->nama_kategori); ?></span>
              <span>Rp <?php echo e(number_format($item['line_total'],0,',','.')); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="flex justify-between text-white/80">
              <span>Biaya Layanan</span>
              <span>Rp <?php echo e(number_format($biayaLayanan,0,',','.')); ?></span>
            </div>
            <div class="flex justify-between items-end font-extrabold border-t border-white/15 pt-4">
              <span>Total Bayar</span>
              <span class="text-gold text-xl">Rp <?php echo e(number_format($total,0,',','.')); ?></span>
            </div>
          </div>
          <div class="mt-6 bg-white/10 border border-white/5 rounded-xl p-3 text-xs text-white/60 flex items-center gap-2">
            <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            Sistem pembayaran dienkripsi SSL 256-bit aman
          </div>
        </div>
      </aside>
    </div>
  </form>
</div>

<div class="buy-bar lg:hidden">
  <div class="flex items-center justify-between gap-4">
    <div>
      <p class="text-xs text-gray-500 mb-0.5">Total Bayar</p>
      <p class="text-lg font-extrabold text-navy-deep">Rp <?php echo e(number_format($total,0,',','.')); ?></p>
    </div>
    <button type="button" onclick="submitOrder()" class="flex-1 bg-gold text-navy-deep text-center font-bold py-3 rounded-xl text-sm">
      Bayar Sekarang
    </button>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  function goStep(step) {
    document.getElementById('panel1').classList.toggle('active', step===1);
    document.getElementById('panel2').classList.toggle('active', step===2);
    const sc1=document.getElementById('sc1'), sc2=document.getElementById('sc2'), sl1=document.getElementById('sl1');
    if(step===2){ sc1.className='step-circle done'; sl1.classList.add('done'); sc2.className='step-circle active'; }
    else { sc1.className='step-circle active'; sl1.classList.remove('done'); sc2.className='step-circle pending'; }
  }
  function selectPay(el, name) {
    document.querySelectorAll('.pay-method').forEach(e=>e.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedPayInput').value=name;
    document.getElementById('pay-instruction').innerHTML=`Setelah klik "Bayar Sekarang", Anda akan mendapatkan instruksi pembayaran <strong>${name}</strong>.`;
  }
  function submitOrder() {
    if(!document.getElementById('agree').checked){ alert('Anda harus menyetujui syarat & ketentuan.'); return; }
    document.getElementById('checkoutForm').submit();
  }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Application\Software\XAMPP\htdocs\ticketin-laravel\resources\views/checkout/index.blade.php ENDPATH**/ ?>