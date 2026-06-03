<?php $__env->startSection('title', 'Pembayaran — TicketIn'); ?>

<?php $__env->startPush('styles'); ?>

<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<style>
  .step { display:none; }
  .step.active { display:block; }

  /* Countdown ring */
  .ring-wrap { position:relative; width:80px; height:80px; }
  .ring-wrap svg { transform:rotate(-90deg); }
  #ring-circle { stroke-dasharray:226; stroke-dashoffset:0; transition:stroke-dashoffset 1s linear; }

  /* Metode bayar */
  .pay-card { border:2px solid #e5e7eb; border-radius:14px; cursor:pointer; transition:all .2s; padding:14px 16px; background:white; }
  .pay-card.selected { border-color:#F5C400; background:#fffbeb; box-shadow:0 0 0 3px rgba(245,196,0,.15); }
  .pay-card:hover { border-color:#F5C400; }

  /* Bank icon */
  .bank-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:white; font-weight:800; font-size:12px; flex-shrink:0; }

  /* Copy button */
  .copy-btn { background:#f1f5f9; border:1px solid #e2e8f0; border-radius:8px; padding:4px 10px; font-size:12px; font-weight:600; color:#475569; cursor:pointer; transition:all .2s; }
  .copy-btn:hover { background:#F5C400; color:#001840; border-color:#F5C400; }
  .copy-btn.copied { background:#22c55e; color:white; border-color:#22c55e; }

  /* Ticket */
  .e-ticket { background:linear-gradient(135deg,#102A71,#001840); border-radius:20px; color:white; position:relative; overflow:hidden; }
  .e-ticket::before { content:''; position:absolute; top:50%; left:-12px; width:24px; height:24px; background:#f9fafb; border-radius:50%; transform:translateY(-50%); }
  .e-ticket::after  { content:''; position:absolute; top:50%; right:-12px; width:24px; height:24px; background:#f9fafb; border-radius:50%; transform:translateY(-50%); }
  .ticket-dashed { border-top:2px dashed rgba(255,255,255,.2); margin:16px 0; }
  .qr-box { background:repeating-conic-gradient(white 0% 25%,#102A71 0% 50%) 0 0 / 7px 7px; border-radius:8px; }

  /* Confetti */
  @keyframes fall { 0%{transform:translateY(-50px) rotate(0);opacity:1}100%{transform:translateY(105vh) rotate(540deg);opacity:0} }
  .confetti-piece { position:fixed; width:10px; height:10px; border-radius:2px; animation:fall linear forwards; pointer-events:none; }

  /* Pulse success */
  @keyframes pulseSuc { 0%{box-shadow:0 0 0 0 rgba(34,197,94,.4)}70%{box-shadow:0 0 0 16px rgba(34,197,94,0)}100%{box-shadow:0 0 0 0 rgba(34,197,94,0)} }
  .pulse-success { animation:pulseSuc 1.5s infinite; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="pt-24 min-h-screen bg-gray-50">
<div class="max-w-2xl mx-auto px-6 py-10">

  
  
  
  <div class="step active" id="step-instruksi">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">

      
      <div class="flex items-center justify-between mb-5">
        <div>
          <h1 class="text-xl font-extrabold text-navy-deep">Selesaikan Pembayaran</h1>
          <p class="text-gray-500 text-sm mt-0.5">Order: <span class="font-mono font-bold text-navy-mid"><?php echo e($order->order_code); ?></span></p>
        </div>
        
        <div class="flex flex-col items-center">
          <div class="ring-wrap">
            <svg width="80" height="80" viewBox="0 0 80 80">
              <circle cx="40" cy="40" r="36" fill="none" stroke="#e5e7eb" stroke-width="5"/>
              <circle id="ring-circle" cx="40" cy="40" r="36" fill="none" stroke="#F5C400" stroke-width="5" stroke-linecap="round"/>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
              <span id="countdown-text" class="text-base font-extrabold text-navy-deep">14:59</span>
            </div>
          </div>
          <p class="text-xs text-gray-400 mt-1">Batas waktu</p>
        </div>
      </div>

      
      <div class="bg-navy-mid/5 border border-navy-mid/10 rounded-xl p-4 mb-5">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-gray-500">Total Pembayaran</p>
            <p class="text-2xl font-extrabold text-navy-deep mt-0.5">Rp <?php echo e(number_format($order->total_harga, 0, ',', '.')); ?></p>
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-500">Metode</p>
            <p class="font-bold text-navy-mid text-sm mt-0.5"><?php echo e($order->metode_bayar); ?></p>
          </div>
        </div>
      </div>

      
      <?php
        $metode = strtolower($order->metode_bayar);
        $vaNumber = '8277' . rand(10000000, 99999999);
        $isTransfer = in_array($metode, ['bca','mandiri','bni']);
        $isEwallet = in_array($metode, ['gopay','ovo','dana','qris']);
      ?>

      <?php if($isTransfer): ?>
        
        <div class="mb-5">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Nomor Virtual Account</p>
          <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5">
            <div>
              <p class="text-xs text-gray-400 mb-1"><?php echo e(strtoupper($order->metode_bayar)); ?> Virtual Account</p>
              <p class="text-xl font-extrabold text-navy-deep tracking-wider" id="va-number"><?php echo e($vaNumber); ?></p>
            </div>
            <button class="copy-btn" onclick="copyVA()">Salin</button>
          </div>
          <p class="text-xs text-gray-400 mt-2">⚠️ Nomor VA hanya berlaku untuk 1x transaksi ini</p>
        </div>

        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Cara Pembayaran via <?php echo e(strtoupper($order->metode_bayar)); ?></p>
        <div class="space-y-2 mb-5">
          <?php if($metode === 'bca'): ?>
            <?php $__currentLoopData = ['Buka aplikasi myBCA atau KlikBCA Internet Banking','Pilih menu Pembayaran → Virtual Account','Masukkan Nomor VA di atas: '.$vaNumber,'Periksa detail pembayaran, pastikan nama dan nominal sesuai','Masukkan PIN/OTP dan konfirmasi pembayaran','Simpan bukti bayar — tiket akan dikirim ke email kamu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-start gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-navy-mid text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5"><?php echo e($i+1); ?></span>
              <?php echo e($step); ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php elseif($metode === 'mandiri'): ?>
            <?php $__currentLoopData = ['Buka aplikasi Livin by Mandiri','Pilih Pembayaran → Multipayment','Masukkan kode perusahaan: 88277 dan Nomor VA: '.$vaNumber,'Ikuti instruksi hingga pembayaran selesai','Simpan bukti bayar sebagai referensi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-start gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-navy-mid text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5"><?php echo e($i+1); ?></span>
              <?php echo e($step); ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php else: ?>
            <?php $__currentLoopData = ['Buka aplikasi mobile banking BNI','Pilih Transfer → Virtual Account','Masukkan Nomor VA: '.$vaNumber,'Konfirmasi pembayaran dan simpan bukti']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-start gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-navy-mid text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5"><?php echo e($i+1); ?></span>
              <?php echo e($step); ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </div>

      <?php elseif($isEwallet): ?>
        
        <div class="text-center mb-5">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">
            <?php if($metode === 'qris'): ?> Scan QR Code <?php else: ?> Kode Pembayaran <?php endif; ?>
          </p>
          <div class="qr-box w-40 h-40 mx-auto mb-4 shadow-inner"></div>
          <p class="text-xs text-gray-400">QR Code berlaku selama <span class="font-bold text-navy-mid" id="qr-countdown">14:59</span></p>
        </div>

        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Cara Pembayaran via <?php echo e(strtoupper($order->metode_bayar)); ?></p>
        <div class="space-y-2 mb-5">
          <?php if($metode === 'gopay'): ?>
            <?php $__currentLoopData = ['Buka aplikasi Gojek di smartphone kamu','Tap ikon GoPay → Scan QR','Arahkan kamera ke QR Code di atas','Periksa nominal Rp '.number_format($order->total_harga,0,',','.').' dan konfirmasi','Pembayaran selesai!']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-start gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-navy-mid text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5"><?php echo e($i+1); ?></span><?php echo e($step); ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php elseif($metode === 'ovo'): ?>
            <?php $__currentLoopData = ['Buka aplikasi OVO di smartphone kamu','Tap icon Scan di halaman utama','Scan QR Code di atas','Masukkan PIN OVO untuk konfirmasi','Pembayaran berhasil!']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-start gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-navy-mid text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5"><?php echo e($i+1); ?></span><?php echo e($step); ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php else: ?>
            <?php $__currentLoopData = ['Buka aplikasi m-banking atau dompet digital apapun','Pilih menu Scan QR / QRIS','Scan QR Code di atas','Konfirmasi nominal dan selesaikan pembayaran']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-start gap-3 text-sm text-gray-600">
              <span class="w-6 h-6 rounded-full bg-navy-mid text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5"><?php echo e($i+1); ?></span><?php echo e($step); ?>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </div>

      <?php else: ?>
        
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-5 text-sm text-gray-600">
          Silakan lakukan pembayaran sebesar <strong class="text-navy-deep">Rp <?php echo e(number_format($order->total_harga, 0, ',', '.')); ?></strong> sesuai metode yang dipilih. Tim kami akan memverifikasi pembayaran dalam 1x24 jam.
        </div>
      <?php endif; ?>

      
      <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-700 flex gap-3 items-start">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p>Setelah pembayaran berhasil, klik tombol <strong>"Konfirmasi Pembayaran"</strong> di bawah untuk mendapatkan e-ticket kamu.</p>
      </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
      <h3 class="font-bold text-navy-deep mb-4">Ringkasan Pesanan</h3>
      <div class="flex gap-4 mb-4">
        <img src="<?php echo e($order->event->cover_url); ?>" class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
        <div>
          <p class="font-bold text-navy-deep text-sm"><?php echo e($order->event->judul); ?></p>
          <p class="text-gray-400 text-xs mt-0.5"><?php echo e($order->event->tanggal_waktu->format('d M Y')); ?> · <?php echo e($order->event->lokasi_kota); ?></p>
        </div>
      </div>
      <div class="border-t border-gray-100 pt-3 space-y-1.5 text-sm">
        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex justify-between text-gray-600">
          <span><?php echo e($item->qty); ?>× <?php echo e($item->ticketCategory->nama_kategori); ?></span>
          <span>Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="flex justify-between font-bold text-navy-deep border-t border-gray-100 pt-2 mt-2">
          <span>Total</span>
          <span class="text-gold">Rp <?php echo e(number_format($order->total_harga, 0, ',', '.')); ?></span>
        </div>
      </div>
    </div>

    
    <button onclick="konfirmasiPembayaran()"
            class="w-full bg-gold text-navy-deep font-bold py-4 rounded-xl hover:bg-gold-light transition-all hover:shadow-lg hover:shadow-gold/30 text-sm mb-3">
      ✅ Konfirmasi Pembayaran Sudah Dilakukan
    </button>
    <a href="<?php echo e(route('dashboard')); ?>" class="block w-full text-center text-gray-400 hover:text-navy-mid text-sm py-2 transition-colors">
      Bayar Nanti
    </a>
  </div>

  
  
  
  <div class="step" id="step-verifikasi">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
      <div class="w-16 h-16 bg-navy-mid/10 rounded-full flex items-center justify-center mx-auto mb-5">
        <svg class="w-8 h-8 text-navy-mid animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      <h2 class="text-xl font-extrabold text-navy-deep mb-2">Memverifikasi Pembayaran...</h2>
      <p class="text-gray-500 text-sm">Mohon tunggu, sistem sedang memeriksa pembayaran kamu.</p>
    </div>
  </div>

  
  
  
  <div class="step" id="step-sukses">

    
    <div id="sukses-warning" class="hidden mb-4 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex items-start gap-3 text-sm text-amber-800">
      <span class="text-lg flex-shrink-0">⚠️</span>
      <span>Pembayaran sedang diverifikasi. Jika tiket belum muncul dalam 5 menit, hubungi <a href="mailto:cs@ticketin.com" class="font-semibold underline">cs@ticketin.com</a> dengan kode order <strong><?php echo e($order->order_code); ?></strong>.</span>
    </div>
    <div id="confetti-container" class="fixed inset-0 pointer-events-none z-50 overflow-hidden"></div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center mb-5">
      <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5 pulse-success">
        <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
      <h2 class="text-2xl font-extrabold text-navy-deep mb-2">Pembayaran Berhasil! 🎉</h2>
      <p class="text-gray-500 text-sm leading-relaxed mb-6">
        Terima kasih <strong class="text-navy-mid"><?php echo e($order->user->nama_lengkap); ?></strong>!<br>
        E-ticket kamu sudah siap di bawah ini.
      </p>
      <div class="flex gap-3 justify-center">
        <a href="<?php echo e(route('profile.index')); ?>" class="bg-navy-mid text-white font-bold px-5 py-2.5 rounded-xl hover:bg-navy-deep transition-all text-sm">Lihat Semua Tiket</a>
        <a href="<?php echo e(route('dashboard')); ?>" class="bg-gray-100 text-gray-700 font-bold px-5 py-2.5 rounded-xl hover:bg-gray-200 transition-all text-sm">Ke Beranda</a>
      </div>
    </div>

    
    <div class="e-ticket p-6 shadow-xl">
      <div class="flex justify-between items-start mb-4">
        <div>
          <p class="text-xs text-white/50 uppercase tracking-wider mb-1">E-Ticket TicketIn</p>
          <h3 class="text-lg font-extrabold text-gold leading-tight"><?php echo e($order->event->judul); ?></h3>
        </div>
        <span class="bg-green-500/20 text-green-400 border border-green-400/30 text-xs font-bold px-3 py-1 rounded-full">VALID</span>
      </div>

      <div class="grid grid-cols-2 gap-3 text-xs mb-4">
        <div><p class="text-white/50 mb-1">Tanggal</p><p class="font-bold text-white"><?php echo e($order->event->tanggal_waktu->format('d M Y')); ?></p></div>
        <div><p class="text-white/50 mb-1">Waktu</p><p class="font-bold text-white"><?php echo e($order->event->tanggal_waktu->format('H:i')); ?> WIB</p></div>
        <div><p class="text-white/50 mb-1">Lokasi</p><p class="font-bold text-white"><?php echo e($order->event->lokasi_kota); ?></p></div>
        <div><p class="text-white/50 mb-1">Tiket</p><p class="font-bold text-white"><?php echo e($order->total_qty); ?> Tiket</p></div>
        <div><p class="text-white/50 mb-1">Tipe</p><p class="font-bold text-white text-xs"><?php echo e($order->ticket_summary); ?></p></div>
        <div><p class="text-white/50 mb-1">Pembayaran</p><p class="font-bold text-gold">Rp <?php echo e(number_format($order->total_harga,0,',','.')); ?></p></div>
      </div>

      <div class="ticket-dashed"></div>

      <div class="flex items-center justify-between">
        <div>
          <p class="text-white/50 text-xs mb-1">Kode Tiket</p>
          <p class="font-mono font-extrabold text-white tracking-widest"><?php echo e($order->order_code); ?></p>
        </div>
        <div class="qr-box w-20 h-20 shadow-inner flex-shrink-0"></div>
      </div>

      <div class="mt-4 text-center text-xs text-white/40">
        Tunjukkan QR Code ini di pintu masuk event
      </div>
    </div>

    
    <div class="mt-4 flex gap-3">
      <button onclick="window.print()" class="flex-1 bg-white border border-gray-200 text-gray-700 font-semibold py-3 rounded-xl hover:bg-gray-50 transition-all text-sm flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        Print Tiket
      </button>
      <button onclick="shareTicket()" class="flex-1 bg-gold text-navy-deep font-bold py-3 rounded-xl hover:bg-gold-light transition-all text-sm flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
        Bagikan
      </button>
    </div>
  </div>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  // ── Countdown Timer ──────────────────────────────────────
  let totalSecs = 15 * 60 - 1; // 14:59
  const circle = document.getElementById('ring-circle');
  const totalDash = 226;
  const countdownText = document.getElementById('countdown-text');
  const qrCountdown   = document.getElementById('qr-countdown');

  const timer = setInterval(() => {
    if (totalSecs <= 0) { clearInterval(timer); countdownText.textContent = '00:00'; return; }
    totalSecs--;
    const m = String(Math.floor(totalSecs / 60)).padStart(2,'0');
    const s = String(totalSecs % 60).padStart(2,'0');
    const txt = `${m}:${s}`;
    if (countdownText) countdownText.textContent = txt;
    if (qrCountdown) qrCountdown.textContent = txt;
    const offset = totalDash - (totalDash * totalSecs) / (15 * 60);
    if (circle) circle.style.strokeDashoffset = offset;
  }, 1000);

  // ── Copy VA Number ────────────────────────────────────────
  function copyVA() {
    const va = document.getElementById('va-number');
    if (!va) return;
    navigator.clipboard.writeText(va.textContent.trim()).then(() => {
      const btn = document.querySelector('.copy-btn');
      btn.textContent = 'Tersalin!';
      btn.classList.add('copied');
      setTimeout(() => { btn.textContent = 'Salin'; btn.classList.remove('copied'); }, 2000);
    });
  }

  // ── Step navigation ───────────────────────────────────────
  function showStep(id) {
    document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // ── Konfirmasi Pembayaran ─────────────────────────────────
  let _konfirmasiDone = false; // cegah double-submit

  function konfirmasiPembayaran() {
    if (_konfirmasiDone) return;
    _konfirmasiDone = true;

    showStep('step-verifikasi');

    fetch('/checkout/konfirmasi/<?php echo e($order->order_code); ?>', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        'X-Requested-With': 'XMLHttpRequest',
      }
    })
    .then(r => {
      if (r.status === 401) {
        // Session expired — redirect ke login
        window.location.href = '/login';
        return null;
      }
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return r.json();
    })
    .then(data => {
      if (!data) return;
      // Tampilkan step sukses
      showStep('step-sukses');
      buatConfetti();
      // Ganti history entry agar tombol back tidak kembali ke step instruksi
      history.replaceState(null, '', window.location.href);
    })
    .catch(err => {
      console.error('Konfirmasi error:', err);
      _konfirmasiDone = false; // allow retry
      // Tetap tampilkan sukses (payment sudah diterima secara optimistis)
      // tapi tandai sebagai perlu verifikasi manual
      showStep('step-sukses');
      buatConfetti();
      // Tampilkan pesan warning
      const warn = document.getElementById('sukses-warning');
      if (warn) warn.classList.remove('hidden');
    });
  }

  // ── Confetti ──────────────────────────────────────────────
  function buatConfetti() {
    const colors = ['#F5C400','#102A71','#22c55e','#ef4444','#3b82f6','#f97316'];
    const container = document.getElementById('confetti-container');
    for (let i = 0; i < 80; i++) {
      const el = document.createElement('div');
      el.className = 'confetti-piece';
      el.style.cssText = `
        left:${Math.random()*100}%;
        top:-20px;
        background:${colors[Math.floor(Math.random()*colors.length)]};
        width:${6+Math.random()*8}px;
        height:${6+Math.random()*8}px;
        border-radius:${Math.random()>.5?'50%':'2px'};
        animation-duration:${2+Math.random()*2}s;
        animation-delay:${Math.random()*1.5}s;
      `;
      container.appendChild(el);
    }
    setTimeout(() => container.innerHTML = '', 5000);
  }

  // ── Share ─────────────────────────────────────────────────
  function shareTicket() {
    if (navigator.share) {
      navigator.share({ title: 'Tiket TicketIn - <?php echo e($order->event->judul); ?>', url: location.href });
    } else {
      navigator.clipboard.writeText(location.href);
      alert('Link tiket disalin ke clipboard!');
    }
  }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Application\Software\XAMPP\htdocs\ticketin-laravel\resources\views/checkout/sukses.blade.php ENDPATH**/ ?>